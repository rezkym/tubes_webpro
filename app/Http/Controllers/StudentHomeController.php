<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceTemplate;
use App\Models\Attendance;
use Carbon\Carbon;

class StudentHomeController extends Controller
{
    /**
     * Display the student's home dashboard.
     * 
     * This method retrieves and displays:
     * - Today's class schedule based on the current day
     * - Student's recent attendance history (last 10 records)
     * - Monthly attendance statistics grouped by status
     *
     * The method performs the following operations:
     * 1. Gets the authenticated student's profile
     * 2. Determines current day of the week
     * 3. Retrieves schedule for current day with subject and teacher details
     * 4. Fetches recent attendance history
     * 5. Calculates attendance statistics for the past month
     *
     * @return \Illuminate\View\View Returns the student dashboard view with schedule, 
     *                               attendance history and statistics data
     */
    public function index()
    {
        $student = Auth::user()->studentProfile;
        
        // Get current day
        $currentDay = strtolower(Carbon::now()->format('l'));

        // Get today's schedule
        $todaySchedule = AttendanceTemplate::with(['subject', 'teacher'])
            ->whereHas('schoolClass', function($query) use ($student) {
                $query->where('id', $student->school_class_id);
            })
            ->where('day', $currentDay)
            ->orderBy('start_time')
            ->get();

        if ($todaySchedule->isEmpty()) {
            $todaySchedule = collect([['message' => 'Tidak ada jadwal untuk hari ini']]);
        }

        // Get attendance history
        $attendanceHistory = Attendance::with(['subject', 'teacher'])
            ->where('student_profile_id', $student->id)
            ->latest()
            ->take(10)
            ->get();

        // Calculate attendance statistics
        $attendanceStats = Attendance::where('student_profile_id', $student->id)
            ->where('created_at', '>=', Carbon::now()->subMonth())
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('student.index', compact(
            'todaySchedule',
            'attendanceHistory',
            'attendanceStats'
        ));
    }
}
