<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'is_active' => 'boolean'
    ];

    public function semesters()
    {
        return $this->hasMany(Semester::class);
    }

    public function classSizeCoefficients()
    {
        return $this->hasMany(ClassSizeCoefficient::class);
    }

    public function paymentConfigs()
    {
        return $this->hasMany(PaymentConfig::class);
    }

    public function classes()
    {
        return $this->hasManyThrough(Clazz::class, Semester::class);
    }
}