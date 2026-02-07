<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $students = Student::when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('class', 'like', "%$search%");
        })
        ->latest()
        ->paginate(5);

        return view('students.index', compact('students', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('students.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        'name'  => 'required|string|max:100',
        'email' => 'required|email|unique:students,email',
        'dob'   => 'required|date',
        'class' => 'required',
        'phone' => 'required|nullable|digits:10',
    ]);

    Student::create($request->all());

    return redirect()->route('students.index')
           ->with('success','Student Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Student ka data ID ke basis par find karein
        $student = Student::findOrFail($id);
        
        // Data ko edit view file ke sath bhejein
        return view('students.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:students,email,'.$id,
        'phone' => 'nullable|digits:10',
        'dob'   => 'required|date',
        'class' => 'required'
    ]);

    $student = Student::findOrFail($id);
    $student->update($request->all());

    return redirect()->route('students.index')
           ->with('success','Student Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
        // Student ko delete karein
    $student->delete();

    // Success message ke sath wapas bhejein
    return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
    }
}
