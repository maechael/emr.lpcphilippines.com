<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientMedication extends Model
{
    use HasFactory;

    protected $table = "patient_medications";

    protected $fillable = [
        'patient_profile_id',
        'medication_name',
        'dosage',
        'start_date',
        'end_date'
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function medicationSchedule()
    {
        return $this->hasOne(MedicationSchedule::class, 'patient_medication_id');
    }
}
