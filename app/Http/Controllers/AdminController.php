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
                'pending' => Student::where('status', '0')->count(),
                'active' => Student::where('status', '1')->count(),
                'inactive' => Student::where('status', '2')->count()
            ],
            'teacher' => [
                'total' => Teacher::count(),
                'pending' => Teacher::where('status', '0')->count(),
                'active' => Teacher::where('status', '1')->count(),
                'inactive' => Teacher::where('status', '2')->count()
            ]
        ];

        return view('admin.dashboard', compact('totalClasses', 'totalStudents', 'totalSubjects', 'totalTeachers', 'stats'));
    }
}
