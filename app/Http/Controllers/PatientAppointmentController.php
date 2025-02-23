<?php

namespace App\Http\Controllers;

use App\Models\PatientAppointment;
use App\Models\PatientAuditLogs;
use App\Models\PatientProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PatientAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $data = $request->all();
            $patientAppointment = new PatientAppointment();
            $patientAppointment->fill($data);
            $patientAppointment->save();


            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $patientAppointment->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Patient Set Appointement';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $patientAppointment,
                'message' => "Patient Appointment Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Saving Patient Appointment: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        try {
            $patientAppointment = PatientAppointment::with('patientProfile', 'doctorProfile')->find($id);
            return response()->json([
                'status' => 'success',
                'data' => $patientAppointment,
                'message' => "Patient Appointment Successfully Retrieved"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //

        //
        try {
            DB::beginTransaction();
            $data = $request->all();
            $patientAppointment = PatientAppointment::find($id);
            $patientAppointment->fill($data);
            $patientAppointment->save();

            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $patientAppointment->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Updated Patient Appointement';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $patientAppointment,
                'message' => "Patient Appointment Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Saving Patient Appointment: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getPatientAppointmentProfileTable(Request $request)
    {
        if ($request->ajax()) {
            $data = PatientAppointment::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('doctor_fullname', function ($data) {
                    $doctorFullName = 'Dr.' . ' ' . $data->doctorProfile->lastname . ' ' . $data->doctorProfile->firstname;
                    return $doctorFullName;
                })
                ->addColumn('formatted_appointment_date', function ($data) {
                    $schedule = Carbon::parse($data->appointment_date)->format('M/d/Y H:i A');
                    return $schedule;
                })
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-line"></i>
                </button>
                <ul class="dropdown-menu">
                <li><button class="dropdown-item viewButton" id="' . $data->id . '"><i class="ri-eye-line"></i>View</button></li>
                    <li><button class="dropdown-item editAppointButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                    <li><button class="dropdown-item deleteButton" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                </ul>
             </div>';
                    return $dropdown;
                })
                ->rawColumns(['action', 'schedule'])
                ->make(true);
        }
    }
}
