<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\FluidIntakeController;
use App\Http\Controllers\FluidOutputController;
use App\Http\Controllers\LabImagingController;
use App\Http\Controllers\LabResultController;
use App\Http\Controllers\LabResultsController;
use App\Http\Controllers\LabResultTypeController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\OtherDocumentController;
use App\Http\Controllers\PatientAppointmentController;
use App\Http\Controllers\PatientListController;
use App\Http\Controllers\PatientMedicationController;
use App\Http\Controllers\PatientProfileController;
use App\Http\Controllers\PatientProfileNoteController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ScheduledActivityController;
use App\Http\Controllers\SpecializationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\VitalSignController;
use App\Models\PatientMedication;
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
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Admin Configuration All Route
Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'Profile')->name('admin.profile');
    Route::get('/edit/profile', 'EditProfile')->name('edit.profile');
    Route::post('/store/profile', 'StoreProfile')->name('store.profile');

    Route::get('/change/password', 'ChangePassword')->name('change.password');
    Route::post('/update/password', 'UpdatePassword')->name('update.password');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //patient list routes
    Route::resource('/patient-list', PatientListController::class);
    Route::post('/get-patient-list', [PatientListController::class, 'getPatientList'])->name('get-patient-list');
    Route::post('/assign-user-profile-to-patient', [PatientListController::class, 'assignUserProfileToPatient'])->name('assign-user-profile-to-patient');
    Route::post('/load-tab-content', [PatientListController::class, 'loadTabContent'])->name('tab.load');

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
    Route::post('/assign-user-profile-to-doctor', [DoctorProfileController::class, 'assignUserProfileToDoctor'])->name('assign-user-profile-to-doctor');
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


    //scheduled activity route
    Route::resource('/scheduled-activity', ScheduledActivityController::class);
    Route::get('/get-scheduled-activity', [ScheduledActivityController::class, 'getScheduledActivity'])->name('get-scheduled-activity');
    Route::get('/scheduled-activity-kanban', [ScheduledActivityController::class, 'scheduledActivityKanban'])->name('scheduled-activity-kanban');
    Route::post('/update-task-kanban', [ScheduledActivityController::class, 'updateTaskKanban'])->name('update-task-kanban');

    //lab result type
    Route::resource('/lab-result-type', LabResultTypeController::class);
    Route::post('/get-lab-result-type-table', [LabResultTypeController::class, 'getLabResultTypeTable'])->name('get-lab-result-type-table');


    //patient medication 
    Route::resource('/patient-medication', PatientMedicationController::class);
    Route::post('/get-patient-medication-table', [PatientMedicationController::class, 'getPatientMedicationTable'])->name('get-patient-medication-table');


    //patient fluid intake 
    Route::resource('/fluid-intake', FluidIntakeController::class);
    Route::post('/get-patient-fluid-intake-table', [FluidIntakeController::class, 'getPatientFluidIntakeTable'])->name('get-patient-fluid-intake-table');

    //patient fluid intake 
    Route::resource('/fluid-output', FluidOutputController::class);
    Route::post('/get-patient-fluid-output-table', [FluidOutputController::class, 'getPatientFluidOutputTable'])->name('get-patient-fluid-output-table');


    //lab imaging route
    Route::resource('/lab-imaging', LabImagingController::class);
    Route::post('/get-lab-imaging-table', [LabImagingController::class, 'getLabImagingTable'])->name('get-lab-imaging-table');


    //notes log
    Route::resource('/notes-log', PatientProfileNoteController::class);

    //other document
    Route::resource('/other-document', OtherDocumentController::class);
    Route::post('/get-other-document-table', [OtherDocumentController::class, 'getOtherDocumentTable'])->name('get-other-document-table');

    //admin nav
    Route::prefix('/admin')->name('admin.')->group(function () {
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.permissions');
        Route::resource('/permissions', PermissionController::class)->except(['update']);
        Route::post('/permissions/update', [PermissionController::class, 'update'])->name('permissions.update');
        Route::resource('/roles', RoleController::class);

        Route::resource('/users', UserController::class)->except(['update']);
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');

        Route::resource('/user-profiles', UserProfileController::class)->except('update');
        Route::post('/user-profiles/update', [UserProfileController::class, 'update'])->name('user-profiles.update');
        Route::post('/user-profiles/change-status', [UserProfileController::class, 'changeStatus'])->name('user-profiles.change_status');
    });
});

require __DIR__ . '/auth.php';
