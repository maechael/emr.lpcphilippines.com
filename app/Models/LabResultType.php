<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabResultType extends Model
{
    use HasFactory;

    protected $table = 'lab_test_type';

    protected $fillable = [
        'name'
    ];
}
