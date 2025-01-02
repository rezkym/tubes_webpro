<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\AttendanceTemplate;

class UpdateAttendanceTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return $this->user()->hasAnyRole(['admin', 'teacher']);
    }

    public function rules()
    {
        return [
            'school_class_id' => ['required', 'exists:school_classes,id'],
            'school_subject_id' => ['required', 'exists:school_subjects,id'],
            'teacher_profile_id' => ['required', 'exists:teacher_profiles,id'],
            'name' => ['required', 'string', 'max:255'],
            'day' => ['required', 'string', 'in:' . implode(',', array_keys(AttendanceTemplate::getDays()))],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean']
        ];
    }
}
