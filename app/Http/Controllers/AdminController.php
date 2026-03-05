<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = User::Where('role', 'teacher')->count();
        $totalClasses  = 4;
        $totalSubjects = 0;
        $stats = [
            'student' => [
                'total' => Student::count(),
                'active' => Student::where('status', 'active')->count(),
                'inactive' => Student::where('status', 'inactive')->count()
            ],
            'teacher' => [
                'total' => Teacher::count(),
                'active' => Teacher::where('status', 'active')->count(),
                'inactive' => Teacher::where('status', 'inactive')->count()
            ]
        ];

        return view('admin.dashboard', compact('totalClasses', 'totalStudents', 'totalSubjects', 'totalTeachers', 'stats'));
    }
}
