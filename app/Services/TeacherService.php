<?php

namespace App\Services;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;

class TeacherService extends BaseService
{

    public function getTeachersList($filters)
    {
        $search = $filters['search'] ?? null;
        $status = $filters['status'] ?? 'active';

        $query = Teacher::select('id', 'user_id', 'phone', 'qualification', 'salary', 'joining_date', 'status')
            ->with(['user:id,name', 'subjects:id,subject_name', 'classes:id,class_name'])
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
                    ->orWhereHas('subjects', function ($s) use ($search) {
                        $s->where('subject_name', 'like', "%$search%");
                    })
                    ->orWhereHas('classes', function ($c) use ($search) {
                        $c->where('class_name', 'like', "%$search%");
                    });
            });
        }
        return [
            'teachers' => $query->paginate(10),
            'pending_count' => Teacher::where('status', 0)->count()
        ];
    }
    public function storeTeacher($data)
    {
        return  DB::transaction(function () use ($data) {
            $exists = DB::table('teacher_subjects_classes')
                ->where('subject_id', $data['subject_id'])
                ->where('class_id', $data['class_id'])
                ->exists();

            if ($exists) {
                throw new Exception('Subject already assign to this class');
            }

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
            if (!empty($data['subject_id']) && !empty($data['class_id'])) {
                $alreadyAssigned = DB::table('teacher_subjects_classes')
                    ->where('subject_id', $data['subject_id'])
                    ->where('class_id', $data['class_id'])
                    ->where('teacher_id', '!=', $teacher->id)
                    ->exists();

                if ($alreadyAssigned) {
                    throw new Exception("Subject is already assign to this class!");
                }
                $teacher->subjects()->syncWithPivotValues($data['subject_id'], [
                    'class_id' => $data['class_id']
                ]);
            }
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
            return $teacher;
        });
    }
}
