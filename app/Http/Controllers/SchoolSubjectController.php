<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolSubject;
use App\Models\SchoolClass;
use App\Http\Requests\StoreSchoolSubjectRequest;
use App\Http\Requests\UpdateSchoolSubjectRequest;
use App\Models\TeacherProfile;

class SchoolSubjectController extends Controller
{

    public function index()
    {
        $subjects = SchoolSubject::with(['teachers', 'classes'])
            ->latest()
            ->paginate(10);

        return view('school-subjects.index', compact('subjects'));
    }

    public function create()
    {
        $teachers = User::role('teacher')->get();
        $classes = SchoolClass::where('is_active', true)->get();

        return view('school-subjects.create', compact('teachers', 'classes'));
    }

    public function store(StoreSchoolSubjectRequest $request)
    {
        $subject = SchoolSubject::create($request->validated());

        if ($request->has('classes')) {
            // Attach classes dengan teacher_profile_id
            $attachData = collect($request->classes)->mapWithKeys(function ($classId) use ($request) {
                return [$classId => ['teacher_profile_id' => TeacherProfile::where('user_id', $request->teacher_id)->first()->id]];
            })->all();

            $subject->classes()->attach($attachData);
        }

        return redirect()->route('school-subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(SchoolSubject $schoolSubject)
    {
        $teachers = User::role('teacher')->get();
        $classes = SchoolClass::where('is_active', true)->get();
        $selectedClasses = $schoolSubject->classes->pluck('id')->toArray();

        return view('school-subjects.edit', compact('schoolSubject', 'teachers', 'classes', 'selectedClasses'));
    }

    public function update(UpdateSchoolSubjectRequest $request, SchoolSubject $schoolSubject)
    {
        $schoolSubject->update($request->validated());

        if ($request->has('classes')) {
            $schoolSubject->classes()->sync($request->classes);
        }

        return redirect()
            ->route('school-subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(SchoolSubject $schoolSubject)
    {
        // if ($schoolSubject->attendanceTemplates()->exists()) {
        //     return back()->with('error', 'Cannot delete subject with existing attendance records.');
        // }

        $schoolSubject->classes()->detach();
        $schoolSubject->delete();

        return redirect()
            ->route('school-subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
