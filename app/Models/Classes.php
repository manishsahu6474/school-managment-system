<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    protected $table = 'classes';
    protected $fillable = ['class_name', 'section'];
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
    public function getClassNameAttribute($value)
    {

        return preg_replace('/[^0-9]/', '', $value);
    }
}
