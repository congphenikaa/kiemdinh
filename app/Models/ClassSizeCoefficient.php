<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSizeCoefficient extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year_id',
        'min_students',
        'max_students',
        'coefficient'
    ];
    
    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
