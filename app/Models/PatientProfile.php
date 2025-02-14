<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;

    protected $table = "patient_profile";

    protected $fillable = [
        'firstname',
        'lastname',
        'birthdate',
        'contact_number',
        'address',
        'is_pwd',
        'pwd_number',

    ];
}
