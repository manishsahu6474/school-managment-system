<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'joining_date' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subMonths(6)->format('Y-m-d'),
            ],
            'qualification' => 'required|string',
            'experience' => 'required|numeric|min:0|max:30',
            'salary' => 'required|numeric|min:1000',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|unique:teachers,phone|digits:10',
            'address' => 'nullable|string|min:10|max:500',
            'subject_id'    => 'required|integer|exists:subjects,id',
            'class_id'      => 'required|integer|exists:classes,id',
            'password' => 'required|min:8'
        ];
    }
}
