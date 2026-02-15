<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;
use App\Models\teacher;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {
        $total_students = Student::where('role', 'student')->count();
        $total_teachers = Teacher::where('role', 'teacher')->count();
        $totalClasses  = 0;
        $totalSubjects = 0;
        
        return view('dashboard',compact('totalClasses','totalStudents','totalSubjects','totalTeachers'));
    }
       
}
