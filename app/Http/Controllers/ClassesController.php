<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classes;
use App\Services\ClassesService;

class ClassesController extends Controller
{
    public function __construct(protected ClassesService $classesService) {}
    public function index()
    {
        try {
            $classdata = $this->classesService->getAllClassWithCount();
            return view('classes.index', compact('classdata'));
        } catch (\Exception $e) {
            return back()->with('error_msg', 'Something went Wrong: ' . $e->getMessage());
        }
    }

    public function showStudents(Request $request, Classes $classes)
    {
        try {
            $students = $this->classesService->getStudentsByClass($classes, $request->search);
            $search = $request->search;
            return view('classes.show', compact('students', 'search', 'classes'));
        } catch (\Exception $e) {
            return back()->with('error_msg', 'Something went Wrong: ' . $e->getMessage());
        }
    }
}
