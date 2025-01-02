<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceTemplate;
use App\Models\Attendance;
use Carbon\Carbon;

class StudentHomeController extends Controller
{
    public function index()
    {
        $student = Auth::user()->studentProfile;
        
        // Get current day
        $currentDay = strtolower(Carbon::now()->format('l'));

        // Debugging: Log the current day

        // Get today's schedule
        $todaySchedule = AttendanceTemplate::with(['subject', 'teacher'])
            ->whereHas('schoolClass', function($query) use ($student) {
                $query->where('id', $student->school_class_id);
            })
            ->where('day', $currentDay)
            ->orderBy('start_time')
            ->get();

        // Debugging: Log the retrieved schedule
        // \Log::info('Today\'s Schedule:', $todaySchedule->toArray());

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
