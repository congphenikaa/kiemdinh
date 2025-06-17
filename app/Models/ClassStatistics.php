<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassStatistics extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'total_sessions_taught',
        'average_attendance'
    ];

    public function class()
    {
        return $this->belongsTo(Clazz::class);
    }
}