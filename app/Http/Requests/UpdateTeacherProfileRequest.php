<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'employee_number' => ['sometimes', 'required', 'string', 'max:20', 'unique:teacher_profiles,employee_number,' . $this->teacher->id],
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'specialization' => ['sometimes', 'required', 'string', 'max:100'],
            'phone_number' => ['sometimes', 'required', 'string', 'max:20'],
            'address' => ['sometimes', 'required', 'string'],
            'join_date' => ['sometimes', 'required', 'date'],
            'education_level' => ['sometimes', 'required', 'string', 'max:50'],
            'teaching_experience_years' => ['sometimes', 'required', 'integer', 'min:0'],
            'subject_ids' => ['sometimes', 'array'],
            'subject_ids.*' => ['exists:school_subjects,id']
        ];
    }
}