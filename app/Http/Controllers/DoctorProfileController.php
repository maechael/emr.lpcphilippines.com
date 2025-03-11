<?php

namespace App\Http\Controllers;

use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use App\Models\Specialization;
use App\Models\User;
use App\Models\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DoctorProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $doctorSchedule = new DoctorSchedule();
        $dayOfWeekOptions = $doctorSchedule->getDayOfWeekOptions();
        $specializations = Specialization::orderBy('name')->get();
        $users = UserProfile::whereHas('user', function ($query) {
            $query->where('role_id', 7);
        })->whereDoesntHave('doctorProfile')->get();

        return view('doctor-list.index', compact('dayOfWeekOptions', 'specializations', 'users'));
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
            $doctorProfile = new DoctorProfile();
            $doctorProfile->fill($data);
            $doctorProfile->save();
            foreach ($data['day_of_week'] as $index => $day) {
                DoctorSchedule::create([
                    'doctor_profile_id' => $doctorProfile->id, // Ensure you pass doctor_id
                    'day_of_week' => $day,
                    'start_time' => $data['start_time'][$index],
                    'end_time' => $data['end_time'][$index],
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $doctorProfile,
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
            $doctorProfile = DoctorProfile::with('doctorSchedule')->find($id);
            return response()->json([
                'status' => 'success',
                'data' => $doctorProfile,
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

            $doctorProfile = DoctorProfile::findOrFail($id);
            $doctorProfile->fill($data);
            $doctorProfile->save();

            DoctorSchedule::where('doctor_profile_id', $id)->delete();

            foreach ($data['day_of_week'] as $index => $day) {
                DoctorSchedule::create([
                    'doctor_profile_id' => $doctorProfile->id,
                    'day_of_week' => $day,
                    'start_time' => $data['start_time'][$index],
                    'end_time' => $data['end_time'][$index],
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $doctorProfile,
                'message' => "Doctor Profile Successfully Updated"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating doctor profile: ' . $e->getMessage());
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

    public function getDoctorProfileList(Request $request)
    {
        if ($request->ajax()) {
            $data = DoctorProfile::orderBy('created_at', 'desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('specialization', function ($data) {
                    return $data->specialization->name;
                })
                ->addColumn('schedule', function ($data) {
                    $schedules = $data->doctorSchedule; // Eager load this relationship
                    $scheduleList = '';
                    foreach ($schedules as $schedule) {
                        $startTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('g:i A');
                        $endTime = \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('g:i A');

                        $scheduleList .= $schedule->day_of_week . ' (' . $startTime . ' - ' . $endTime . ')<br>';
                    }

                    return $scheduleList;
                })
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-line"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item assignUserButton" id="' . $data->id . '"><i class="ri-user-settings-line"></i>Acc Setting</button></li>
                    <li><button class="dropdown-item editButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                    
                </ul>
             </div>';
                    return $dropdown;
                })
                ->rawColumns(['action', 'schedule'])
                ->make(true);
        }
    }

    public function getAvailableDoctor(Request $request)
    {
        $data = $request->all();
        $doctorProfile = new DoctorProfile();
        $availableDoctor = $doctorProfile->getAvailableDoctorBySpecializationDate($data['specializationId'], $data['availabilityDate']);
        return response()->json([
            'data' => $availableDoctor,
            'message' => 'available doctor successfully returned'
        ]);
    }

    public function assignUserProfileToDoctor(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();
            $doctorProfile = DoctorProfile::find($data['doctor_profile_id']);
            $doctorProfile->user_profile_id = $data['user_profile_id'];
            $doctorProfile->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $doctorProfile,
                'message' => "Doctor Profile Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on assigning user profile to doctor' . $e);
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
