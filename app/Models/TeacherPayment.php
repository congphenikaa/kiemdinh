<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'class_id',
        'payment_batch_id',
        'status',
        'semester_id',
        'total_sessions',
        'degree_coefficient',
        'size_coefficient',
        'base_rate',
        'total_amount',
        'payment_date',
        'notes'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'degree_coefficient' => 'decimal:3,2',
        'size_coefficient' => 'decimal:3,2',
        'base_rate' => 'decimal:10,2',
        'total_amount' => 'decimal:12,2'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(Clazz::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function paymentBatch()
    {
        return $this->belongsTo(PaymentBatch::class);
    }
}