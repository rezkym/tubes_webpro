<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'employee_number' => ['required', 'string', 'max:20', 'unique:teacher_profiles'],
            'full_name' => ['required', 'string', 'max:255'],
            'specialization' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'join_date' => ['required', 'date'],
            'education_level' => ['required', 'string', 'max:50'],
            'teaching_experience_years' => ['required', 'integer', 'min:0'],
            'subject_ids' => ['sometimes', 'array'],
            'subject_ids.*' => ['exists:school_subjects,id']
        ];
    }
}