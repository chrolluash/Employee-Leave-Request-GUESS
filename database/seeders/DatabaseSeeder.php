<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@guess.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'department' => 'Management',
            'position' => 'System Administrator',
        ]);

        // Create sample employee
        User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@guess.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'department' => 'IT',
            'position' => 'Software Developer',
        ]);

        // Create another sample employee
        User::create([
            'name' => 'Jane Smith',
            'email' => 'janesmith@guess.com',
            'password' => Hash::make('password123'),
            'role' => 'employee',
            'department' => 'HR',
            'position' => 'HR Manager',
        ]);
    }
}
