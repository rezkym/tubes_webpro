<?php

namespace Database\Seeders;

use App\Models\SchoolSubject;
use App\Models\SchoolClass;
use App\Models\TeacherProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSubjectSeeder extends Seeder
{
    public function run()
    {
        // Create test teachers
        $teacherUser1 = User::create([
            'name' => 'Test Teacher 1',
            'email' => 'teacher1.test@example.com',
            'password' => bcrypt('password')
        ]);
        $teacherUser1->assignRole('teacher');
        
        $teacherUser2 = User::create([
            'name' => 'Test Teacher 2',
            'email' => 'teacher2.test@example.com',
            'password' => bcrypt('password')
        ]);
        $teacherUser2->assignRole('teacher');

        // Create teacher profiles
        $teacher1 = TeacherProfile::create([
            'user_id' => $teacherUser1->id,
            'employee_number' => 'TEST-T001',
            'full_name' => 'Test Teacher 1',
            'specialization' => 'Mathematics',
            'phone_number' => '1234567890',
            'address' => 'Test Address 1',
            'join_date' => now(),
            'education_level' => 'Master',
            'teaching_experience_years' => 5
        ]);

        $teacher2 = TeacherProfile::create([
            'user_id' => $teacherUser2->id,
            'employee_number' => 'TEST-T002',
            'full_name' => 'Test Teacher 2',
            'specialization' => 'Physics',
            'phone_number' => '0987654321',
            'address' => 'Test Address 2',
            'join_date' => now(),
            'education_level' => 'Doctorate',
            'teaching_experience_years' => 8
        ]);

        // Create test classes
        $class1 = SchoolClass::create([
            'name' => 'Test Class 1',
            'code' => 'TC1',
            'description' => 'Test Class Description 1',
            'is_active' => true
        ]);

        $class2 = SchoolClass::create([
            'name' => 'Test Class 2',
            'code' => 'TC2',
            'description' => 'Test Class Description 2',
            'is_active' => true
        ]);

        // Create test subjects with various scenarios
        $subject1 = SchoolSubject::create([
            'name' => 'Test Subject 1',
            'code' => 'TS1',
            'description' => 'Test Subject Description 1',
            'is_active' => true
        ]);

        $subject2 = SchoolSubject::create([
            'name' => 'Test Subject 2',
            'code' => 'TS2',
            'description' => 'Test Subject Description 2',
            'is_active' => true
        ]);

        $subject3 = SchoolSubject::create([
            'name' => 'Test Subject 3',
            'code' => 'TS3',
            'description' => 'Test Subject Description 3',
            'is_active' => false
        ]);

        // Create relationships in class_subject table
        DB::table('class_subject')->insert([
            [
                'class_id' => $class1->id,
                'subject_id' => $subject1->id,
                'teacher_profile_id' => $teacher1->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => $class2->id,
                'subject_id' => $subject1->id,
                'teacher_profile_id' => $teacher1->id,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'class_id' => $class1->id,
                'subject_id' => $subject2->id,
                'teacher_profile_id' => $teacher2->id,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}