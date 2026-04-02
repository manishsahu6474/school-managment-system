<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService extends BaseService
{
    public function bulkPromote(array $ids)
    {
        $ids = $this->validateBulkIds(Student::class, $ids);

        return DB::transaction(function () use ($ids) {
            $passoutCount = Student::whereIn('id', $ids)
                ->where('status', 1)
                ->where('class_id', 4)
                ->update(['status' => 2]);

            $promotedCount = Student::whereIn('id', $ids)
                ->where('status', 1)
                ->where('class_id', '<', 4)
                ->increment('class_id');

            return ['count' => ($promotedCount + $passoutCount)];
        });
    }
    public function storeStudent($data)
    {

        return DB::transaction(function () use ($data) {
            $user = User::create(
                [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make('password'),
                    'role' => 'student'
                ]
            );

            $user->student()->create([
                'roll_no' => $data['roll_no'] ?? null,
                'class_id' => $data['class_id'],
                'father_name' => $data['father_name'] ?? null,
                'phone' => $data['phone'],
                'dob' => $data['dob'],
                'status' => '0'
            ]);
            return $user;
        });
    }
    public function updateStudent(Student $student, $data)
    {

        return DB::transaction(function () use ($student, $data) {
            if ($student->user)
                $student->user->update(
                    [
                        'name' => $data['name'],
                        'email' => $data['email']
                    ]
                );

            $student->update([
                'roll_no' => $data['roll_no'] ?? $student->roll_no,
                'class_id' => $data['class_id'],
                'father_name' => $data['father_name'] ?? $student->father_name,
                'phone' => $data['phone'] ?? $student->phone,
                'dob' => $data['dob']
            ]);


            return $student;
        });
    }
}
