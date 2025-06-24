<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FacultyFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'short_name' => strtoupper($this->faker->unique()->lexify('???')),
            'description' => $this->faker->sentence(),
        ];
    }

    public function withLongShortName()
    {
        return $this->state(function (array $attributes) {
            return [
                'short_name' => str_repeat('A', 51),
            ];
        });
    }
}