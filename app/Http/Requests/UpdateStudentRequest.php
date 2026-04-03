<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Student;

class UpdateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->filled('roll_no')) {
            $this->merge([
                'roll_no' => Student::formatRollno($this->input('roll_no')),
            ]);
        }
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $student = $this->route('student');
        $userId = $student->user->id;
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $userId,
            'father_name' => 'nullable|string|max:100',
            'roll_no' => 'required|string|max:10|unique:students,roll_no,' . $student->id,
            'dob' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d'), 'after:' . now()->subYears(20)->format('Y-m-d')],
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|digits:10',
        ];
    }
}
