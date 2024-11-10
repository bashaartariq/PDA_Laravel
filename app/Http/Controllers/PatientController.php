<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;
use App\Models\user;
use Exception;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    function addPatientInfo(Request $request)
    {
        Log::info($request);
        try {
            $patient = Patient::updateOrCreate(
                ['patient_id' => $request->input('user_id')],
                [
                    'home_phone' => $request->input('homePhone'),
                    'cell_phone' => $request->input('cellPhone'),
                    'ssn' => $request->input('ssn'),
                    'address' => $request->input('address'),
                    'city' => $request->input('city'),
                    'zip' => $request->input('zip'),
                    'state' => $request->input('state'),
                ]
            );
            return response()->json(['message' => 'Successfully Created and Updated the Table'], 200);
        } catch (\Exception $e) {
            Log::error('Error updating or creating patient: ' . $e);
            return response()->json(['message' => 'Unable to Create or Update'], 500);
        }
    }

    function getPatientInfo(Request $request, $userid)
    {
        Log::info($userid);
        $user = User::where('id', $userid)->first();
        $patient = $user->patient;
        Log::info($patient);
        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $patient,
        ], 200);
    }

    function getAllPatients(Request $request)
    {
        try {
            $patient = Patient::with('user')->get();
            Log::info($patient);
            return response()->json($patient);
        } catch (Exception $e) {
            Log::info($e);
            return response()->json(['message' => 'Patient Not Found']);
        }
    }
    function searchPatient(Request $request, $type, $term)
    {
        Log::info($type);
        Log::info($term);
        $results = DB::table('patients')
            ->join('users', 'patients.patient_id', '=', 'users.id')
            ->select(
                'patients.address',
                'patients.cell_phone',
                'patients.city',
                'patients.created_at AS patient_created_at',
                'patients.deleted_at AS patient_deleted_at',
                'patients.home_phone',
                'patients.patient_id',
                'patients.ssn',
                'patients.state',
                'patients.updated_at AS patient_updated_at',
                'users.created_at AS user_created_at',
                'users.deleted_at AS user_deleted_at',
                'users.dob',
                'users.email',
                'users.firstName',
                'users.gender',
                'users.id AS user_id',
                'users.lastName',
                'patients.zip AS zip',
                'users.middleName',
                'users.role',
                'users.updated_at AS user_updated_at'
            )
            ->where($type, 'LIKE', '%' . $term . '%')
            ->get();
        $formattedResults = $results->map(function ($item) {
            return [
                'address' => $item->address,
                'cell_phone' => $item->cell_phone,
                'city' => $item->city,
                'created_at' => $item->patient_created_at,
                'deleted_at' => $item->patient_deleted_at,
                'home_phone' => $item->home_phone,
                'patient_id' => $item->patient_id,
                'ssn' => $item->ssn,
                'state' => $item->state,
                'updated_at' => $item->patient_updated_at,
                'user' => [
                    'created_at' => $item->user_created_at,
                    'deleted_at' => $item->user_deleted_at,
                    'dob' => $item->dob,
                    'email' => $item->email,
                    'firstName' => $item->firstName,
                    'gender' => $item->gender,
                    'id' => $item->user_id,
                    'lastName' => $item->lastName,
                    'middleName' => $item->middleName,
                    'role' => $item->role,
                    'updated_at' => $item->user_updated_at,
                ],
                'zip' => $item->zip
            ];
        });
        Log::info($formattedResults);
        Log::info($formattedResults);
        return response()->json($formattedResults);
    }
}
