<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'school_class_id' => ['sometimes', 'required', 'exists:school_classes,id'],
            'student_number' => ['sometimes', 'required', 'string', 'max:20', 'unique:student_profiles,student_number,' . $this->student->id],
            'full_name' => ['sometimes', 'required', 'string', 'max:255'],
            'date_of_birth' => ['sometimes', 'required', 'date'],
            'gender' => ['sometimes', 'required', 'in:male,female'],
            'address' => ['sometimes', 'required', 'string'],
            'parent_name' => ['sometimes', 'required', 'string', 'max:255'],
            'parent_phone' => ['sometimes', 'required', 'string', 'max:20'],
            'enrollment_date' => ['sometimes', 'required', 'date']
        ];
    }
}