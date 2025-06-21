<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Degree extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'short_name', 
        'salary_coefficient'
    ];

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
}