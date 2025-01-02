<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSchoolSubjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage subjects');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:school_subjects'],
            'description' => ['nullable', 'string'],
            'teacher_id' => ['required', 'exists:users,id'],
            'classes' => ['nullable', 'array'],
            'classes.*' => ['exists:school_classes,id'],
            'is_active' => ['boolean']
        ];
    }
}
