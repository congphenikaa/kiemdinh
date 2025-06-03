<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'semester_id',
        'processed_date',
        'total_amount',
        'status',
        'notes'
    ];

    protected $casts = [
        'processed_date' => 'date'
    ];

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function payments()
    {
        return $this->hasMany(TeacherPayment::class);
    }
}