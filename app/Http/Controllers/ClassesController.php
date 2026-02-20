<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class ClassesController extends Controller
{
    //
    public function index(Request $request) 
    {
        $all_class = ['9','10','11','12'];
        $count = Student::where('status', '1')
                        ->select('class',DB::raw('count(*) as total'))
                        ->groupBy('class')
                        ->pluck('total','class')
                        ->all();
                        
        $classdata =[];
        foreach($all_class as $class)
            {
                $classdata[$class] = $count[$class] ?? 0;

            }
        return view('classes.index',compact('classdata'));                
    }
             
        public function showStudents(Request $request, $class_name) 
        {
            $search = $request->search;
            $students = Student::with('user')
                        ->where('status',1)
                        ->where('class',$class_name)
                       ->whereHas('user', function ($q) {
                // Sirf wahi records dikhao jinka role 'student' ho
                $q->where('role', 'student');
                // $q->where('status', '1');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('dob', 'like', "%$search%");
                    })
                        ->orWhere('class', 'like', "%$search%")
                        ->orWhere('roll_no', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate(5);

        return view('classes.show', compact('students', 'search','class_name'));
        }
}
