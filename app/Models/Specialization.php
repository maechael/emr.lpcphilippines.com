<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialization extends Model
{
    use HasFactory;

    protected $table = 'specialization';

    protected $fillable = [
        'name'
    ];

    public function doctorProfile()
    {
        return $this->hasOne(Specialization::class, 'specialization_id');
    }
}
