<?php

namespace App\Http\Controllers;

use App\Models\appointment;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    function addDoctor(Request $request)
    {
        Log::info($request->input('userId'));
        try {
            $doctor = Doctor::create([
                'user_id' => $request->input('userId'),
                'practice_location_id' => $request->input('practicelocation'),
                'speciality_id' => $request->input('speciality'),
            ]);
            return response()->json($doctor, 201); // 201 Created status
        } catch (Exception $e) {
            // Log the error message for debugging
            Log::error('Failed to create doctor', [
                'error' => $e->getMessage(),
                'user_id' => $request->input('userId'),
                'practice_location_id' => $request->input('practicelocation'),
                'speciality_id' => $request->input('speciality'),
            ]);
            return response()->json(['error' => 'Failed to create doctor: ' . $e->getMessage()], 500); // 500 Internal Server Error
        }
    }
    function getDoctor($practiceLocationId, $specialityId)
    {

        try {
            $doctors = Doctor::with('user')
                ->where('practice_location_id', $practiceLocationId)
                ->where('speciality_id', $specialityId)
                ->get();
            Log::info($doctors);

            if ($doctors->isEmpty()) {
                return response()->json([
                    'message' => 'No doctors found for the specified criteria.'
                ], 404);
            }
            return response()->json($doctors, 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while retrieving doctors.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    function getAppointmentForDoctor(Request $request, $DoctorId)
    {
        Log::info($DoctorId);
        $doctor = Doctor::where('user_id', $DoctorId)
            ->with('appointment', 'speciality', 'practiceLocation', 'user')
            ->get();
        $doctor = $doctor->map(function ($doc) {
            $doc->appointment = $doc->appointment->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'description' => $appointment->Description,
                    'duration' => $appointment->Duration,
                    'date' => $appointment->date,
                    'time' => $appointment->appointment_time,
                    'appointment_type' => $appointment->appointmentType->name,
                    'speciality' => $appointment->speciality->name,
                    'practice_location' => $appointment->practiceLocation->name,
                    'patient_id' => $appointment->case->patient->patient_id,
                    'patient_name' => trim(
                        $appointment->case->patient->user->firstName . ' ' .
                            (optional($appointment->case->patient->user)->middleName ? optional($appointment->case->patient->user)->middleName . ' ' : '') .
                            $appointment->case->patient->user->lastName
                    ),
                ];
            });
            return ['appointment' => $doc->appointment];
        });
        Log::info($doctor);
        return response()->json($doctor);
    }

    function getAppointmentCase(Request $request, $appointmentId)
    {
        $appointment = appointment::with('case.insurance', 'case.firm', 'case.practiceLocation')->find($appointmentId);
        if ($appointment) {
            $case = $appointment->case;
            $caseData = [
                'category' => $case->category,
                'purpose_of_visit' => $case->purpose_of_visit,
                'case_type' => $case->case_type,
                'DOA' => $case->DOA,
                'id' => $case->id,
                'insurance_name' => $case->insurance->name,
                'firm_name' => $case->firm->name,
                'practice_location_name' => $case->practiceLocation->name,
                'patient_id' => $case->PID,
                'created_at' => $case->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $case->updated_at->format('Y-m-d H:i:s'),
                'deleted_at' => $case->deleted_at,
            ];
            Log::info($caseData);
            return $caseData;
        } else {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
    }
}
