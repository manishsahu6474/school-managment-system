<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //admin add karene ke liye
        User::create(
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('1234567890'),
                'role' => 'admin',
            ]
        );
    }
}
