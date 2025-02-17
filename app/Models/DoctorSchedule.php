<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedule';

    protected $fillable = [
        'doctor_profile_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
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
