<?php

namespace App\Http\Controllers;

use App\Models\TeacherProfile;
use App\Models\SchoolSubject;
use App\Http\Requests\StoreTeacherProfileRequest;
use App\Http\Requests\UpdateTeacherProfileRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\User;

class TeacherProfileController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', TeacherProfile::class);
        
        $teachers = TeacherProfile::with(['user', 'subjects', 'classes'])
            ->paginate(15);
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $this->authorize('create', TeacherProfile::class);
    
        $users = User::role('teacher')
            ->whereNotIn('id', TeacherProfile::pluck('user_id'))
            ->get();
        $subjects = SchoolSubject::whereNull('teacher_id')->get();
        
        return view('teachers.create', compact('users', 'subjects'));
    }

    public function store(StoreTeacherProfileRequest $request)
    {
        $this->authorize('create', TeacherProfile::class);
        
        $teacher = TeacherProfile::create($request->validated());
        
        if ($request->has('subject_ids')) {
            SchoolSubject::whereIn('id', $request->subject_ids)
                ->update(['teacher_id' => $teacher->id]);
        }
        
        return redirect()->route('teachers.index')
            ->with('success', 'Teacher profile created successfully.');
    }

    public function show(TeacherProfile $teacher)
    {
        $this->authorize('view', $teacher);
        
        $teacher->load(['subjects', 'classes']);
        return view('teachers.show', compact('teacher'));
    }

    public function edit(TeacherProfile $teacher)
    {
        $this->authorize('update', $teacher);
        
        $subjects = SchoolSubject::where(function($query) use ($teacher) {
            $query->whereNull('teacher_id')
                  ->orWhere('teacher_id', $teacher->id);
        })->get();
        
        return view('teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(UpdateTeacherProfileRequest $request, TeacherProfile $teacher)
    {
        $this->authorize('update', $teacher);
        
        $teacher->update($request->validated());
        
        if ($request->has('subject_ids')) {
            // Remove teacher from previously assigned subjects
            SchoolSubject::where('teacher_id', $teacher->id)
                ->update(['teacher_id' => null]);
            
            // Assign new subjects
            SchoolSubject::whereIn('id', $request->subject_ids)
                ->update(['teacher_id' => $teacher->id]);
        }
        
        return redirect()->route('teachers.index')
            ->with('success', 'Teacher profile updated successfully.');
    }

    public function destroy(TeacherProfile $teacher)
    {
        $this->authorize('delete', $teacher);
        
        // Remove teacher from subjects before deletion
        SchoolSubject::where('teacher_id', $teacher->id)
            ->update(['teacher_id' => null]);
            
        $teacher->delete();
        return redirect()->route('teachers.index')
            ->with('success', 'Teacher profile deleted successfully.');
    }
}