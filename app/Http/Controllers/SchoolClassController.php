<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Http\Requests\StoreSchoolClassRequest;
use App\Http\Requests\UpdateSchoolClassRequest;

class SchoolClassController extends Controller
{
    public function index()
    {
        $classes = SchoolClass::withCount(['students', 'teachers'])
            ->latest()
            ->paginate(10);

        return view('school-classes.index', compact('classes'));
    }

    public function create()
    {
        return view('school-classes.create');
    }

    public function store(StoreSchoolClassRequest $request)
    {
        SchoolClass::create($request->validated());

        return redirect()
            ->route('school-classes.index')
            ->with('success', 'Class created successfully.');
    }

    public function edit(SchoolClass $schoolClass)
    {
        return view('school-classes.edit', compact('schoolClass'));
    }

    public function update(UpdateSchoolClassRequest $request, SchoolClass $schoolClass)
    {
        $schoolClass->update($request->validated());

        return redirect()
            ->route('school-classes.index')
            ->with('success', 'Class updated successfully.');
    }

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