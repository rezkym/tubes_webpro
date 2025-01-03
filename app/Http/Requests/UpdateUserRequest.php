<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if user has permission then set true
        return $this->user()->can('manage users', $this->user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,name'],
        ];

        if ($this->role === 'student') {
            $rules = array_merge($rules, [
                'class_id' => ['required', 'exists:school_classes,id'],
                'student_number' => ['required', 'string', Rule::unique('student_profiles')->ignore($this->user->studentProfile->id ?? null)],
                'date_of_birth' => ['required', 'date'],
                'gender' => ['required', 'in:male,female'],
                'address' => ['required', 'string'],
                'parent_name' => ['required', 'string', 'max:255'],
                'parent_phone' => ['required', 'string', 'max:255'],
                'enrollment_date' => ['required', 'date'],
            ]);
        } elseif ($this->role === 'teacher') {
            $rules = array_merge($rules, [
                'employee_number' => ['required', 'string', Rule::unique('teacher_profiles')->ignore($this->user->teacherProfile->id ?? null)],
                'specialization' => ['required', 'string', 'max:255'],
                'phone_number' => ['required', 'string', 'max:255'],
                'teacher_address' => ['required', 'string'],
                'join_date' => ['required', 'date'],
                'education_level' => ['required', 'string', 'max:255'],
                'teaching_experience_years' => ['required', 'integer', 'min:0'],
                'assigned_classes' => ['required', 'array', 'min:1'],
                'assigned_classes.*' => ['exists:school_classes,id']
            ]);
        }

        return $rules;
    }
}
