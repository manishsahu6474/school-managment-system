<?php

namespace App\Services;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentService extends BaseService
{

    public function getStudentsList($filters)
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? 'active';

        $query = Student::select('id', 'user_id', 'class_id', 'father_name', 'roll_no', 'phone', 'dob', 'status')
            ->with(['user:id,name', 'classes:id,class_name'])
            ->latest();

        if ($status === 'pending') {
            $query->where('status', 0);
        } elseif ($status === 'inactive') {
            $query->where('status', 2);
        } else {
            $query->where('status', 1);
        }
        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })
                    ->orWhere('roll_no', 'like', "%$search%")
                    ->orWhereHas('classes', function ($c) use ($search) {
                        $c->where('class_name', 'like', "%$search%");
                    });
            });
        }
        return [
            'students' => $query->paginate(10),
            'pending_count' => Student::where('status', 0)->count()
        ];
    }
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
                'father_name' => $data['father_name'],
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
