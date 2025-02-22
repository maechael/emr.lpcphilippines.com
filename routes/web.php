<?php

use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\LabResultsController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\PatientListController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\VitalSignController;
use App\Models\Specialization;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //patient list routes
    Route::resource('/patient-list', PatientListController::class);
    Route::post('/get-patient-list', [PatientListController::class, 'getPatientList'])->name('get-patient-list');


    //vital signs routes
    Route::resource('/vital-sign', VitalSignController::class);
    Route::get('/get-latest-vital-sign/{id}', [VitalSignController::class, 'getLatestVitalSign'])->name('get-latest-vital-sign');
    Route::post('/get-vital-sign-table', [VitalSignController::class, 'getVitalSignTable'])->name('get-vital-sign-table');

    //specialization routes
    Route::resource('/specialization', SpecializationController::class);
    Route::post('/get-specialization-table', [SpecializationController::class, 'getSpecializationTable'])->name('get-specialization-table');

    //doctor profile routes
    Route::resource('/doctor-profile', DoctorProfileController::class);
    Route::post('/get-doctor-profile-list', [DoctorProfileController::class, 'getDoctorProfileList'])->name('get-doctor-profile-list');
    Route::post('/get-available-doctor', [DoctorProfileController::class, 'getAvailableDoctor'])->name('get-available-doctor');

    //patient appointment 
    Route::resource('/patient-appointment', PatientAppointmentController::class);
    Route::post('/get-patient-appointment-profile-table', [PatientAppointmentController::class, 'getPatientAppointmentProfileTable'])->name('get-patient-appointment-profile-table');


    //lab result
    Route::resource('/lab-results', LabResultsController::class);
    Route::post('/get-lab-results-table', [LabResultsController::class, 'getLabResultsTable'])->name('get-lab-results-table');

    //Medical Record routes
    Route::resource('/medical_record', MedicalRecordController::class);
    Route::post('/get-medical-assesment-table', [MedicalRecordController::class, 'getMedicalAssesmentTable'])->name('get-medical-assesment-table');
    Route::get('/get-latest-medical-assesment/{id}', [MedicalRecordController::class, 'getLatestMedicalAssesment'])->name('get-latest-medical-assesment');



    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.permissions');
        Route::resource('/permissions', PermissionController::class)->except(['update']);
        Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
        Route::resource('/roles', RoleController::class);
    });
});

require __DIR__ . '/auth.php';
