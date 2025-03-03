<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MedicationSchedule extends Model
{
    use HasFactory;

    protected $table = "medication_schedule";

    protected $fillable = [
        'patient_medication_id',
        'day_of_week',
        'time',
    ];

    public function patientMedication()
    {
        return $this->belongsTo(PatientMedication::class, 'patient_profile_id');
    }

    public static function getDayOfWeekOptions()
    {
        $type = DB::select(DB::raw("SHOW COLUMNS FROM doctor_schedule WHERE Field = 'day_of_week'"))[0]->Type;
        preg_match("/^enum\((.*)\)$/", $type, $matches);
        $enum = array_map(function ($value) {
            return trim($value, "'");
        }, explode(',', $matches[1]));

        return $enum;
    }
}
