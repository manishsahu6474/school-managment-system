<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Student extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'roll_no', 'class', 'father_name', 'phone', 'dob', 'status'];

public function user()
{
    return $this->belongsTo(User::class);
}
     
}
