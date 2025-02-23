<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\MedicalRecord;
use App\Models\PatientAuditLogs;
use App\Models\PatientProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class MedicalRecordController extends Controller
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
            $medicalRecord = new MedicalRecord();

            $data['chief_complaint'] = nl2br($data['chief_complaint']);
            $data['assesment'] = nl2br($data['assesment']);
            $data['treatment_plan'] = nl2br($data['treatment_plan']);
            $data['notes'] = nl2br($data['notes']);

            $medicalRecord->fill($data);
            $medicalRecord->save();


            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $medicalRecord->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Logs Medical Record';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $medicalRecord,
                'message' => "Medical Assesment Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Saving Medical Assesment: ' . $e->getMessage());
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
        $patientProfile = PatientProfile::find($id);
        $doctorList = DoctorProfile::orderBy('lastname')->get();
        return view('medical-records.medical-assesment-form', compact('doctorList', 'patientProfile'));
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
        $medicalRecord = MedicalRecord::find($id);
        $doctorList = DoctorProfile::orderBy('lastname')->get();
        $patientProfile = $medicalRecord->patientProfile;
        return view('medical-records.update-medical-assesment-form', compact('doctorList', 'medicalRecord', 'patientProfile'));
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

        try {
            DB::beginTransaction();
            $data = $request->all();
            $medicalRecord = MedicalRecord::find($id);

            $data['chief_complaint'] = nl2br($data['chief_complaint']);
            $data['assesment'] = nl2br($data['assesment']);
            $data['treatment_plan'] = nl2br($data['treatment_plan']);
            $data['notes'] = nl2br($data['notes']);

            $medicalRecord->fill($data);
            $medicalRecord->save();

            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $medicalRecord->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Updated Medical Record';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $medicalRecord,
                'message' => "Medical Assesment Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error Saving Medical Assesment: ' . $e->getMessage());
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


    public function getLatestMedicalAssesment($id)
    {

        try {
            $patientProfile = PatientProfile::find($id);
            $medicalRecord = $patientProfile->medicalRecord()->with('doctorProfile')->latest()->first();
            return response()->json([
                'status' => 'success',
                'data' => $medicalRecord,
                'message' => "Medical Successfully Retrieved"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getMedicalAssesmentTable(Request $request)
    {
        $patientProfile = PatientProfile::find($request['id']);
        $medicalRecords = $patientProfile->medicalRecord()->orderBy('created_at', 'desc')->get();
        return DataTables::of($medicalRecords)
            ->addIndexColumn()
            ->addColumn('date_log', function ($data) {
                return Carbon::parse($data->created_at)->format('M d Y');;
            })
            ->addColumn('checked_by', function ($data) {
                $doctorFullName = 'Dr.' . ' ' . $data->doctorProfile->firstname . ' ' . $data->doctorProfile->lastname;
                return $doctorFullName;
            })
            ->addColumn('action', function ($data) {
                $dropdown = '<div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-line"></i>
                </button>
                <ul class="dropdown-menu">
              
                    <li><button class="dropdown-item editMedicalAssesmentButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                    <li><button class="dropdown-item deleteButton" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                </ul>
             </div>';
                return $dropdown;
            })
            ->make(true);
    }
}
