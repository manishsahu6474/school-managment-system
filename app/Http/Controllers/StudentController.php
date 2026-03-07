<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public function toggleStatus(Request $request, $id)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }

        try {
            DB::beginTransaction();

            $student = Student::findOrFail($id);

            // Check: Sirf Inactive (2) student ko hi Active (1) kar sakte hain
            if ($student->status != 2) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Student pehle se hi Active ya Pending hai.'
                ]);
            }

            // Status Update
            $student->status = 1; // 1 = Active
            $student->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Student successfully activate kar diya gaya hai!'
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Kuch galat hua: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->get('status');

        $query = Student::with('user')->whereHas('user', function ($q) {
            $q->where('role', 'student');
        });

        // Filtering Logic
        if ($status === 'pending') {
            $query->where('status', 0); // 0 = Pending
        } elseif ($status === 'inactive') {
            $query->where('status', 2); // 2 = Inactive
        } else {
            $query->where('status', 1); // 1 = Active (Default)
        }

        // Search Logic (Pahle jaisa hi)
        $query->when($search, function ($q) use ($search) {
            $q->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('user', function ($u) use ($search) {
                    $u->where('name', 'like', "%$search%")
                        ->orWhere('class', 'like', "%$search%");
                })
                    ->orWhere('dob', 'like', "%$search%")
                    ->orWhere('roll_no', 'like', "%$search%");
            });
        });

        $students = $query->latest()->paginate(5);

        // Counts update karein (Taaki tabs pe sahi number dikhe)
        $pendingCount = Student::where('status', 0)->count();
        $activeCount = Student::where('status', 1)->count();
        $totalStudents = Student::count();

        return view('students.index', compact('students', 'search', 'pendingCount', 'activeCount', 'totalStudents'));
    }
    // Admission Approve karne ka naya function
    public function approve($id)
    {
        $student = Student::findOrFail($id);
        $student->update(['status' => '1']); // Pending (0) se Active (1) kar diya

        return redirect()->back()->with('success', 'Student Admission Approved Successfully!');
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


        if ($student->status == 1) {
            $student->status = 2;
            $student->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Student Successfully Inactivated!'
            ]);
        }

        if ($student->status == 0) {
            try {
                DB::beginTransaction();
                if ($student->user) {
                    $student->user->delete();
                }
                $student->delete();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pending request rejected and successfully Deleted!'
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong: ' . $e->getMessage()
                ], 500);
            }
        }
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid Action Performed!'
        ], 403);
    }
    public function bulkPromote(Request $request)
    {
        $ids = $request->ids;
        // Agar 12th pass kar chuke hain toh Inactive kar do
        Student::whereIn('id', $ids)
            ->where('class', '>=', 12)
            ->update(['status' => 0]);

        Student::whereIn('id', $ids)
            ->where('class', '<', 12)
            ->increment('class');
        return response()->json(['status' => 'success', 'message' => 'students, promoted in next class']);
    }
}
