<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolSubject;
use App\Models\SchoolClass;
use App\Http\Requests\StoreSchoolSubjectRequest;
use App\Http\Requests\UpdateSchoolSubjectRequest;
use App\Models\TeacherProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class SchoolSubjectController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $subjects = SchoolSubject::with(['teachers', 'classes'])
            ->when(Auth::user()->hasRole('teacher'), function ($query) {
                $query->whereHas('teachers', function ($q) {
                    $q->where('teacher_profiles.user_id', Auth::id());
                });
            })
            ->latest()
            ->paginate(10);

        return view('school-subjects.index', compact('subjects'));
    }

    public function create()
    {
        $this->authorize('manage subjects', SchoolSubject::class);

        $teachers = User::role('teacher')->get();
        $classes = SchoolClass::where('is_active', true)->get();

        return view('school-subjects.create', compact('teachers', 'classes'));
    }

    public function store(StoreSchoolSubjectRequest $request)
    {
        $this->authorize('manage subjects', SchoolSubject::class);

        $data = $request->validated();
        $subject = SchoolSubject::create($data);

        if (!empty($data['classes'])) {
            $teacherProfile = TeacherProfile::where('user_id', $data['teacher_id'])->first();

            $attachData = collect($data['classes'])->mapWithKeys(function ($classId) use ($teacherProfile) {
                return [$classId => ['teacher_profile_id' => $teacherProfile->id]];
            })->all();

            $subject->classes()->attach($attachData);
        }

        return redirect()->route('school-subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function edit(SchoolSubject $schoolSubject)
    {
        $this->authorize('manage subjects', $schoolSubject);

        $teachers = User::role('teacher')->get();
        $classes = SchoolClass::where('is_active', true)->get();
        $selectedClasses = $schoolSubject->classes->pluck('id')->toArray();

        return view('school-subjects.edit', compact('schoolSubject', 'teachers', 'classes', 'selectedClasses'));
    }

    public function update(UpdateSchoolSubjectRequest $request, SchoolSubject $schoolSubject)
{
    $this->authorize('manage subjects', $schoolSubject);

    try {
        DB::beginTransaction();
        
        $data = $request->validated();
        $schoolSubject->update($data);

        if (isset($data['classes'])) {
            $teacherProfile = TeacherProfile::where('user_id', $data['teacher_id'])->firstOrFail();
            
            $syncData = collect($data['classes'])->mapWithKeys(function ($classId) use ($teacherProfile) {
                return [$classId => ['teacher_profile_id' => $teacherProfile->id]];
            })->all();

            $schoolSubject->classes()->sync($syncData);
        }

        DB::commit();
        return redirect()->route('school-subjects.index')
                        ->with('success', 'Subject updated successfully.');
                        
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed to update subject: ' . $e->getMessage());
    }
}

    public function destroy(SchoolSubject $schoolSubject)
    {
        $this->authorize('manage subjects', $schoolSubject);

        if ($schoolSubject->attendanceTemplates()->exists()) {
            return back()->with('error', 'Cannot delete subject with existing attendance records.');
        }

        $schoolSubject->classes()->detach();
        $schoolSubject->delete();

        return redirect()->route('school-subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
