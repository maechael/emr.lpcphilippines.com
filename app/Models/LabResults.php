<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResults extends Model
{
    use HasFactory;

    protected $table = 'lab_results';

    protected $fillable = [
        'metadata_id',
        'patient_profile_id',
        'type'
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
