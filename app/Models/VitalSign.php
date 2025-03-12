<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    protected $table = 'vital_sign';

    protected $fillable = [
        'patient_profile_id',
        'blood_pressure',
        'temperature',
        'heart_rate',
        'pulse_rate',
        'weight',
        'height',
        'respiratory_rate'
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }
}
