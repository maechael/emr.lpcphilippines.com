<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = "medical_record";

    protected $fillable = [
        'patient_profile_id',
        'doctor_profile_id',
        'chief_complaint',
        'assesment',
        'treatment_plan',
        'notes',
    ];

    public function patientProfile()
    {
        return $this->belongsTo(MedicalRecord::class, 'patient_profile_id');
    }

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }
}
