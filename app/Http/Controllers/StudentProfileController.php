<?php

namespace App\Http\Controllers;

use App\Models\StudentProfile;
use App\Models\SchoolClass;
use App\Http\Requests\StoreStudentProfileRequest;
use App\Http\Requests\UpdateStudentProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class StudentProfileController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', StudentProfile::class);

        $students = StudentProfile::with(['user', 'schoolClass'])
            ->paginate(15);
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $this->authorize('create', StudentProfile::class);

        $classes = SchoolClass::all();
        return view('students.create', compact('classes'));
    }

    public function store(StoreStudentProfileRequest $request)
    {

        dd("test");
        $this->authorize('create', StudentProfile::class);


        $student = StudentProfile::create($request->validated());
        dd($student);
        return redirect()->route('students.index')
            ->with('success', 'Student profile created successfully.');
    }

    public function show(StudentProfile $student)
    {
        $this->authorize('view', $student);

        return view('students.show', compact('student'));
    }

    public function edit(StudentProfile $student)
    {
        $this->authorize('update', $student);

        $classes = SchoolClass::all();
        return view('students.edit', compact('student', 'classes'));
    }

    public function update(UpdateStudentProfileRequest $request, StudentProfile $student)
    {
        $this->authorize('update', $student);

        $student->update($request->validated());
        return redirect()->route('students.index')
            ->with('success', 'Student profile updated successfully.');
    }

    public function destroy(StudentProfile $student)
    {
        $this->authorize('delete', $student);

        $student->delete();
        return redirect()->route('students.index')
            ->with('success', 'Student profile deleted successfully.');
    }

    public function searchStudentNumbers(Request $request)
{
    $search = $request->get('term');
    
    $studentNumbers = StudentProfile::query()
        ->select('student_profiles.user_id', 'student_profiles.full_name', 'school_classes.name as class_name')
        ->join('school_classes', 'student_profiles.school_class_id', '=', 'school_classes.id')
        ->where(function($query) use ($search) {
            $query->where('student_profiles.student_number', 'LIKE', "%{$search}%")
                  ->orWhere('student_profiles.full_name', 'LIKE', "%{$search}%")
                  ->orWhere('school_classes.name', 'LIKE', "%{$search}%");
        })
        ->limit(10)
        ->get()
        ->map(function($student) {
            return [
                'id' => $student->student_number,
                'text' => sprintf(
                    '%s - %s (%s)', 
                    $student->student_number, 
                    $student->full_name,
                    $student->class_name
                )
            ];
        });
    
    return response()->json(['results' => $studentNumbers]);
}
}
