<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $fillable = ['subject_name'];
    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'teacher_subjects_classes', 'subject_id', 'class_id')
            ->withPivot('teacher_id')
            ->distinct()
            ->withTimestamps();
    }
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects_classes', 'subject_id', 'teacher_id')
            ->withPivot('class_id')
            ->withTimestamps();
    }
}
