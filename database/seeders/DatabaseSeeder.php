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

        // Create HR user
        User::create([
            'name' => 'HR Manager',
            'email' => 'hr@guess.com',
            'password' => Hash::make('password123'),
            'role' => 'hr',
            'department' => 'HR',
            'position' => 'HR Manager',
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@guess.com',
            'password' => Hash::make('password123'),
            'role' => 'manager',
            'department' => 'IT',
            'position' => 'IT Manager',
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
            'department' => 'Sales',
            'position' => 'Sales Representative',
        ]);
    }
}