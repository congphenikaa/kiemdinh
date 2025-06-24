<?php

namespace Database\Factories;

use App\Models\Faculty;
use App\Models\Degree;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->numerify('GV####'),
            'name' => $this->faker->name(),
            'dob' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'address' => $this->faker->address(),
            'faculty_id' => Faculty::factory(),
            'degree_id' => Degree::factory(),
            'start_date' => $this->faker->date(),
            'is_active' => true,
            'notes' => $this->faker->sentence(),
        ];
    }
}