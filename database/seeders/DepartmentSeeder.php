<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'Computer Science', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Information Technology', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Software Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Business Administration', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Education', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
