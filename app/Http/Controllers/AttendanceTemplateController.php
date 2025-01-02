<?php

namespace App\Http\Controllers;

use App\Models\AttendanceTemplate;
use App\Models\SchoolClass;
use App\Models\SchoolSubject;
use App\Http\Requests\StoreAttendanceTemplateRequest;
use App\Http\Requests\UpdateAttendanceTemplateRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceTemplateController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $query = AttendanceTemplate::with(['schoolClass', 'subject', 'teacher']);

        // If teacher, only show their templates
        if (Auth::user()->hasRole('teacher')) {
            $query->where('teacher_profile_id', Auth::user()->teacherProfile->id);
        }

        $templates = $query->latest()->paginate(10);
        return view('attendance-templates.index', compact('templates'));
    }

    public function create()
    {
        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = SchoolSubject::where('is_active', true)->get();
        $days = AttendanceTemplate::getDays();

        return view('attendance-templates.create', compact('classes', 'subjects', 'days'));
    }

    public function store(StoreAttendanceTemplateRequest $request)
    {


        $user = Auth::user();
        $data = $request->validated();

        if ($user->hasRole('admin')) {
            // Admin dapat memilih teacher dari form
            if (!isset($data['teacher_profile_id'])) {
                return back()->with('error', 'Please select a teacher.');
            }
            $teacherProfileId = $data['teacher_profile_id'];
        } else {
            // Untuk teacher, gunakan profile mereka sendiri
            $teacherProfile = $user->teacherProfile;
            if (!$teacherProfile) {
                return back()->with('error', 'Teacher profile not found.');
            }
            $teacherProfileId = $teacherProfile->id;
        }

        try {
            $template = AttendanceTemplate::create([
                ...$data,
                'teacher_profile_id' => $teacherProfileId
            ]);

            return redirect()
                ->route('attendance-templates.index')
                ->with('success', 'Template created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create template: ' . $e->getMessage());
        }
    }

    public function edit(AttendanceTemplate $attendance_template)  // Changed from $template
    {
        $this->authorize('update', $attendance_template);

        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = SchoolSubject::where('is_active', true)->get();
        $days = AttendanceTemplate::getDays();

        return view('attendance-templates.edit', compact('attendance_template', 'classes', 'subjects', 'days'));
    }

    public function update(UpdateAttendanceTemplateRequest $request, AttendanceTemplate $attendance_template)
    {
        $this->authorize('manage attendance', $attendance_template);
        return $attendance_template->update($request->validated())
            ? redirect()->route('attendance-templates.index')->with('success', 'Template updated successfully.')
            : back()->with('error', 'Failed to update template');
    }

    public function destroy(AttendanceTemplate $attendance_template)
    {
        $this->authorize('manage attendance', $attendance_template);

        $attendance_template->delete();

        return redirect()
            ->route('attendance-templates.index')
            ->with('success', 'Template deleted successfully.');
    }
}
