<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $fillable = [
        'name',
        'short_name',
        'description'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
} 