<?php

namespace App\Http\Controllers;

use App\Models\PatientProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            $data['is_pwd'] = $data['is_pwd'] == 'on' ? 1 : 0;
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

    public function getPatientList(Request $request)
    {
        if ($request->ajax()) {
            $data = PatientProfile::orderBy('created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('full_name', function ($data) {
                    return $data->firstname . ' ' . $data->lastname;
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
                    <li><button class="dropdown-item viewButton" id="' . $data->id . '"><i class="ri-upload-2-line"></i>Upload</button></li>
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
}
