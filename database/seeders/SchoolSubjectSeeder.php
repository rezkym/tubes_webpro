<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\SchoolSubject;
use Illuminate\Database\Seeder;

class SchoolSubjectSeeder extends Seeder
{
    public function run()
    {
        $subjects = [
            [
                'name' => 'Mathematics', 
                'code' => 'MATH', 
                'description' => 'Advanced Mathematics',
                'teacher_id' => null
            ],
            [
                'name' => 'Physics', 
                'code' => 'PHY', 
                'description' => 'Physics Science',
                'teacher_id' => null
            ],
            [
                'name' => 'Chemistry', 
                'code' => 'CHEM', 
                'description' => 'Chemistry Science',
                'teacher_id' => null
            ],
            [
                'name' => 'Biology', 
                'code' => 'BIO', 
                'description' => 'Biology Science',
                'teacher_id' => null
            ],
            [
                'name' => 'English', 
                'code' => 'ENG', 
                'description' => 'English Language',
                'teacher_id' => null
            ],
        ];

        foreach ($subjects as $subject) {
            SchoolSubject::create($subject);
        }
    }
}
