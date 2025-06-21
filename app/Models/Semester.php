<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'academic_year_id',
        'start_date',
        'end_date',
        'type',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classes()
    {
        return $this->hasMany(Clazz::class);
    }

    public function teacherPayments()
    {
        return $this->hasMany(TeacherPayment::class);
    }

    public function paymentBatches()
    {
        return $this->hasMany(PaymentBatch::class);
    }
}