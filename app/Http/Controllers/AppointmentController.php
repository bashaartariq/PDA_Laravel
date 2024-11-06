<?php

namespace App\Http\Controllers;

use App\Models\appointment;
use App\Models\Cases;
use App\Models\Patient;
use App\Models\user;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    function addAppointment(Request $request)
    {
        Log::error($request->all());
        try {
            $appointmentData = $request->all();
            Log::info($appointmentData);
            $caseId = $appointmentData['case_id'];
            $case = Cases::findOrFail($caseId);
            $startTime = $appointmentData['time'];
            $endTime = date('H:i', strtotime($startTime) + ($appointmentData['duration']));
            Log::info($endTime);
            $existingAppointment = appointment::where('doctor_id', $appointmentData['doctor'])
                ->where('date', $appointmentData['date'])
                ->where(function ($query) use ($startTime, $endTime) {
                    $query->whereBetween('appointment_time', [$startTime, $endTime])
                        ->orWhereBetween(DB::raw('DATE_ADD(appointment_time, INTERVAL Duration MINUTE)'), [$startTime, $endTime]);
                })
                ->first();
            if ($existingAppointment) {
                Log::info("This doctor already has an appointment at this date and time");
                return response()->json(['message' => 'This doctor already has an appointment at this date and time.'], 400);
            }

            $appointment = $case->appointment()->create([
                'case_id' => $caseId,
                'doctor_id' => $appointmentData['doctor'],
                'speciality_id' => $appointmentData['speciality'],
                'practice_location_id' => $appointmentData['location'],
                'date' => $appointmentData['date'],
                'appointment_time' => $appointmentData['time'],
                'appointment_type_id' => $appointmentData['appointmentType'],
                'Duration' => $appointmentData['duration'],
                'Description' => $appointmentData['description'],
            ]);

            Log::info($appointment);
            return response()->json(['message' => "Successfully Created An Appointment"], 201);
        } catch (QueryException $e) {
            Log::error('Database Error: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Database error occurred.'], 500);
        } catch (\Exception $e) {
            Log::error('An unexpected error occurred: ', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'An unexpected error occurred.'], 500);
        }
    }
    function getCaseWithAppointments(Request $request, $PID)
    {
        Log::info($PID);
        $patient = user::with('patient.cases.appointment')->find($PID);
        Log::info($patient);
        return response()->json($patient);
    }

    function getAppointment(Request $request, $caseid)
    {
        Log::info("Working");
        try {
            $appointments = appointment::with(['doctor.user', 'speciality', 'practiceLocation'])
                ->where('case_id', $caseid)
                ->get()
                ->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'created_at' => $appointment->created_at,
                        'updated_at' => $appointment->updated_at,
                        'case_id' => $appointment->case_id,
                        'speciality_id' => $appointment->speciality_id,
                        'practice_location_id' => $appointment->practice_location_id,
                        'date' => $appointment->date,
                        'appointment_time' => $appointment->appointment_time,
                        'appointment_type_id' => $appointment->appointment_type_id,
                        'appointment_type' => $appointment->appointmentType->name,
                        'Duration' => $appointment->Duration,
                        'Description' => $appointment->Description,
                        'doctor_id' => $appointment->doctor_id,
                        'doctor_name' => $appointment->doctor->user->firstName . ' ' . ($appointment->doctor->user->middleName ? $appointment->doctor->user->middleName . ' ' : '') . $appointment->doctor->user->lastName,
                        'speciality_name' => $appointment->speciality->name,
                        'practice_location_name' => $appointment->practiceLocation->name,
                    ];
                });

            Log::info($appointments);
            return response()->json($appointments);
        } catch (Exception $e) {
            return response()->json(['message' => "Appointment Not Found"], 404);
        }
    }
    
    function updateAppointment(Request $request,$appointmentId)
    {
        try{
            Log::info($appointmentId);
        Log::info($request);
        $updateData = [
            "date"=>$request->input('date'),
            "appointment_time"=>$request->input('time'),
            "appointment_type_id"=>$request->input('appointmentType'),
            "speciality_id"=>$request->input('speciality'),
            "doctor_id"=>$request->input('doctor'),
            "practice_location_id"=>$request->input('location'),
            "Duration"=>$request->input('duration'),
            "Description"=>$request->input('description')
        ];
        $startTime = $updateData['appointment_time'];
        $endTime = date('H:i', strtotime($startTime) + ($updateData['Duration']));
        $existingAppointment = appointment::where('doctor_id', $updateData['doctor_id'])
            ->where('date', $updateData['date'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('appointment_time', [$startTime, $endTime])
                    ->orWhereBetween(DB::raw('DATE_ADD(appointment_time, INTERVAL Duration MINUTE)'), [$startTime, $endTime]);
            })
            ->first();
        if ($existingAppointment) {
            Log::info("This doctor already has an appointment at this date and time");
            return response()->json(['message' => 'This doctor already has an appointment at this date and time.'], 400);
        }
        Log::info($updateData);
        appointment::where('id',$appointmentId)->update($updateData);
        return response()->json(['message'=>"Successfully updated the Appointment"],200);
        }
        catch(Exception $e)
        {
            return response()->json(['message'=>"Failed to Update the Appointment"],500);
        }
    }
}
