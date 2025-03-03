<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluidOutput extends Model
{
    use HasFactory;

    protected $table = 'fluid_output';

    protected $fillable = [
        'patient_profile_id',
        'time_date',
        'amount'
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }
}
