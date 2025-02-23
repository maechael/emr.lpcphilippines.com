<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAuditLogs extends Model
{
    use HasFactory;

    protected $table = 'patient_audit_logs';

    protected $fillable = [
        'id',
        'patient_id',
        'user_profile_id',
        'changes',
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_id');
    }

    public function userProfile()
    {
        return $this->belongsTo(userProfile::class, 'user_profile_id');
    }
}
