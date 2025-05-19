<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LecturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $departments = Department::pluck('id')->toArray(); // Get existing department IDs

        for ($i = 1; $i <= 5; $i++) {
            // Create user with type = 2 (lecturer)
            $user = User::create([
                'name' => "Lecturer $i",
                'email' => "lecturer$i@example.com",
                'password' => bcrypt('password'),
                'type' => 2,
            ]);

            // Create related lecturer profile
            Lecturer::create([
                'user_id' => $user->id,
                'staff_number' => 'STF' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'names' => $user->name,
                'department_id' => $departments[array_rand($departments)] ?? null,
                'address' => 'Building ' . chr(64 + $i) . ', Campus',
                'phone' => '07880000' . $i . $i,
            ]);
        }
    }
}
