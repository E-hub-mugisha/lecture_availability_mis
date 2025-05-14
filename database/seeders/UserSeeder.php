<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'type' => 1, // Admin
        ]);

        // Lecturers
        $lecturers = [
            ['name' => 'Dr. Alice', 'email' => 'alice@example.com'],
            ['name' => 'Prof. Bob', 'email' => 'bob@example.com'],
            ['name' => 'Dr. Carol', 'email' => 'carol@example.com'],
            ['name' => 'Prof. Dave', 'email' => 'dave@example.com'],
            ['name' => 'Dr. Eve', 'email' => 'eve@example.com'],
            ['name' => 'Prof. Frank', 'email' => 'frank@example.com'],
        ];

        foreach ($lecturers as $lecturer) {
            User::create([
                'name' => $lecturer['name'],
                'email' => $lecturer['email'],
                'password' => Hash::make('password'),
                'type' => 2, // Lecturer
            ]);
        }

        // Students
        $students = [
            ['name' => 'Student A', 'email' => 'studenta@example.com'],
            ['name' => 'Student B', 'email' => 'studentb@example.com'],
            ['name' => 'Student C', 'email' => 'studentc@example.com'],
        ];

        foreach ($students as $student) {
            User::create([
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('password'),
                'type' => 0, // Student
            ]);
        }
    }
}
