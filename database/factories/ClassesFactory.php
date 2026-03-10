<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'class_name' => $this->faker->randomElement(['10th', '11th', '12th', '9th']),
            'section' => $this->faker->randomElement(['A', 'B', 'C']),
        ];
    }
}
