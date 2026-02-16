<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\teacher;
use App\Models\User;

class AdminController extends Controller
{
    //
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = User::Where('role','teacher')->count();
        $totalClasses  = 0;
        $totalSubjects = 0;
        
        return view('admin.dashboard',compact('totalClasses','totalStudents','totalSubjects','totalTeachers'));
    }
}
