<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;
class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalStudents = Student::count();
        $totalTeachers = 0;
        $totalClasses  = 0;
        $totalSubjects = 0;
        
        return view('dashboard',compact('totalClasses','totalStudents','totalSubjects','totalTeachers'));
    }
       
}
