<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'base_salary_per_session'
    ];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}