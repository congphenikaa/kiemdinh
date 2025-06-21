<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPayment extends Model
{
    protected $fillable = [
        'teacher_id',
        'class_id',
        'payment_batch_id',
        'semester_id',
        'total_sessions',
        'degree_coefficient',
        'size_coefficient',
        'base_rate',
        'total_amount',
        'status',
        'payment_date'
    ];

    protected $casts = [
        'total_sessions' => 'integer',
        'degree_coefficient' => 'decimal:3',
        'size_coefficient' => 'decimal:3',
        'base_rate' => 'decimal:10',
        'total_amount' => 'decimal:12',
        'payment_date' => 'date'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(Clazz::class);
    }

    public function paymentBatch()
    {
        return $this->belongsTo(PaymentBatch::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}