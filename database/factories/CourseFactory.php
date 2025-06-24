<?php

namespace Database\Factories;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    public function definition()
    {
        return [
            'course_code' => $this->faker->unique()->bothify('COURSE###'),
            'name' => $this->faker->words(3, true),
            'credit_hours' => $this->faker->numberBetween(1, 4),
            'total_sessions' => $this->faker->numberBetween(15, 45),
            'description' => $this->faker->paragraph(),
            'faculty_id' => Faculty::factory(),
        ];
    }
}