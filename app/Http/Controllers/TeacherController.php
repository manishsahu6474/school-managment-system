<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->search;
        $teachers = User::where('role','teacher')
        ->when($search, function ($query) use ($search) {
                 $query->where(function($subQuery) use ($search){
                 $subQuery->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
                 });
        })
        ->latest()
        ->paginate(5);

        return view('teachers.index', compact('teachers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('teachers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password'=>'required|min:8', 
            ]
        );
        User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password'=> Hash::make($request->password),
                'role' => 'teacher',
        ]);
        return redirect()->route('admin.teachers.index')->with('success','New Teacher Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $teacher = User::findorfail($id);
        
        return view('teachers.edit', compact('teacher'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // 
        $teacher = User::findorfail($id);
        $request->validate(
            [ 'name'=>'required',
              'email'=>'required|email|unique:users,email,'.$id,
            ]

        );
        $teacher->update([
            'name'=>$request->name,
            'email'=>$request->email,
        ]);
        return Redirect()->route('admin.teachers.index')
        ->with('success','Teacher updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $teacher)
    {
        //
        $teacher->delete();
        return redirect()->route('admin.teachers.index')
         ->with('success','Teacher Deleted Successdfully!');

    }
}
