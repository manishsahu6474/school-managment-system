<?php

namespace App\Http\Controllers;

use App\Models\teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{   
     public function toggleStatus($id)
    {
        $teacher = Teacher::findOrFail($id);

        // Status toggle logic
        $newStatus = ($teacher->status == 1) ? 0 : 1;
        $teacher->update(['status' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'newStatus' => $newStatus,
            'message' => 'Teacher status updated successfully!'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = $request->search;
        $teachers  = Teacher::with('user')
            ->whereHas('user', function ($q) {
                // Sirf wahi records dikhao jinka role 'teacher' ho
                $q->where('role', 'teacher');
                // $q->where('status', '1');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('subject', 'like', "%$search%");
                    })
                        ->orWhere('gender', 'like', "%$search%")
                        ->orWhere('address', 'like', "%$search%");
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
                'password' => 'required|min:8',
            ]
        );
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);
        return redirect()->route('admin.teachers.index')->with('success', 'New Teacher Added Successfully!');
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
        $teacher = Teacher::with('user')->findorfail($id);

        return view('teachers.edit', compact('teacher'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        $teacher = Teacher::findorfail($id);
        $user = $teacher->user;

        $request->validate(
            [
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email,' . $user->id,
                'phone'         => 'required|digits:10',
                'subject'       => 'required',
                'qualification' => 'required',
                'experience'    => 'nullable|numeric|min:0',
                'salary'        => 'nullable|numeric|min:0',
                'joining_date'  => 'required|date',
                'address'       => 'nullable|string|max:500',
                'password'      => 'nullable|min:8',
            ]

        );
        DB::transaction(function () use ($request, $user, $teacher) {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,

            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);
            $teacher->update([
                'phone'         => $request->phone,
                'subject'       => $request->subject,
                'qualification' => $request->qualification,
                'experience'    => $request->experience,
                'salary'        => $request->salary,
                'joining_date'  => $request->joining_date,
                'address'       => $request->address,
                'gender'        => $request->gender,
            ]);
        });
        return Redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $teacher = Teacher::findorFail($id);
        if ($teacher->status == 0) {
            return response()->json([
                'status' => 'info',
                'message' => 'Yeh Teacher pehle se hi Inactive hai!'
            ]);
        }
        $teacher->update(['status' => '0']);

        return response()->json([
            'status' => 'success',
            'message' => 'Teacher status updated successfully!',
            'newStatus' => 0
        ]);
    
        
    }
}
