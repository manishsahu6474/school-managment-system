<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Classes;

class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'user_id' => User::factory()->create(['role' => 'student'])->id,

            'father_name' => $this->faker->name('male'),

            'roll_no' => 'STU-' . $this->faker->unique()->numberBetween(1000, 99999),

            'class_id' => Classes::inRandomOrder()->first()->id ?? Classes::factory(),

            'phone' => $this->faker->phoneNumber,

            'dob' => $this->faker->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            'status' => $this->faker->randomElement([0, 1, 2]),
            'created_at' => now(),
        ];
    }
}
