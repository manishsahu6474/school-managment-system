<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count();
        $totalTeachers = User::Where('role', 'teacher')->count();
        $totalClasses  = Classes::count();
        $totalSubjects = Subject::count();
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

    public function showprofile()
    {
        return view('admin.myprofile');
    }
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'password' => 'nullable|min:8|confirmed'

        ]);
        DB::beginTransaction();
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if($request->hasFile('profile_image'))
                {
                    if($user->profile_image)
                        {
                            Storage::disk('public')->delete($user->profile_image);

                        }
                        $filename = str_replace(' ', '_', strtolower($user->name)) .'_profile_'. time(). '.'. $request->profile_image->extension();
                        $user->profile_image =$request->file('profile_image')->storeAs('profiles',$filename,'public');
                }
                $user->save();
                DB::commit();
                return back()->with('success','Profile updated Successfully!');
        } catch(\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Update fail ho gaya: ' . $e->getMessage());
        }
    }
}
