<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class SpecializationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('specialization.index');
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
        try {
            DB::beginTransaction();
            $data = $request->all();
            $specialization = new Specialization();
            $specialization->fill($data);
            $specialization->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $specialization,
                'message' => "Specialization Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on saving specialization' . $e);
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
            $specialization = Specialization::find($id);
            return response()->json([
                'status' => 'success',
                'data' => $specialization,
                'message' => "Specialization Successfully Retrieved"
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
            $specialization = Specialization::find($id);
            $specialization->fill($data);
            $specialization->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $specialization,
                'message' => "Specialization Successfully Updated"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::info('error on updating specialization' . $e);
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

    public function getSpecializationTable(Request $request)
    {
        if ($request->ajax()) {
            $data = Specialization::orderBy('created_at');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $dropdown = '<div class="btn-group">
                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ri-more-line"></i>
                    </button>
                    <ul class="dropdown-menu">
                   
                        <li><button class="dropdown-item editButton" id="' . $data->id . '"><i class="ri-edit-box-line"></i>Edit</button></li>
                    </ul>
                 </div>';
                    return $dropdown;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
