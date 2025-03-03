<?php

namespace App\Http\Controllers;

use App\Models\LabResultType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class LabResultTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('lab-result-type.index');
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
            $labResultType = new LabResultType();
            $labResultType->fill($data);
            $labResultType->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $labResultType,
                'message' => "lab result type Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving lab result type' . $e);
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
            $labResultType = LabResultType::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $labResultType,
                'message' => "lab test type Successfully Retrieved"
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
            $labResultType = LabResultType::find($id);
            $labResultType->fill($data);
            $labResultType->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $labResultType,
                'message' => "lab result type Successfully Updated"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on updating lab result type' . $e);
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

            $LabResultType = LabResultType::find($id);
            $LabResultType->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Lab Result type Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getLabResultTypeTable(Request $request)
    {
        if ($request->ajax()) {
            $data = LabResultType::orderBy('created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><button class="dropdown-item editButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                        <li><button class="dropdown-item deleteButton" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                    </ul>
                 </div>';
                    return $dropdown;
                })
                ->addColumn('formatted_created_at', function ($data) {
                    return $data->created_at->format('Y-m-d H:i:s'); // Customize format as needed
                })
                ->addColumn('formatted_updated_at', function ($data) {
                    return $data->updated_at->format('Y-m-d H:i:s'); // Customize format as needed
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
