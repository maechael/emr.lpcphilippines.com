<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FluidIntake extends Model
{
    use HasFactory;

    protected $table = 'fluid_intake';

    protected $fillable = [
        'patient_profile_id',
        'time_date',
        'type_of_fluid',
        'amount'
    ];

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }
}
