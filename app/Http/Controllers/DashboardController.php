<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;
use App\Models\teacher;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses  = 0;
        $totalSubjects = 0;
        
        return view('dashboard',compact('totalClasses','totalStudents','totalSubjects','totalTeachers'));
    }
       
}
