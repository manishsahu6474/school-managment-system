<?php

namespace Database\Seeders;

use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
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
        Classes::factory(5)->create();
        Subject::factory(6)->create();
        $this->call(UserSeeder::class);
        Teacher::factory(10)->create();
        Student::factory(20)->create();
    }
}
