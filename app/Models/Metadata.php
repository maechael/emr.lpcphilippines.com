<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    use HasFactory;

    protected $table = 'metadata';

    protected $fillable = [
        'basename',
        'filename',
        'filepath',
        'type',
        'size',
    ];

    public function labResults()
    {
        return $this->hasMany(LabResults::class, 'metadata_id');
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'media_id');
    }
}
