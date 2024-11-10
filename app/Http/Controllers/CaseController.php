<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Firm;
use App\Models\Patient;
use App\Models\PracticeLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Insurance;
use App\Models\user;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class CaseController extends Controller
{
    function addCase(Request $request)
    {
        Log::info('Case Information');
        try {
            $CaseData = $request->all();
            Log::info($CaseData);
            $patientId = $CaseData['userId'];
            $firmName = $CaseData['firmName'];
            $insuranceName = $CaseData['insuranceName'];
            $practice_location_name = $CaseData['practiceLocation'];
            $insurance = Insurance::where('name', $insuranceName)->get();
            $FIRM = Firm::where('name', $firmName)->get();

            $DOA = $CaseData['doa'];
            Log::info("This is the DOA");
            Log::info($DOA);


            $practice_location_id = PracticeLocation::where('name', $practice_location_name)->first()['id'];
            $insuranceId = $insurance[0]['id'];

            $firmId = $FIRM[0]['id'];
            $patient = Patient::findOrFail($patientId);

            $userDOB = $patient->user->dob;
            Log::info($userDOB);


            $carbonDate1 = Carbon::parse($DOA);
            $carbonDate2 = Carbon::parse($userDOB);

            if (!$carbonDate2->lessThan($carbonDate1)) {
                return response()->json(['message' => "DOA Should be Greater than the DOB"], 400);
            }

            Log::info($patient);
            $case = $patient->cases()->create([
                'case_type' => $CaseData['caseType'],
                'purpose_of_visit' => $CaseData['purposeOfVisit'],
                'category' => $CaseData['category'],
                'DOA' => $CaseData['doa'],
                'insurance_id' => $insuranceId,
                'firm_id' => $firmId,
                'practice_location_id' => $practice_location_id
            ]);
            Log::info('Case Information:');
            return response()->json(['message' => 'Case information logged successfully.', 'case_Id' => $case['id']], 200);
        } catch (\Exception $e) {
            Log::error('Error logging patient information: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to log patient information.'], 500);
        }
    }
    function getCase(Request $request, $PID)
    {
        try {
            $USER = user::with('patient')->where('id', $PID)->first();
            Log::info($USER);
            $patient_id = $USER['patient']['patient_id'];
            $case = Cases::where('PID', $patient_id)
                ->with(['insurance', 'firm', 'practiceLocation'])
                ->get()
                ->map(function ($case) {
                    return [
                        'category' => $case->category,
                        'purpose_of_visit' => $case->purpose_of_visit,
                        'case_type' => $case->case_type,
                        'DOA' => $case->DOA,
                        'id' => $case->id,
                        'insurance_name' => $case->insurance->name,
                        'firm_name' => $case->firm->name,
                        'practice_location_name' => $case->practiceLocation->name,
                        'patient_id' => $case->PID,
                        'created_at' => $case->created_at,
                        'updated_at' => $case->updated_at,
                        'deleted_at' => $case->deleted_at,
                    ];
                });
            return response()->json($case);
        } catch (Exception $e) {
            return response()->json(['message' => "Case Not Found."], 404);
        }
    }
    function updateCase(Request $request, $caseId)
    {
        try {
            Log::info($caseId);
            Log::info($request);
            $practice_location_id = (PracticeLocation::where('name', $request->input('practiceLocation'))->first())['id'];
            $insurance_id = (Insurance::where('name', $request->input('insuranceName'))->first())['id'];
            $firm_id = (Firm::where('name', $request->input('firmName'))->first())['id'];
            Log::info($practice_location_id);
            Log::info($insurance_id);
            Log::info($firm_id);
            $updateData = [
                'practice_location_id' => $practice_location_id,
                'category' => $request->input('category'),
                'purpose_of_visit' => $request->input('purposeOfVisit'),
                'case_type' => $request->input('caseType'),
                'DOA' => $request->input('doa'),
                'insurance_id' => $insurance_id,
                'firm_id' => $firm_id
            ];
            $case = Cases::with('patient.user')
                ->where('id', $caseId)
                ->first();

            $user = $case->patient->user;
            $userDOB = $user->dob;

            $carbonDate1 = Carbon::parse($updateData['DOA']);
            $carbonDate2 = Carbon::parse($userDOB);
            if (!$carbonDate2->lessThan($carbonDate1)) {
                return response()->json(['message' => "DOA Should be Greater than the DOB"], 400);
            }
            Cases::where('id', $caseId)->update($updateData);
            return response()->json(['message' => 'Successfully Updated the Case.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Cannot update the form. Try Again '], 500);
        }
    }
    function searchCases(Request $request, $type, $term, $patientId)
    {
        Log::info("Search type: " . $type);
        Log::info("Search term: " . $term);
        Log::info("Patient ID: " . $patientId);
        if (!!$type && !!$term) {
            if ($type == 'id' || $type == 'category' || $type == 'purpose_of_visit' || $type == 'case_type' || $type == 'DOA') {
                $cases = DB::table('cases')
                    ->join('insurances', 'cases.insurance_id', '=', 'insurances.id')
                    ->join('firms', 'cases.firm_id', '=', 'firms.id')
                    ->join('practice_locations', 'cases.practice_location_id', '=', 'practice_locations.id')
                    ->where('cases.PID', $patientId)
                    ->where('cases.' . $type, 'Like', '%' . $term . '%')
                    ->select(
                        'cases.category as category',
                        'cases.purpose_of_visit as purpose_of_visit',
                        'cases.case_type as case_type',
                        'cases.DOA as DOA',
                        'cases.id as id',
                        'insurances.name as insurance_name',
                        'firms.name as firm_name',
                        'practice_locations.name as practice_location_name',
                        'cases.PID as patient_id'
                    )
                    ->get();
            } else if ($type == 'insurance_name') {
                $cases = DB::table('cases')
                    ->join('insurances', 'cases.insurance_id', '=', 'insurances.id')
                    ->join('firms', 'cases.firm_id', '=', 'firms.id')
                    ->join('practice_locations', 'cases.practice_location_id', '=', 'practice_locations.id')
                    ->where('cases.PID', $patientId)
                    ->where('insurances.name', 'Like', '%' . $term . '%')
                    ->select(
                        'cases.category as category',
                        'cases.purpose_of_visit as purpose_of_visit',
                        'cases.case_type as case_type',
                        'cases.DOA as DOA',
                        'cases.id as id',
                        'insurances.name as insurance_name',
                        'firms.name as firm_name',
                        'practice_locations.name as practice_location_name',
                        'cases.PID as patient_id'
                    )
                    ->get();
            } else if ($type == 'firm_name') {
                $cases = DB::table('cases')
                    ->join('insurances', 'cases.insurance_id', '=', 'insurances.id')
                    ->join('firms', 'cases.firm_id', '=', 'firms.id')
                    ->join('practice_locations', 'cases.practice_location_id', '=', 'practice_locations.id')
                    ->where('cases.PID', $patientId)
                    ->where('firms.name', 'Like', '%' . $term . '%')
                    ->select(
                        'cases.category as category',
                        'cases.purpose_of_visit as purpose_of_visit',
                        'cases.case_type as case_type',
                        'cases.DOA as DOA',
                        'cases.id as id',
                        'insurances.name as insurance_name',
                        'firms.name as firm_name',
                        'practice_locations.name as practice_location_name',
                        'cases.PID as patient_id'
                    )
                    ->get();
            } else if ($type == 'practice_location') {
                $cases = DB::table('cases')
                    ->join('insurances', 'cases.insurance_id', '=', 'insurances.id')
                    ->join('firms', 'cases.firm_id', '=', 'firms.id')
                    ->join('practice_locations', 'cases.practice_location_id', '=', 'practice_locations.id')
                    ->where('cases.PID', $patientId)
                    ->where('practice_locations.name', 'Like', '%' . $term . '%')
                    ->select(
                        'cases.category as category',
                        'cases.purpose_of_visit as purpose_of_visit',
                        'cases.case_type as case_type',
                        'cases.DOA as DOA',
                        'cases.id as id',
                        'insurances.name as insurance_name',
                        'firms.name as firm_name',
                        'practice_locations.name as practice_location_name',
                        'cases.PID as patient_id'
                    )
                    ->get();
            }
        }
        Log::info($cases);
        return response()->json($cases);
    }
}
