<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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

            // Har student ke liye ek user banega jiska role 'student' hoga
            'user_id' => \App\Models\User::factory()->create(['role' => 'student'])->id,

            // Father Name (Fake Male Name)
            'father_name' => $this->faker->name('male'),

            // Unique Roll Number (Jaise: STU-1023)
            'roll_no' => 'STU-' . $this->faker->unique()->numberBetween(1000, 99999),

            // Class (9 se 12 ke beech koi bhi number)
            'class_id' => \App\Models\Classes::inRandomOrder()->first()->id ?? \App\Models\Classes::factory(),

            // Phone Number
            'phone' => $this->faker->phoneNumber,

            // DOB (Date of Birth - Maan lijiye 14 se 18 saal purani date)
            'dob' => $this->faker->dateTimeBetween('-18 years', '-14 years')->format('Y-m-d'),
            // Status (Default 1 yaani Active)
            'status' => $this->faker->randomElement([0, 1, 2]),
            'created_at' => now(),
        ];
    }
}
