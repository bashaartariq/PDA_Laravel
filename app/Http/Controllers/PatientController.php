<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Patient;
use App\Models\user;
use Exception;

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
        try{
            $patient = Patient::with('user')->get();
            Log::info($patient);
            return response()->json($patient);}
        catch(Exception $e)
        {
            Log::info($e);
            return response()->json(['message'=>'Patient Not Found']);
        }
    }
}
