<?php

namespace App\Http\Controllers;

use App\Models\appointment;
use App\Models\Doctor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    function searchDoctorAppointment(Request $request, $type, $term, $userId)
    {
        Log::info($type);
        Log::info($term);
        Log::info($userId);
        if ($type == 'appointment_type') {
            $results = DB::table('doctors')
                ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->join('appointments_types', 'appointments.appointment_type_id', '=', 'appointments_types.id')
                ->join('cases', 'appointments.case_id', '=', 'cases.id')
                ->join('patients', 'cases.PID', '=', 'patients.patient_id')
                ->join('users', 'users.id', '=', 'patients.patient_id')
                ->where('doctors.user_id', $userId)
                ->where('appointments_types.name', 'LIKE', '%' . $term . '%')
                ->select(
                    'appointments.id',
                    'appointments.date',
                    'appointments.description',
                    'appointments.duration',
                    'users.id as patient_id',
                    'users.firstName as patient_name',
                    'practice_locations.name as practice_location',
                    'specialities.name as speciality',
                    'appointments.appointment_time as time',
                    'appointments_types.name as appointment_type'
                )
                ->get();
        } else {
            $results = DB::table('doctors')
                ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->join('appointments_types', 'appointments.appointment_type_id', '=', 'appointments_types.id')
                ->join('cases', 'appointments.case_id', '=', 'cases.id')
                ->join('patients', 'cases.PID', '=', 'patients.patient_id')
                ->join('users', 'users.id', '=', 'patients.patient_id')
                ->where('doctors.user_id', $userId)
                ->where('appointments.' . $type, 'LIKE', '%' . $term . '%')
                ->select(
                    'appointments.id',
                    'appointments.date',
                    'appointments.description',
                    'appointments.duration',
                    'users.id as patient_id',
                    'users.firstName as patient_name',
                    'practice_locations.name as practice_location',
                    'specialities.name as speciality',
                    'appointments.appointment_time as time',
                    'appointments_types.name as appointment_type'
                )
                ->get();
        }
        Log::info($results);
        return response()->json($results);
    }
    function searchDoctor(Request $request, $type, $term)
    {
        Log::info($type);
        Log::info($term);
        if ($type == 'name' && !!$term) {
            $results = DB::table('doctors')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->select(
                    DB::raw("CONCAT(users.firstName, ' ', users.middleName, ' ', users.lastName) AS doctor_name"),
                    'users.email',
                    'users.gender',
                    'doctors.id AS id',
                    'practice_locations.name AS practice_location',
                    'specialities.name AS speciality'
                )
                ->where('users.firstName', 'LIKE', '%' . $term . '%')
                ->orWhere('users.middleName', 'LIKE', '%' . $term . '%')
                ->orWhere('users.lastName', 'LIKE', '%' . $term . '%')
                ->get();
        } else {
            $results = DB::table('doctors')
                ->join('users', 'doctors.user_id', '=', 'users.id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->select(
                    DB::raw("CONCAT(users.firstName, ' ', users.middleName, ' ', users.lastName) AS doctor_name"),
                    'users.email',
                    'users.gender',
                    'doctors.id AS id',
                    'practice_locations.name AS practice_location',
                    'specialities.name AS speciality'
                )
                ->where($type, 'LIKE', '%' . $term . '%')
                ->get();
        }
    }
    function searchDoctorAppointments(Request $request, $type, $term, $doctorId)
    {
        Log::info($type);
        Log::info($term);
        Log::info($doctorId);
        if ($type == 'appointment_type') {
            Log::info("fds");
            $results = DB::table('doctors')
                ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->join('appointments_types', 'appointments.appointment_type_id', '=', 'appointments_types.id')
                ->join('cases', 'appointments.case_id', '=', 'cases.id')
                ->join('patients', 'cases.PID', '=', 'patients.patient_id')
                ->join('users', 'users.id', '=', 'patients.patient_id')
                ->where('doctors.id', $doctorId)
                ->where('appointments_types.name', 'LIKE', '%' . $term . '%')
                ->select(
                    'appointments.id',
                    'appointments.date',
                    'appointments.description',
                    'appointments.duration',
                    'users.id as patient_id',
                    'users.firstName as patient_name',
                    'practice_locations.name as practice_location',
                    'specialities.name as speciality',
                    'appointments.appointment_time as time',
                    'appointments_types.name as appointment_type'
                )
                ->get();
        } else {
            Log::info("hy");
            $results = DB::table('doctors')
                ->join('appointments', 'doctors.id', '=', 'appointments.doctor_id')
                ->join('specialities', 'doctors.speciality_id', '=', 'specialities.id')
                ->join('practice_locations', 'practice_locations.id', '=', 'doctors.practice_location_id')
                ->join('appointments_types', 'appointments.appointment_type_id', '=', 'appointments_types.id')
                ->join('cases', 'appointments.case_id', '=', 'cases.id')
                ->join('patients', 'cases.PID', '=', 'patients.patient_id')
                ->join('users', 'users.id', '=', 'patients.patient_id')
                ->where('doctors.id', $doctorId)
                ->where('appointments.' . $type, 'LIKE', '%' . $term . '%')
                ->select(
                    'appointments.id',
                    'appointments.date',
                    'appointments.description',
                    'appointments.duration',
                    'users.id as patient_id',
                    'users.firstName as patient_name',
                    'practice_locations.name as practice_location',
                    'specialities.name as speciality',
                    'appointments.appointment_time as time',
                    'appointments_types.name as appointment_type'
                )
                ->get();
        }
        Log::info($results);
        return response()->json($results);
    }
}
