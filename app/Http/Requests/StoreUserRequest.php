<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if user has permission then set true
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'exists:roles,name'],
        ];

        if ($this->input('role') === 'student') {
            $rules = array_merge($rules, [
                'class_id' => ['required', 'exists:school_classes,id'],
                'student_number' => ['required', 'string', 'unique:student_profiles'],
                'full_name' => ['required', 'string', 'max:255'],
                'date_of_birth' => ['required', 'date'],
                'gender' => ['required', 'in:male,female'],
                'address' => ['required', 'string'],
                'parent_name' => ['required', 'string', 'max:255'],
                'parent_phone' => ['required', 'string', 'max:255'],
                'enrollment_date' => ['required', 'date'],
            ]);
        } elseif ($this->input('role') === 'teacher') {
            $rules = array_merge($rules, [
                'employee_number' => ['required', 'string', 'unique:teacher_profiles'],
                'teacher_full_name' => ['required', 'string', 'max:255'],
                'specialization' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255'],
                'teacher_address' => ['required', 'string'],
                'join_date' => ['required', 'date'],
                'education_level' => ['required', 'string', 'in:Bachelor,Master,Doctorate'],
                'teaching_experience_years' => ['required', 'integer', 'min:0'],
            ]);
        }

        return $rules;
    }
}
