<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DegreeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'short_name' => $this->faker->lexify('???'),
            'salary_coefficient' => $this->faker->randomFloat(2, 1, 3),
        ];
    }
}