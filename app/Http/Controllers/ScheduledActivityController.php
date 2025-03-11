<?php

namespace App\Http\Controllers;

use App\Kanban\ScheduledActivityKanban;
use App\Models\DoctorProfile;
use App\Models\PatientAppointment;
use App\Models\PatientProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduledActivityController extends Controller
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

    public function getScheduledActivity()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if ($user->role_id == 7) {

                $patientAppointments = PatientAppointment::where('doctor_profile_id', $user->userProfile->doctorProfile->id)->orderBy('appointment_date')->get();
            } else {
                $patientAppointments = PatientAppointment::orderBy('appointment_date')->get();
            }

            $data = [];

            foreach ($patientAppointments as $patientAppointment) {
                $patientProfile =  PatientProfile::find($patientAppointment->patient_profile_id);
                $doctorProfile = DoctorProfile::find($patientAppointment->doctor_profile_id);
                $patientFullName = $patientProfile->firstname . ' ' . $patientProfile->lastname;
                $doctorFullName =  $doctorProfile->firstname . ' ' . $doctorProfile->lastname;
                $appointmentTime = date('h:i A', strtotime($patientAppointment->appointment_date)); // Format time
                $data[] = [
                    'id' => $patientAppointment->id,
                    'name' => 'Appointment',
                    'date' => $patientAppointment->appointment_date,
                    'type' => 'appointmemnt',
                    'description' => "Patient " . $patientFullName . " has an appointment with Dr. " . $doctorFullName . " at " . $appointmentTime . ".",
                    'color' => '#28a745' // Green for callbacks
                ];
            }

            DB::commit();
            return response()->json([
                'message' => 'Activity log retrieved successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error On Fetching Scheduled Activity: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function scheduledActivityKanban()
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            if ($user->role_id == 7) {

                $patientAppointments = PatientAppointment::where('doctor_profile_id', $user->userProfile->doctorProfile->id)->orderBy('appointment_date')->get();
            } else {
                $patientAppointments = PatientAppointment::orderBy('appointment_date')->get();
            }
            $data = [];

            foreach ($patientAppointments as $patientAppointment) {
                $patientProfile =  PatientProfile::find($patientAppointment->patient_profile_id);
                $doctorProfile = DoctorProfile::find($patientAppointment->doctor_profile_id);
                $patientFullName = $patientProfile->firstname . ' ' . $patientProfile->lastname;
                $doctorFullName =  $doctorProfile->firstname . ' ' . $doctorProfile->lastname;

                $data[] = [
                    'id' => $patientAppointment->id,
                    'name' => 'Appointment',
                    'date' => $patientAppointment->appointment_date,
                    'type' => 'appointmemnt',
                    'description' => "Patient" . ' ' . $patientFullName . ' ' . 'Appointment To Dr.' . $doctorFullName,
                    'color' => '#28a745', // Green for callbacks
                    'status' => $patientAppointment->status,
                ];
            }

            DB::commit();
            return response()->json([
                'message' => 'Activity log retrieved successfully',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error On Fetching Scheduled Activity: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateTaskKanban(Request $request)
    {
        try {
            DB::beginTransaction();
            $patientAppointment = PatientAppointment::find($request->id);
            $patientAppointment->status = $request->status;
            $patientAppointment->save();
            DB::commit();
            return response()->json([
                'message' => 'Status Updated',
                'data' => $patientAppointment
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error On Updating Status : ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
