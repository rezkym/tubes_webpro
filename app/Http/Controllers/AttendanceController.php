<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\SchoolSubject;
use App\Models\StudentProfile;
use App\Models\TeacherProfile;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a paginated list of attendance records with optional filters.
     *
     * This method handles the following functionality:
     * - Authorizes the user to view attendance records
     * - Loads related models (student, teacher, subject, class) through eager loading
     * - Filters records by date range if start_date and end_date are provided
     * - Filters records by class if class_id is provided
     * - Filters records by subject if subject_id is provided
     * - For teachers, restricts view to only their own records
     * - Paginates results and includes class and subject data for filtering
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $query = Attendance::with(['student', 'teacher', 'subject', 'class']);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [
                Carbon::parse($request->start_date),
                Carbon::parse($request->end_date)
            ]);
        }

        // Filter by class
        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        // Filter by subject
        if ($request->filled('subject_id')) {
            $query->where('school_subject_id', $request->subject_id);
        }

        // For teachers, only show their own records
        if (Auth::user()->hasRole('teacher')) {
            $query->where('teacher_profile_id', Auth::user()->teacherProfile->id);
        }

        $attendances = $query->latest('date')->paginate(15);
        $classes = SchoolClass::all();
        $subjects = SchoolSubject::all();

        return view('attendance.index', compact('attendances', 'classes', 'subjects'));
    }

    /**
     * Show the form for creating a new attendance record.
     * 
     * This method performs the following:
     * - Authorizes the user for attendance creation
     * - Retrieves the teacher profile if user has teacher role
     * - Gets classes associated with the teacher or all classes if not a teacher
     * - Gets subjects taught by the teacher or all subjects if not a teacher
     * 
     * @return \Illuminate\View\View Returns the attendance creation view with classes and subjects data
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     */
    public function create()
    {
        $this->authorize('create', Attendance::class);
        
        $teacher = Auth::user()->hasRole('teacher') 
            ? Auth::user()->teacherProfile 
            : null;

        $classes = $teacher 
            ? $teacher->classes()->distinct()->get() 
            : SchoolClass::all();

        $subjects = $teacher 
            ? $teacher->subjects 
            : SchoolSubject::all();

        return view('attendance.create', compact('classes', 'subjects'));
    }

    /**
     * Retrieve students for attendance.
     *
     * This method fetches students from a specific class ordered by their full name.
     * Requires authorization to take attendance for the given class and subject.
     * 
     * @param \Illuminate\Http\Request $request Contains class_id and subject_id
     * @return \Illuminate\Http\JsonResponse JSON response containing students data
     * @throws \Illuminate\Auth\Access\AuthorizationException If user is not authorized
     */
    public function getStudents(Request $request)
    {
        $this->authorize('takeAttendance', [Attendance::class, $request->class_id, $request->subject_id]);

        $students = StudentProfile::where('school_class_id', $request->class_id)
            ->orderBy('full_name')
            ->get();

        return response()->json($students);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $this->authorize('takeAttendance', [Attendance::class, $request->class_id, $request->subject_id]);

        DB::beginTransaction();
        try {
            foreach ($request->attendances as $studentId => $data) {
                Attendance::create([
                    'student_profile_id' => $studentId,
                    'school_class_id' => $request->class_id,
                    'school_subject_id' => $request->subject_id,
                    'teacher_profile_id' => Auth::user()->teacherProfile->id,
                    'date' => $request->date,
                    'status' => $data['status'],
                    'remarks' => $data['remarks'] ?? null
                ]);
            }
            DB::commit();
            
            return redirect()->route('attendance.index')
                ->with('success', 'Attendance has been recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to record attendance. Please try again.');
        }
    }

    public function edit(Request $request, $classId, $subjectId, $date)
    {
        $this->authorize('takeAttendance', [Attendance::class, $classId, $subjectId]);

        $attendances = Attendance::with('student')
            ->where('school_class_id', $classId)
            ->where('school_subject_id', $subjectId)
            ->where('date', $date)
            ->get()
            ->keyBy('student_profile_id');

        $students = StudentProfile::where('school_class_id', $classId)
            ->orderBy('full_name')
            ->get();

        $class = SchoolClass::findOrFail($classId);
        $subject = SchoolSubject::findOrFail($subjectId);

        return view('attendance.edit', compact('attendances', 'students', 'class', 'subject', 'date'));
    }

    public function update(UpdateAttendanceRequest $request, $classId, $subjectId, $date)
    {
        $this->authorize('takeAttendance', [Attendance::class, $classId, $subjectId]);

        DB::beginTransaction();
        try {
            foreach ($request->attendances as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_profile_id' => $studentId,
                        'school_class_id' => $classId,
                        'school_subject_id' => $subjectId,
                        'date' => $date
                    ],
                    [
                        'teacher_profile_id' => Auth::user()->teacherProfile->id,
                        'status' => $data['status'],
                        'remarks' => $data['remarks'] ?? null
                    ]
                );
            }
            DB::commit();

            return redirect()->route('attendance.index')
                ->with('success', 'Attendance has been updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update attendance. Please try again.');
        }
    }

    public function report(Request $request)
    {
        $this->authorize('viewAny', Attendance::class);

        $query = Attendance::with(['student', 'class', 'subject']);

        if ($request->filled(['start_date', 'end_date', 'class_id', 'subject_id'])) {
            $query->whereBetween('date', [$request->start_date, $request->end_date])
                  ->where('school_class_id', $request->class_id)
                  ->where('school_subject_id', $request->subject_id);
        }

        if (Auth::user()->hasRole('teacher')) {
            $query->where('teacher_profile_id', Auth::user()->teacherProfile->id);
        }

        $attendanceData = $query->get()
            ->groupBy('student_profile_id')
            ->map(function ($records) {
                $total = $records->count();
                $present = $records->where('status', Attendance::STATUS_PRESENT)->count();
                $absent = $records->where('status', Attendance::STATUS_ABSENT)->count();
                $late = $records->where('status', Attendance::STATUS_LATE)->count();
                
                return [
                    'student' => $records->first()->student,
                    'total' => $total,
                    'present' => $present,
                    'absent' => $absent,
                    'late' => $late,
                    'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0
                ];
            });

        $classes = SchoolClass::all();
        $subjects = SchoolSubject::all();

        return view('attendance.report', compact('attendanceData', 'classes', 'subjects'));
    }
}