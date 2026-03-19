<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Classes;

class ClassesController extends Controller
{
    //
    public function index(Request $request)
    {
        $classdata = Classes::withCount(['students' => function ($query) {
            $query->where('status', 1);
        }])->get();
        return view('classes.index', compact('classdata'));
    }

    public function showStudents(Request $request, $id)
    {
        $classes = Classes::findOrFail($id);
        $search = $request->search;

        $query = Student::with(['user', 'Classes'])
            ->where('class_id', $id) 
            ->where('status', 1);

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })
                    ->orWhere('roll_no', 'like', "%$search%");
            });
        }

        $students = $query->latest()->paginate(10);

        return view('classes.show', compact('students', 'search', 'classes'));
    }
}
