<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;

class SchoolClassController extends Controller
{
    /**
     * Display a listing of the school classes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $classes = SchoolClass::withCount([
            'studentProfiles as students_count' => function ($query) {
                $query->whereHas('user.roles', function ($q) {
                    $q->where('name', 'student');
                });
            },
            'teachers as teachers_count' => function ($query) {
                $query->whereHas('roles', function ($q) {
                    $q->where('name', 'teacher');
                });
            }
        ])
            ->latest()
            ->paginate(10);

        return view('school-classes.index', compact('classes'));
    }

    /**
     * Show the form for creating a new school class.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('school-classes.create');
    }

    /**
     * Store a newly created school class in storage.
     *
     * @param  \App\Http\Requests\StoreSchoolClassRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreSchoolClassRequest $request)
    {
        SchoolClass::create($request->validated());

        return redirect()
            ->route('school-classes.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Show the form for editing the specified school class.
     *
     * @param  \App\Models\SchoolClass  $schoolClass
     * @return \Illuminate\View\View
     */
    public function edit(SchoolClass $schoolClass)
    {
        return view('school-classes.edit', compact('schoolClass'));
    }

    /**
     * Update the specified school class in storage.
     *
     * @param  \App\Http\Requests\UpdateSchoolClassRequest  $request
     * @param  \App\Models\SchoolClass  $schoolClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        $data = $request->validated();

        if ($request->has('is_active') && !$request->is_active) {
            if ($schoolClass->students()->exists() || $schoolClass->teachers()->exists()) {
                return back()->with('error', 'Cannot deactivate class with active students or teachers.');
            }
        }

        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $schoolClass->update($data);

        return redirect()
            ->route('school-classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Remove the specified school class from storage.
     *
     * @param  \App\Models\SchoolClass  $schoolClass
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(SchoolClass $schoolClass)
    {
        if ($schoolClass->students()->exists()) {
            return back()->with('error', 'Cannot delete class with active students.');
        }

        $schoolClass->delete();

        return redirect()
            ->route('school-classes.index')
            ->with('success', 'Class deleted successfully.');
    }
}
