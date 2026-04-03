<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $teacher = $this->route('teacher');
        $userId = $teacher->user->id;
        return [
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $userId,
            'phone'         => 'required|digits:10|unique:teachers,phone,' . $teacher->id,
            'qualification' => 'required|string',
            'experience'    => 'required|numeric|min:0|max:30',
            'salary'        => 'required|numeric|min:1000',
            'gender'        => 'required|in:male,female,other',
            'joining_date'  => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subMonths(6)->format('Y-m-d'),
            ],
            'address'       => 'nullable|string|min:10|max:500',
            'password'      => 'nullable|min:8',
            'subject_id'    => 'required|integer|exists:subjects,id',
            'class_id'      => 'required|integer|exists:classes,id',
        ];
    }
}
