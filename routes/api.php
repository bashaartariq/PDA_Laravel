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
use Database\Seeders\SpecialitySeeder;
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
    Route::get('/getCategory', [Category::class, 'getCategory']);
    Route::get('/getPurposeOfVisit', [PurposeOfVisitController::class, 'getPurposeOfVisit']);
    Route::get('/getCaseType', [CaseType::class, 'getCaseType']);
    Route::post('/addCase', [CaseController::class, 'addCase']);
    Route::get('/getCase/{PID}', [CaseController::class, 'getCase']);
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
    Route::get('/Case/{patientId}', [Admin::class, 'getCases']);
    Route::get('/DoctorAppointments/{DoctorId}', [Admin::class, 'getAppointmentForDoctor']);
    Route::delete("/doctor/{id}", [Admin::class, 'deleteDoctor']);
    Route::delete('/Case/{CaseIds}', [Admin::class, 'deleteCases']);
    Route::delete('Appointment/{appointmentIds}', [Admin::class, 'deleteAppointment']);
    Route::get('/getRoles', [roles::class, 'getRoles']);
    Route::get('/Gender', [Gender::class, 'getGender']);
    Route::post('/PDF', [Admin::class, 'getPDF']);
    Route::get('/SearchCases/{type}/{term}/{patientId}', [CaseController::class, 'searchCases']);
    Route::get('/SearchAppointment/{type}/{term}/{caseId}', [AppointmentController::class, 'searchAppointment']);
    Route::get('/SearchDoctorAppointment/{type}/{term}/{userId}', [DoctorController::class, 'searchDoctorAppointment']);
    Route::get('/SearchPatients/{type}/{term}', [PatientController::class, 'searchPatient']);
    Route::get('/SearchDoctor/{type}/{term}', [DoctorController::class, 'searchDoctor']);
    Route::get('/SearchAppointmentsForDoctor/{type}/{term}/{doctorId}', [DoctorController::class, 'searchDoctorAppointments']);

    //Speciality
    Route::post('/speciality', [DoctorSpeciality::class, 'createSpeciality']);
    Route::get('/getSpeciality', [DoctorSpeciality::class, 'getSpeciality']);
    Route::put('/speciality/{id}', [DoctorSpeciality::class, 'updateSpeciality']);
    Route::delete('/speciality/{id}', [DoctorSpeciality::class, 'deleteSpeciality']);

    //Practice Location
    Route::post('/practiceLocation', [PracticeLocationController::class, 'createPracticeLocation']);
    Route::get('/getpracticelocation', [PracticeLocationController::class, 'getPracticeLocation']);
    Route::put('/practiceLocation/{id}', [PracticeLocationController::class, 'updatePracticeLocation']);
    Route::delete('/practiceLocation/{id}', [PracticeLocationController::class, 'deletePracticeLocation']);

    //Insurance 
    Route::post('/insurance', [FirmController::class, 'createInsurance']);
    Route::get('/getInsurance', [FirmController::class, 'getInsurance']);

    Route::put('/insurance/{id}', [FirmController::class, 'updateInsurance']);
    Route::delete('/insurance/{id}', [FirmController::class, 'deleteInsurance']);

    //Firm
    Route::post('/firm', [FirmController::class, 'createFirm']);
    Route::get('/getFirm', [FirmController::class, 'getFirm']);
    Route::put('/firm/{id}', [FirmController::class, 'updateFirm']);
    Route::delete('/firm/{id}', [FirmController::class, 'deleteFirm']);
});
Route::get('/generate-pdf/{DoctorId}', [Admin::class, 'generatePdf']);
