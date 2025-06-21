<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'day_of_week',
        'start_time',
        'end_time',
        'date',
        'session_type',
        'is_cancelled',
        'cancellation_reason',
        'session_number',
        'is_taught'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_cancelled' => 'boolean',
        'is_taught' => 'boolean'
    ];

    public function class()
    {
        return $this->belongsTo(Clazz::class);
    }
}