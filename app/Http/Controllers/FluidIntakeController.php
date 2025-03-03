<?php

namespace App\Http\Controllers;

use App\Models\FluidIntake;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class FluidIntakeController extends Controller
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
            $fluidIntake = new FluidIntake();
            $fluidIntake->fill($data);
            $fluidIntake->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $fluidIntake,
                'message' => "fluid intake Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving fluid intake' . $e);
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
            $fluidIntake = FluidIntake::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $fluidIntake,
                'message' => "Fluid Intake Successfully Retrieved"
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
            $fluidIntake = FluidIntake::find($id);
            $fluidIntake->fill($data);
            $fluidIntake->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $fluidIntake,
                'message' => "fluid intake Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving fluid intake' . $e);
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

            $fluidIntake = FluidIntake::find($id);
            $fluidIntake->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Fluid Intake Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPatientFluidIntakeTable(Request $request)
    {
        if ($request->ajax()) {
            $query = FluidIntake::orderBy('created_at', 'desc');

            if ($request->has('patient_profile_id')) {
                $query->where('patient_profile_id', $request->patient_profile_id);
            }

            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('date', function ($data) {
                    return \Carbon\Carbon::parse($data->time_date)->format('Y-m-d'); // Extract only the date
                })
                ->addColumn('time', function ($data) {
                    return \Carbon\Carbon::parse($data->time_date)->format('h:i A'); // Extract only the time in 12-hour format
                })

                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="ri-more-line"></i>
                </button>
                <ul class="dropdown-menu">
                
                    <li><button class="dropdown-item editFluidIntake" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                   <li><button class="dropdown-item deleteFluidIntake" id="' . $data->id . '"><i class="ri-delete-bin-line"></i>Delete</button></li>
                   
                </ul>
             </div>';
                    return $dropdown;
                })
                ->rawColumns(['action', 'schedule'])
                ->make(true);
        }
    }
}
