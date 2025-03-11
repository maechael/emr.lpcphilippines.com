<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    use HasFactory;

    protected $table = "doctor_profile";

    protected $fillable = [
        'specialization_id',
        'user_profile_id',
        'firstname',
        'lastname',
        'email',
        'contact_number',
        'license_number',
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
    }

    public function doctorSchedule()
    {
        return $this->hasMany(DoctorSchedule::class, 'doctor_profile_id');
    }

    public function patientAppointment()
    {
        return $this->hasMany(PatientAppointment::class, 'doctor_profile_id');
    }

    public function doctorProfile()
    {
        return $this->hasMany(DoctorProfile::class, 'doctor_profile_id');
    }

    public static function getAvailableDoctorBySpecializationDate($specialization, $date)
    {
        $dayOfWeek = Carbon::parse($date)->format('l'); // Extract the day of the week
        $time = Carbon::parse($date)->format('H:i:s'); // Extract time from the given date

        $availableDoctors = self::where('specialization_id', $specialization)
            ->whereHas('doctorSchedule', function ($query) use ($dayOfWeek, $time) {
                $query->where('day_of_week', $dayOfWeek)
                    ->where('start_time', '<=', $time)
                    ->where('end_time', '>=', $time);
            })
            ->get();

        return $availableDoctors ? $availableDoctors : [];
    }

    public function userProfile()
    {
        return $this->belongsTo(UserProfile::class, 'user_profile_id');
    }
}
