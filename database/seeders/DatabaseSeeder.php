<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Classes::factory(5)->create();
        \App\Models\Subject::factory(6)->create();
        $this->call(UserSeeder::class);
        \App\Models\Teacher::factory(10)->create();
        \App\Models\Student::factory(20)->create();
    }
}
