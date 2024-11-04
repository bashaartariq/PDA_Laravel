<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Appointment_types;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CaseController;
use App\Http\Controllers\CaseType;
use App\Http\Controllers\Category;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorSpeciality;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\Gender;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PracticeLocationController;
use App\Http\Controllers\PurposeOfVisitController;
use App\Http\Controllers\roles;
use App\Http\Controllers\statecityzip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('checkApiKey')->group(function () {
Route::get('/getstates', [PatientController::class, 'getStates']);
Route::get('/getcities/{stateId}', [PatientController::class, 'getCity']);
Route::post('/addPatientInfo', [PatientController::class, 'addPatientInfo']);
Route::get('/getstates', [statecityzip::class, 'getStates']);
Route::get('/getcity/{state}', [statecityzip::class, 'getCity']);
Route::get('/getpracticelocation', [PracticeLocationController::class, 'getPracticeLocation']);
Route::get('/getCategory', [Category::class, 'getCategory']);
Route::get('/getPurposeOfVisit', [PurposeOfVisitController::class, 'getPurposeOfVisit']);
Route::get('/getCaseType', [CaseType::class, 'getCaseType']);
Route::get('/getFirm', [FirmController::class, 'getFirm']);
Route::get('/getInsurance', [FirmController::class, 'getInsurance']);
Route::post('/addCase', [CaseController::class, 'addCase']);
Route::get('/getCase/{PID}', [CaseController::class, 'getCase']);
Route::get('/getSpeciality', [DoctorSpeciality::class, 'getSpeciality']);
Route::post('/createDoctor', [DoctorController::class, 'addDoctor']);
Route::get('/getAppointmentType', [Appointment_types::class, 'getAppointmentTypes']);
Route::get('/getDoctor/{practiceLocationId}/{specialityId}', [DoctorController::class, 'getDoctor']);
Route::post('/addAppointment', [AppointmentController::class, 'addAppointment']);
Route::get('/getPatientInfo/{userid}', [PatientController::class, 'getPatientInfo']);
Route::get('/getCaseAppointment/{PID}', [AppointmentController::class, 'getCaseWithAppointments']);
Route::get('/getAppointment/{caseid}', [AppointmentController::class, 'getAppointment']);
Route::get('/getAppointmentsForDoctor/{DoctorId}', [DoctorController::class, 'getAppointmentForDoctor']);
Route::put('/updateCase/{caseId}', [CaseController::class, 'updateCase']);
Route::put('/updateAppointment/{appointmentId}', [AppointmentController::class, 'updateAppointment']);
Route::get('/getAppointmentCase/{appointmentId}', [DoctorController::class, 'getAppointmentCase']);
Route::get('/doctorAndPatientCount', [Admin::class, 'getDoctorPatientCount']);
Route::get('/PatientAllInfo', [PatientController::class, 'getAllPatients']);
Route::delete('/Patient/{patientIds}', [Admin::class, 'deletePatient']);
Route::get('/allDoctors', [Admin::class, 'allDoctors']);
Route::get('/Case/{patientId}',[Admin::class,'getCases']);
Route::get('/DoctorAppointments/{DoctorId}', [Admin::class, 'getAppointmentForDoctor']);
Route::post('/PDF', [Admin::class, 'getPDF']);
Route::get('/getRoles', [roles::class, 'getRoles']);
Route::get('/Gender', [Gender::class, 'getGender']);
});