<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoomConsultation extends Model
{
    use HasFactory;
    protected $table = 'zoom_consultation';

    protected $fillable = [
        'patient_appointment_id',
        'link',
        'date_time',
    ];

    public function patientAppointment()
    {
        return $this->belongsTo(PatientAppointment::class, 'patient_appointment_id');
    }
}
