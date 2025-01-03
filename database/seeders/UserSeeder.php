<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TeacherProfile;
use App\Models\StudentProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        $user->assignRole('admin');

        // Create Teachers
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Teacher $i",
                'email' => "teacher$i@example.com",
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('teacher');

            $teacherProfile = TeacherProfile::create([
                'user_id' => $user->id,
                'employee_number' => "T$i" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'full_name' => "Teacher $i",
                'specialization' => ['Mathematics', 'Physics', 'Chemistry'][$i-1],
                'phone_number' => "+1234567890$i",
                'address' => "Teacher $i Address",
                'join_date' => now(),
                'education_level' => ['Bachelor', 'Master', 'Doctorate'][$i-1],
                'teaching_experience_years' => rand(1, 10)
            ]);

            // Assign classes and subjects to teachers
            $classIds = range(1, 5);
            shuffle($classIds);
            $selectedClassIds = array_slice($classIds, 0, 2);
            
            foreach ($selectedClassIds as $classId) {
                DB::table('class_teacher')->insert([
                    'class_id' => $classId,
                    'teacher_id' => $teacherProfile->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }

        // Create Students
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'name' => "Student $i",
                'email' => "student$i@example.com",
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('student');

            StudentProfile::create([
                'user_id' => $user->id,
                'school_class_id' => rand(1, 5),
                'student_number' => "S$i" . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'full_name' => "Student $i",
                'date_of_birth' => now()->subYears(rand(15, 18)),
                'gender' => ['male', 'female'][rand(0, 1)],
                'address' => "Student $i Address",
                'parent_name' => "Parent of Student $i",
                'parent_phone' => "+1987654321$i",
                'enrollment_date' => now()
            ]);
        }
    }
    
}
