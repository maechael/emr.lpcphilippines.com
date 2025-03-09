<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabImaging extends Model
{
    use HasFactory;

    protected $table = 'lab_imaging';

    protected $fillable = [
        'patient_profile_id',
        'metadata_id',
        'type',
        'description',
        'date_tested'
    ];

    public function metadata()
    {
        return $this->belongsTo(Metadata::class, 'metadata_id');
    }

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }
}
