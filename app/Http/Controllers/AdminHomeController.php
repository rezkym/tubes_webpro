<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\StudentProfile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AdminHomeController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $statistics = $this->getMainStatistics();

        // Data Kehadiran
        $attendanceData = $this->getAttendanceData();
        

        // Aktivitas Terkini
        $recentActivities = $this->getRecentActivities();

        return view('admin.index', compact(
            'statistics',
            'attendanceData',
            'recentActivities'
        ));
    }

    private function getMainStatistics()
    {
        return [
            'total_students' => User::role('student')->count(),
            'total_teachers' => User::role('teacher')->count(),
            'total_classes' => SchoolClass::count(),
            'recent_students' => StudentProfile::with(['user', 'schoolClass'])
                ->latest()
                ->take(5)
                ->get()
        ];
    }

    private function getAttendanceData()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        $dailyAttendance = DB::table('attendances')
            ->select(
                DB::raw('DATE(created_at) as attendance_date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();

        $classAttendance = DB::table('attendances')
            ->join('student_profiles', 'attendances.student_profile_id', '=', 'student_profiles.user_id')
            ->join('school_classes', 'student_profiles.school_class_id', '=', 'school_classes.id')
            ->select(
                'school_classes.id',
                'school_classes.name',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END) as present_count')
            )
            ->where('attendances.created_at', '>=', Carbon::today())
            ->groupBy('school_classes.id', 'school_classes.name')
            ->get();

        return [
            'daily_attendance' => $dailyAttendance,
            'class_attendance' => $classAttendance
        ];
    }

    private function getRecentActivities()
    {
        // Get recent activities from multiple sources
        $activities = collect();

        // Recent user registrations
        $newUsers = User::with('roles')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'registration',
                    'description' => $user->name . ' joined as ' . $user->roles->first()->name,
                    'timestamp' => $user->created_at
                ];
            });

        // Recent attendance records
        $recentAttendance = DB::table('attendances')
            ->join('users', 'attendances.student_profile_id', '=', 'users.id')
            ->select('users.name', 'attendances.status', 'attendances.created_at')
            ->latest('attendances.created_at')
            ->take(5)
            ->get()
            ->map(function ($record) {
                return [
                    'type' => 'attendance',
                    'description' => $record->name . ' marked as ' . $record->status,
                    'timestamp' => $record->created_at
                ];
            });

        // Combine and sort activities
        $activities = $activities->concat($newUsers)
            ->concat($recentAttendance)
            ->sortByDesc('timestamp')
            ->take(10)
            ->values()
            ->all();

        return $activities;
    }
}
