<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            // Create user with type = 0 (student)
            $user = User::create([
                'name' => "student $i",
                'email' => "student$i@example.com",
                'password' => bcrypt('password'),
                'type' => 0, // Assuming 0 is for students
            ]);

            // Create related student profile
            Student::create([
                'user_id' => $user->id,
                'student_number' => 'STD' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'names' => $user->name,
                'address' => 'Building ' . chr(64 + $i) . ', Campus',
                'phone' => '07880000' . $i . $i,
            ]);
        }
    }
}
