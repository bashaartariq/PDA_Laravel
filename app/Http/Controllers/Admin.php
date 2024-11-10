<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Doctor;
use App\Models\user;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\GeneratePdfjob;
use App\Models\appointment;

class Admin extends Controller
{
    function getDoctorPatientCount(Request $request)
    {
        try {
            $doctorCount = DB::select('SELECT Count(*)as DoctorCount FROM users WHERE role = ? AND deleted_at is NULL', ['doctor']);
            $patientCount = DB::select('SELECT Count(*)as PatientCount FROM users WHERE role = ? AND deleted_at is NULL', ['patient']);
            $data = [
                'doctorCount' => $doctorCount[0]->DoctorCount,
                'patientCount' => $patientCount[0]->PatientCount,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(["message" => "Error while Retriving the count"]);
        }
    }
    function deletePatient(Request $request, $patientIds)
    {
        try {
            $array = explode(",", $patientIds);
            $arrayOfInts = array_map('intval', $array);
            Log::info($arrayOfInts);
            foreach ($arrayOfInts as $userId) {
                DB::transaction(function () use ($userId) {
                    $user = User::with('patient.cases.appointment')->find($userId);
                    $patient = $user->patient;
                    if ($patient) {
                        foreach ($patient->cases as $case) {
                            foreach ($case->appointment as $appointment) {
                                $appointment->delete();
                            }
                            $case->delete();
                        }
                        $patient->delete();
                    }
                    $user->delete();
                    Log::info('User and all related records soft-deleted', [
                        'userId' => $userId,
                        'userEmail' => $user->email,
                    ]);
                });
            }
            return response()->json(['message' => 'Patients soft deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error deleting patients.'], 500);
        }
    }
    function deleteCases(Request $request, $CaseIds)
    {
        try {
            $array = explode(",", $CaseIds);
            $arrayOfInts = array_map('intval', $array);
            Log::info('Case IDs to delete: ', $arrayOfInts);
            DB::transaction(function () use ($arrayOfInts) {
                foreach ($arrayOfInts as $caseId) {
                    $case = Cases::find($caseId);
                    if ($case) {
                        $case->appointment()->delete();
                        $case->delete();
                    } else {
                        Log::warning("Case with ID {$caseId} not found.");
                    }
                }
            });
            return response()->json(['message' => 'Cases and associated appointments soft deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error Deleting Cases and its Appointment.'], 500);
        }
    }
    function deleteAppointment(Request $request, $appointmentIds)
    {
        Log::info("Working");
        try {
            $array = explode(",", $appointmentIds);
            $arrayOfInts = array_map('intval', $array);
            Log::info('Appointment IDs to delete: ', $arrayOfInts);
            foreach ($arrayOfInts as $appointmentId) {
                $appointment = appointment::find($appointmentId);
                $appointment->delete();
            }
            return response()->json(['message' => 'appointments soft deleted successfully.']);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json(['message' => 'Error deleting Appointment.'], 500);
        }
    }
    function allDoctors(Request $request)
    {
        try {
            $doctors = Doctor::with('user', 'speciality', 'practiceLocation')->get();
            Log::info($doctors);

            $data = $doctors->map(function ($doctor) {
                return [
                    'doctor_name' => $doctor->user->firstName . ' ' . ($doctor->user->middleName ?? '') . ' ' . $doctor->user->lastName,
                    'gender' => $doctor->user->gender,
                    'email' => $doctor->user->email,
                    'speciality' => $doctor->speciality->name,
                    'practice_location' => $doctor->practiceLocation->name,
                    'id' => $doctor->id
                ];
            });
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['error' => "Error Found"]);
        }
    }
    function getAppointmentForDoctor(Request $request, $DoctorId)
    {
        Log::info($DoctorId);
        $doctor = Doctor::where('id', $DoctorId)
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
    function getPDF(Request $request)
    {
        Log::info("WOrking");
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $DoctorId = $request->input('DoctorId');
        Log::info($startDate);
        Log::info($endDate);
        Log::info($DoctorId);
        $caseCounts = DB::table('appointments')
            ->select(DB::raw('COUNT(*) AS count, cases.case_type'))
            ->join('cases', 'appointments.case_id', '=', 'cases.id')
            ->whereBetween('appointments.date', [$startDate, $endDate])
            ->where('appointments.doctor_id', $DoctorId)
            ->groupBy('cases.case_type')
            ->get();
        Log::info($caseCounts);
        $doctor = Doctor::where('id', $DoctorId)->first();
        $user = $doctor->user;
        $doctorName = $user['firstName'] . ' ' . $user['middleName'] . ' ' . $user['lastName'];
        Log::info($doctorName);
        $data = ['startDate' => $startDate, 'endDate' => $endDate, 'DoctorId' => $DoctorId, 'caseData' => $caseCounts, 'doctorName' => $doctorName];
        GeneratePdfjob::dispatch($data);
        return response()->json([
            'message' => "Successfully Created the PDF."
        ]);
    }
    function getCases(Request $request, $patientId)
    {
        try {
            $case = Cases::where('PID', $patientId)
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
    function deleteDoctor(Request $request, $id)
    {
        Log::info($id);
        $doctor = Doctor::with('appointment')->find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found.'], 404);
        }
        if ($doctor->appointment()->count() > 0) {
            $doctor->appointment()->delete();
        }
        $doctor->delete();
        return response()->json(['message' => 'Doctor and associated appointments deleted successfully.']);
    }
    function generatePdf(Request $request, $DoctorId)
    {
        $pdfFileName = 'case_report_doctor_' . $DoctorId . '.pdf';
        $files = glob(storage_path('app/public/' . $pdfFileName));
        if (count($files) === 0) {
            return response()->json(['message' => 'PDF not found.'], 404);
        }
        return response()->download($files[0]);
    }
}
