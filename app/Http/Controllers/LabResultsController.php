<?php

namespace App\Http\Controllers;

use App\Models\LabResults;
use App\Models\Metadata;
use App\Models\PatientAuditLogs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class LabResultsController extends Controller
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
            $directoryPath = public_path('backend/assets/lab_result');
            $type = $file->getClientMimeType();
            $size = $file->getSize();
            if (!File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }
            $file->move($directoryPath, $basename);
            $filepath = 'backend/assets/lab_result/' . $basename;

            $metadata = new Metadata();
            $metadata->basename = $basename;
            $metadata->filename = $basename;
            $metadata->filepath = $filepath;
            $metadata->type = $type;
            $metadata->size = $size;
            $metadata->save();

            $data['metadata_id'] = $metadata->id;

            $data['type'] = str_replace(',', ', ', $data['types']); // Ensure space after comma

            $labResult = new LabResults();
            $labResult->fill($data);
            $labResult->save();


            $patientAuditLogs = new PatientAuditLogs();
            $patientAuditLogs->patient_id = $labResult->patient_profile_id;
            $patientAuditLogs->user_profile_id = Auth::user()->userProfile->id;
            $patientAuditLogs->changes = 'Uploaded Lab Results';
            $patientAuditLogs->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $labResult,
                'message' => "Lab Result Successfully Uploaded"
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
        try {
            DB::beginTransaction();

            $patientProfile = LabResults::find($id);
            $patientProfile->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Lab Result Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLabResultsTable(Request $request)
    {
        if ($request->ajax()) {
            $query = LabResults::orderBy('created_at', 'desc');

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
                    return Carbon::parse($data->created_at)->format('M/d/Y h:i A');
                })
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                   
                        <li><button class="dropdown-item deleteFileButton" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                    </ul>
                 </div>';
                    return $dropdown;
                })
                ->rawColumns(['action', 'file'])
                ->make(true);
        }
    }
}
