<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicationSchedule extends Model
{
    use HasFactory;

    protected $table = "medication_schedule";

    protected $fillable = [
        'patient_medication_id',
        'interval_hours',
        'next_dose_time',
        'status'
    ];

    public function patientMedication()
    {
        return $this->belongsTo(PatientMedication::class, 'patient_medication_id');
    }
}
