<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clazz extends Model
{
    use HasFactory;

    protected $table = 'classes';
    protected $fillable = [
        'class_code',
        'course_id',
        'semester_id',
        'room',
        'max_students',
        'current_students',
        'schedule_type',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

   public function teachingAssignments()
    {
        return $this->hasMany(TeachingAssignment::class, 'class_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teaching_assignments')
            ->using(TeachingAssignment::class)
            ->withPivot('id');
    }

    public function statistics()
    {
        return $this->hasOne(ClassStatistics::class);
    }

    public function payments()
    {
        return $this->hasMany(TeacherPayment::class);
    }
}