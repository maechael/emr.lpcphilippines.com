<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\LabResultType;
use App\Models\MedicationSchedule;
use App\Models\PatientAuditLogs;
use App\Models\PatientProfile;
use App\Models\PatientProfileNote;
use App\Models\Specialization;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PatientListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('patient-list.index');
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
            $patientProfile = new PatientProfile();
            if (isset($data['is_pwd'])) {
                $data['is_pwd'] = $data['is_pwd'] == 'on' ? 1 : 0;
            } else {
                $data['is_pwd'] =  0;
            }
            $patientProfile->fill($data);
            $patientProfile->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $patientProfile,
                'message' => "Patient Profile Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving patient profile' . $e);
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
        $patientProfile = PatientProfile::find($id);
        $patientProfile['age'] = Carbon::parse($patientProfile->birthdate)->age;
        $specializations = Specialization::orderBy('name')->get();
        $logs = $patientProfile->patientAuditLog()->orderBy('created_at')->get();
        $labTestTypes = LabResultType::orderBy('name')->get();
        $medicationSchedule = new MedicationSchedule();
        $dayOfWeekOptions = $medicationSchedule->getDayOfWeekOptions();
        $userProfileId = Auth::user()->userProfile->id;
        $users = UserProfile::where('position', 'Patient')
            ->whereDoesntHave('patientProfile')
            ->get();
        $patientNotes = PatientProfileNote::where('patient_profile_id', $patientProfile->id)->orderBy('created_at')->get();
        return view('patient-list.patient-profile-dashboard', compact('patientProfile', 'specializations', 'logs', 'labTestTypes', 'dayOfWeekOptions', 'patientNotes', 'userProfileId', 'users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $patientProfile = PatientProfile::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $patientProfile,
                'message' => "Patient Profile Successfully Retrieved"
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
        try {
            DB::beginTransaction();
            $data = $request->all();

            $patientProfile = PatientProfile::find($id);
            if (isset($data['is_pwd'])) {
                $data['is_pwd'] = $data['is_pwd'] == 'on' ? 1 : 0;
            } else {
                $data['is_pwd'] =  0;
            }
            $patientProfile->fill($data);

            $patientProfile->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $patientProfile,
                'message' => "Patient Profile Successfully Updated"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on updating patient profile' . $e);
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
        try {
            DB::beginTransaction();

            $patientProfile = PatientProfile::find($id);
            $patientProfile->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Patient Profile Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPatientList(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();
            if ($user->role_id == 7) {
                $patientProfileIds = $user->userProfile->doctorProfile->patientAppointment->pluck('patient_profile_id');
                $data = PatientProfile::whereIn('id', $patientProfileIds)->orderBy('created_at');
            } else {
                $data = PatientProfile::orderBy('created_at');
            }
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($data) {
                    return $data->firstname . ' ' . $data->lastname;
                })
                ->addColumn('firstname', function ($data) {
                    $age = Carbon::parse($data->birthdate)->age;

                    if ($data->is_pwd && $age >= 65) {
                        $firstname = $data->firstname . ' (senior)';
                    } elseif ($data->is_pwd) {
                        $firstname = $data->firstname . ' (pwd)';
                    } else {
                        $firstname = $data->firstname;
                    }

                    return $firstname;
                })

                ->addColumn('age', function ($data) {
                    return Carbon::parse($data->birthdate)->age;
                })
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                    <li><button class="dropdown-item viewButton" id="' . $data->id . '"><i class="ri-eye-line"></i>View</button></li>
                        <li><button class="dropdown-item editButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                        <li><button class="dropdown-item deleteButton" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                    </ul>
                 </div>';
                    return $dropdown;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function assignUserProfileToPatient(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $patientProfile = PatientProfile::find($data['patient_profile_id']);
            $patientProfile->user_profile_id = $data['user_profile_id'];
            $patientProfile->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $patientProfile,
                'message' => "User Profile Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on assigning user profile' . $e);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function loadTabContent(Request $request)
    {
        $patientProfile = PatientProfile::findOrFail($request->patient_id);
        $patientProfile['age'] = Carbon::parse($patientProfile->birthdate)->age;
        $specializations = Specialization::orderBy('name')->get();
        $logs = $patientProfile->patientAuditLog()->orderBy('created_at', 'desc')->get();
        $labTestTypes = LabResultType::orderBy('name')->get();
        $medicationSchedule = new MedicationSchedule();
        $dayOfWeekOptions = $medicationSchedule->getDayOfWeekOptions();
        $userProfileId = Auth::user()->userProfile->id;
        $users = UserProfile::where('position', 'Patient')
            ->whereDoesntHave('patientProfile')
            ->get();
        $patientNotes = PatientProfileNote::where('patient_profile_id', $patientProfile->id)->orderBy('created_at')->get();
        switch ($request->tab) {
            case 'appointment':
                return view('appointment.index', compact('specializations', 'patientProfile'))->render();
            case 'labResults':
                return view('lab-results.index', compact('patientProfile', 'labTestTypes'))->render();
            case 'medicalRecords':
                return view('medical-records.index', compact('patientProfile'))->render();
            case 'medication':
                return view('patient-medication.index', compact('patientProfile', 'dayOfWeekOptions'))->render();
            case 'imaging':
                return view('lab-results.lab-imaging', compact('patientProfile', 'labTestTypes'))->render();
            case 'fluidMonitoring':
                return view('patient-fluid-monitoring.patient-fluid-intake', compact('patientProfile'))->render() .
                    view('patient-fluid-monitoring.patient-fluid-output', compact('patientProfile'))->render();
            case 'activityLogs':
                return view('patient-list.activity-logs', compact('patientProfile', 'logs', 'patientNotes', 'userProfileId'))->render();
            case 'otherDocuments':
                return view('other-document.index', compact('patientProfile'))->render();
            default:
                return response()->json(['error' => 'Invalid tab'], 400);
        }
    }
}
