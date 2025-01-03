<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceTemplate;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TeacherHomeController extends Controller
{
    /**
     * Display the teacher's dashboard/home page
     * 
     * This method retrieves and displays:
     * - Today's class schedule for the authenticated teacher
     * - Recent attendance records (last 5)
     * - Today's attendance statistics grouped by status
     *
     * @return \Illuminate\View\View Returns the teacher's dashboard view with schedule, attendance and stats data
     */
    public function index()
    {
        $teacher = Auth::user()->teacherProfile;
        
        // Get today's classes
        $todaySchedule = AttendanceTemplate::with(['schoolClass', 'subject'])
            ->where('teacher_profile_id', $teacher->id)
            ->where('day', strtolower(Carbon::now()->format('l')))
            ->orderBy('start_time')
            ->get();

        // Get recent attendance records
        $recentAttendance = Attendance::with(['student', 'class', 'subject'])
            ->where('teacher_profile_id', $teacher->id)
            ->latest()
            ->take(5)
            ->get();

        // Get attendance statistics
        $attendanceStats = Attendance::where('teacher_profile_id', $teacher->id)
            ->where('date', Carbon::today())
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('teacher.index', compact(
            'todaySchedule',
            'recentAttendance',
            'attendanceStats'
        ));
    }
}
