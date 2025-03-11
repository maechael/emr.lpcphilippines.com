<?php

namespace App\Http\Controllers;

use App\Models\LabImaging;
use App\Models\Metadata;
use App\Models\PatientAuditLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LabImagingController extends Controller
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
            $file = $request->file('lab_result_file');
            $data = $request->all();
            $basename = $file->getClientOriginalName();
            $directoryPath = public_path('backend/assets/lab_imaging');
            $type = $file->getClientMimeType();
            $size = $file->getSize();
            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }
            $file->move($directoryPath, $basename);
            $filepath = 'backend/assets/lab_imaging/' . $basename;

            $metadata = new Metadata();
            $metadata->basename = $basename;
            $metadata->filename = $basename;
            $metadata->filepath = $filepath;
            $metadata->type = $type;
            $metadata->size = $size;
            $metadata->save();

            $data['metadata_id'] = $metadata->id;
            $data['description'] = nl2br($data['description']);

            $labImaging = new LabImaging();
            $labImaging->fill($data);
            $labImaging->save();


            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $labImaging->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Uploaded Lab Imaging';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $labImaging,
                'message' => "Lab Imagin Successfully Uploaded"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving lab imaging' . $e);
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

        try {
            DB::beginTransaction();
            $labImaging = LabImaging::find($id);
            $labImaging->delete();

            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $labImaging->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Deleted Lab Imaging ' . ' ' . $labImaging->type;
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Lab Imaging Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLabImagingTable(Request $request)
    {
        if ($request->ajax()) {
            $query = LabImaging::orderBy('created_at', 'desc');

            if ($request->has('patient_profile_id')) {
                $query->where('patient_profile_id', $request->patient_profile_id);
            }

            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('file', function ($data) {
                    $baseUrl = url('/');
                    $media = $data->metadata;
                    if ($media) {
                        $fullPath = $baseUrl . '/' . $media->filepath;
                        return '<a href="' . $fullPath . '" target="_blank">' . $media->basename . '</a>';
                    }
                    return '';
                })
                ->addColumn('date_uploaded', function ($data) {
                    return Carbon::parse($data->date_tested)->format('M/d/Y h:i A');
                })
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                   
                        <li><button class="dropdown-item deleteLabImaging" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                    </ul>
                 </div>';
                    return $dropdown;
                })
                ->rawColumns(['action', 'file'])
                ->make(true);
        }
    }
}
