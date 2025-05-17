<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    protected $fillable = [
        'name',
        'short_name'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
} 