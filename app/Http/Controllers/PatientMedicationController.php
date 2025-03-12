<?php

namespace App\Http\Controllers;

use App\Models\MedicationSchedule;
use App\Models\PatientMedication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PatientMedicationController extends Controller
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
    // public function store(Request $request)
    // {
    //     //
    //     try {
    //         DB::beginTransaction();
    //         $data = $request->all();
    //         // Set end_date to null if not provided
    //         $data['end_date'] = $data['end_date'] ?? null;
    //         // Create PatientMedication record
    //         $patientMedication = PatientMedication::create($data);
    //         foreach ($data['day_of_week'] as $index => $day) {
    //             // If "Everyday" is selected, save all seven days
    //             MedicationSchedule::create([
    //                 'patient_medication_id' => $patientMedication->id,
    //                 'day_of_week' => $day,
    //                 'time' => $data['start_time'][$index],
    //             ]);
    //         }
    //         DB::commit();
    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $patientMedication,
    //             'message' => "Patient Medication Successfully Added"
    //         ], 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Error on saving patient medication: ' . $e->getMessage());

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->all();

            // Set end_date to null if not provided
            $data['end_date'] = $data['end_date'] ?? null;

            // Create PatientMedication record
            $patientMedication = PatientMedication::create($data);

            // Create MedicationSchedule record
            MedicationSchedule::create([
                'patient_medication_id' => $patientMedication->id,
                'interval_hours' => $data['interval_hours'], // Every 12, 24 hours, etc.
                'next_dose_time' => $data['next_dose_time'], // Initial timestamp of next dose
                'status' => 'pending-task',
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'data' => $patientMedication,
                'message' => "Patient Medication Successfully Added"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error on saving patient medication: ' . $e->getMessage());

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
            $patientMedication = PatientMedication::with('medicationSchedule')->find($id);
            return response()->json([
                'status' => 'success',
                'data' => $patientMedication,
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

            // Find the existing PatientMedication record
            $patientMedication = PatientMedication::findOrFail($id);

            // Set end_date to null if not provided
            $data['end_date'] = $data['end_date'] ?? null;

            // Update the main record
            $patientMedication->update($data);

            // Remove old schedules
            MedicationSchedule::where('patient_medication_id', $id)->delete();

            // Insert new schedules
            foreach ($data['day_of_week'] as $index => $day) {
                MedicationSchedule::create([
                    'patient_medication_id' => $id,
                    'day_of_week' => $day,
                    'time' => $data['start_time'][$index],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => $patientMedication,
                'message' => "Patient Medication Successfully Updated"
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating patient medication: ' . $e->getMessage());

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

            $patientMedications = PatientMedication::find($id);
            $patientMedications->medicationSchedule()->delete();
            $patientMedications->delete();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "Patien Medication Successfully Deleted"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getPatientMedicationTable(Request $request)
    {
        if ($request->ajax()) {
            $query = PatientMedication::orderBy('created_at', 'desc');

            if ($request->has('patient_profile_id')) {
                $query->where('patient_profile_id', $request->patient_profile_id);
            }

            $data = $query->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('end_date', function ($data) {
                    $endDate = $data->end_date === null ? 'No End Date' : $data->end_date;
                    return $endDate;
                })
                ->addColumn('next_dose_time', function ($data) {

                    return optional($data->medicationSchedule)->next_dose_time
                        ? \Carbon\Carbon::parse($data->medicationSchedule->next_dose_time)->format('Y-m-d h:i A')
                        : 'N/A';
                })
                ->addColumn('interval_schedule', function ($data) {

                    return $data->medicationSchedule->interval_hours ?? ' ';
                })
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
                ->rawColumns(['action', 'medication_schedule'])
                ->make(true);
        }
    }
}
