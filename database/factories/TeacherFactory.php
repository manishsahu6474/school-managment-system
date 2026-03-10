<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
            'user_id' => \App\Models\User::factory()->create(['role' => 'teacher'])->id,
            'phone' => $this->faker->phoneNumber,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'address' => $this->faker->address,
            'qualification' => $this->faker->randomElement(['B.Ed', 'M.Sc', 'Ph.D', 'MA']),
            'experience' => $this->faker->numberBetween(1, 5),
            'salary' => $this->faker->numberBetween(25000, 60000),
            'joining_date' => $this->faker->dateTimeBetween('-20 years', '-2 years')->format('Y-m-d'),
            'status' => $this->faker->randomElement([0, 1, 2]),
            'created_at' => now(),
        ];
    }
}
