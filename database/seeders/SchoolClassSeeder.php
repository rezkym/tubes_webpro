<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    public function run()
    {
        $classes = [
            ['name' => 'Class 10A', 'code' => '10A', 'description' => 'Science Class A'],
            ['name' => 'Class 10B', 'code' => '10B', 'description' => 'Science Class B'],
            ['name' => 'Class 11A', 'code' => '11A', 'description' => 'Science Class A'],
            ['name' => 'Class 11B', 'code' => '11B', 'description' => 'Science Class B'],
            ['name' => 'Class 12A', 'code' => '12A', 'description' => 'Science Class A'],
        ];

        foreach ($classes as $class) {
            SchoolClass::create($class);
        }
    }
}
