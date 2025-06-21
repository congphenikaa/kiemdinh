<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'semester_id',
        'total_classes',
        'total_sessions_taught',
        'total_sessions_cancelled',
        'average_attendance'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}