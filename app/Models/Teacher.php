<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'dob',
        'gender',
        'phone',
        'email',
        'address',
        'faculty_id',
        'degree_id',
        'start_date',
        'is_active',
        'notes'
    ];

    protected $casts = [
        'dob' => 'date',
        'start_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function degree()
    {
        return $this->belongsTo(Degree::class);
    }

    public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class);
    }

    public function classes()
    {
        return $this->belongsToMany(Clazz::class, 'teaching_assignments')
                   ->withPivot('main_teacher', 'assigned_sessions');
    }

    public function payments()
    {
        return $this->hasMany(TeacherPayment::class);
    }

    public function statistics()
    {
        return $this->hasMany(TeacherStatistics::class);
    }

    
}