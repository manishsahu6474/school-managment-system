<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'phone',
        'gender',
        'address',
        'qualification',
        'experience',
        'salary',
        'joining_date',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects_classes', 'teacher_id', 'subject_id')
            ->withPivot('class_id')
            ->withTimestamps();
    }
     public function classes()
    {
        return $this->belongsToMany(Classes::class, 'teacher_subjects_classes', 'teacher_id', 'class_id')
            ->withPivot('subject_id')
            ->withTimestamps();
    }
    protected $casts = [
        'joining_date' => 'date',
    ];
}
