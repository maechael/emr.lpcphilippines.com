<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;
    protected $table = 'user_profiles';

    protected $fillable = [
        'id',
        'firstname',
        'lastname',
        'id_num',
        'user_id',
        'media_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function media()
    {
        return $this->belongsTo(Metadata::class, 'media_id');
    }

    public function patientAuditProfileLog()
    {
        return $this->hasMany(PatientAuditLogs::class, 'user_profile_id');
    }

    public function patientProfileNote()
    {
        return $this->hasOne(PatientProfileNote::class, 'sent_by');
    }

    public function patientProfile()
    {
        return $this->hasOne(PatientProfile::class, 'user_profile_id');
    }

    public function doctorProfile()
    {
        return $this->hasOne(doctorProfile::class, 'user_profile_id');
    }
}
