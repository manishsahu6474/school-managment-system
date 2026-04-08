<?php

namespace App\Services;

use App\Models\Classes;

class ClassesService extends BaseService
{
    public function getAllClassWithCount()
    {
        return Classes::select('id', 'class_name')
            ->withCount(['students' => function ($query) {
                $query->where('status', 1);
            }])->get();
    }

    public function getStudentsByClass(Classes $classes, $search = null)
    {
        $query = $classes->students()
            ->select('id', 'user_id', 'class_id', 'roll_no', 'status', 'phone', 'dob', 'father_name', 'created_at')
            ->with(['user:id,name,email','classes:id,class_name'])
            ->where('status', 1);

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "$search%");
                })->orWhere('roll_no', 'like', "$search%");
            });
        }

        return $query->latest()->paginate(10);
    }
}
