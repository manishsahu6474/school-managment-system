<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        if ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($role === 'teacher') {
            return view('teachers.dashboard');
        } else {
            return view('students.dashboard');
        }
    }
}
