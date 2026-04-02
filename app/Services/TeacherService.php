<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TeacherService extends BaseService
{

    public function storeTeacher($data)
    {
        return  DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? 'password'), 
                'role' => 'teacher'

            ]);

            $teacher = $user->teacher()->create([
                'qualification' => $data['qualification'],
                'joining_date' => $data['joining_date'],
                'experience' => $data['experience'] ?? null,
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'salary' => $data['salary'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => 0

            ]);
            if ($data['subject_id'] && $data['class_id']) {
                $teacher->subjects()->attach($data['subject_id'], [
                    'class_id' => $data['class_id']
                ]);
            }
            return $teacher;
        });
    }

    public function updateTeacher($teacher, $data)
    {

        return  DB::transaction(function () use ($teacher, $data) {

            $user = $teacher->user;
            $user->update([
                'name' => $data['name'],
                'email' => $data['email']
            ]);

            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            $teacher->update([
                'qualification' => $data['qualification'],
                'joining_date' => $data['joining_date'],
                'experience' => $data['experience'] ?? null,
                'gender' => $data['gender'],
                'phone' => $data['phone'],
                'salary' => $data['salary'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $teacher->status

            ]);
            if (!empty($data['subject_id']) && !empty($data['class_id'])) {
                $teacher->subjects()->syncWithPivotValues($data['subject_id'], [
                    'class_id' => $data['class_id']
                ]);
            }
            return $teacher;
        });
    }
}
