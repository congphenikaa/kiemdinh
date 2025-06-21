<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(Clazz::class);
    }

    // Kiểm tra xem giáo viên đã được phân công cho lớp này chưa
    public static function isAssigned($classId, $teacherId)
    {
        return self::where('class_id', $classId)
            ->where('teacher_id', $teacherId)
            ->exists();
    }
}