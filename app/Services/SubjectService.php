<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use Exception;

class SubjectService extends BaseService
{
    public function getAllSubject()
    {
        return Subject::select('id', 'subject_name')
           ->with(['teachers.user:id,name', 'teachers.classes:id,class_name'])
            ->latest()->paginate(10);
    }

    public function createSubject($data)
    {

        return DB::transaction(function () use ($data) {
            return Subject::create(['subject_name' => $data['subject_name']]);
        });
    }
    public function deleteSubject(Subject $subject)
    {
        return DB::transaction(function () use ($subject) {
            if ($subject->teachers()->exists()) {
                throw new Exception('Assigned to teacher so this subject can not be deleted!');
            }

            return $subject->delete();
        });
    }
}
