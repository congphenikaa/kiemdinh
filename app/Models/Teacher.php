<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'dob',
        'phone',
        'email',
        'faculty_id',
        'degree_id'
    ];

    protected $casts = [
        'dob' => 'date'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }
} 