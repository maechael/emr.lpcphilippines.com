<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfileNote extends Model
{
    use HasFactory;

    protected $table = "patient_profile_note";

    protected $fillable = [
        'patient_profile_id',
        'sent_by',
        'description',
        'status',
        'address',
        'is_pwd',
        'pwd_number',

    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'sent_by');
    }
}
