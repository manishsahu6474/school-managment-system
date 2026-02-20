<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public function toggleStatus($id)
    {
        $student = Student::findOrFail($id);

        // Status toggle logic
        $newStatus = ($student->status == 1) ? 0 : 1;
        $student->update(['status' => $newStatus]);

        return response()->json([
            'status' => 'success',
            'newStatus' => $newStatus,
            'message' => 'Student status updated successfully!'
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $students = Student::with('user')
            ->whereHas('user', function ($q) {
                // Sirf wahi records dikhao jinka role 'student' ho
                $q->where('role', 'student');
                // $q->where('status', '1');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->whereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    })
                        ->orWhere('class', 'like', "%$search%")
                        ->orWhere('roll_no', 'like', "%$search%");
                });
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
        $student = new Student();

        return view('students.create', compact('student'));
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
            'email' => 'required|email|unique:users,email',
            'father_name' => 'nullable|string|max:100',
            'roll_no'  => 'nullable|numeric|unique:students,roll_no',
            'dob'   => 'required|date',
            'class' => 'required',
            'phone' => 'required|digits:10',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('student123'),
                'role' => 'student',
            ]);

            $user->student()->create(
                [
                    'dob' => $request->dob,
                    'class' => $request->class,
                    'phone' => $request->phone,
                    'roll_no' => $request->roll_no,
                    'father_name' => $request->father_name,
                    'status' => '1',
                ]
            );
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Student Added Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Student ka data ID ke basis par find karein
        $student = Student::with('user')->findOrFail($id);

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
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'father_name' => 'nullable|string|max:100',
            'roll_no' => 'nullable|unique:students,roll_no,' . $student->id,
            'dob'   => 'required|date',
            'class' => 'required',
            'phone' => 'nullable|digits:10',
        ]);

        DB::transaction(function () use ($request, $user, $student) {
            // Step A: User Table Update (Name, Email)
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Step B: Student Table Update (Academic Info)
            $student->update([
                'roll_no' => $request->roll_no,
                'class' => $request->class,
                'father_name' => $request->father_name,
                'phone' => $request->phone,
                'dob' => $request->dob,
            ]);
        });

        return redirect()->route('admin.students.index')->with('success', 'Student profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $student = Student::findOrFail($id);

        if ($student->status == 0) {
            return response()->json([
                'status' => 'info',
                'message' => 'Yeh student pehle se hi Inactive hai!'
            ]);
        }

        $student->update(['status' => '0']);

        return response()->json([
            'status' => 'success',
            'message' => 'Student status updated successfully!',
            'newStatus' => 0
        ]);
    }
    public function bulkPromote(Request $request)
    {
        $ids = $request->ids;
        // Agar 12th pass kar chuke hain toh Inactive kar do
        Student::whereIn('id', $ids)
            ->where('class' ,'>=', 12)
            ->update(['status' => 0]);

        Student::whereIn('id', $ids)
            ->where('class','<', 12)
            ->increment('class');
        return response()->json(['status' => 'success', 'message' => 'students, promoted in next class']);
    }
}
