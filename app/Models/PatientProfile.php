<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientProfile extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "patient_profile";

    protected $fillable = [
        'firstname',
        'lastname',
        'birthdate',
        'contact_number',
        'address',
        'is_pwd',
        'pwd_number',

    ];

    public function vitalSign()
    {
        return $this->hasMany(VitalSign::class, 'patient_profile_id');
    }

    public function patientAppointment()
    {
        return $this->hasMany(PatientAppointment::class, 'patient_profile_id');
    }

    public function labResults()
    {
        return $this->hasMany(LabResults::class, 'patient_profile_id');
    }

    public function medicalRecord()
    {
        return $this->hasMany(MedicalRecord::class, 'patient_profile_id');
    }

    public function patientAuditLog()
    {
        return $this->hasMany(PatientAuditLogs::class, 'patient_id');
    }
}
