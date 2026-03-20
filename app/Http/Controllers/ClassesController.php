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
        $classdata = Classes::select('id', 'class_name')
            ->withCount(['students' => function ($query) {
                $query->where('status', 1);
            }])->get();
        return view('classes.index', compact('classdata'));
    }

    public function showStudents(Request $request, Classes $classes)
    {
        $search = $request->search;
        $id = $classes->id;

        $query = $classes->students()
            ->select('id', 'user_id', 'class_id', 'roll_no', 'status', 'phone', 'dob', 'father_name')
            ->with(['user:id,name'])
            ->where('status', 1);

        if ($search) {
            $query->where(function ($sub) use ($search) {
                $sub->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%");
                })
                    ->orWhere('roll_no', 'like', "%$search%")
                    ->orWhereHas('Classes', function ($c) use ($search) {
                        $c->where('class_name', 'like', "%$search%");
                    });
            });
        }

        $students = $query->latest()->paginate(10);

        return view('classes.show', compact('students', 'search', 'classes'));
    }
}
