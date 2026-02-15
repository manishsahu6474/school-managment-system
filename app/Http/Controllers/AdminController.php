<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\teacher;

class AdminController extends Controller
{
    //
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses  = 0;
        $totalSubjects = 0;
        
        return view('admin.dashboard',compact('totalClasses','totalStudents','totalSubjects','totalTeachers'));
    }
}
