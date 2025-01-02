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
        $this->authorize('create', StudentProfile::class);
        
        $student = StudentProfile::create($request->validated());
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
}