<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public function toggleStatus(Request $request, Student $student)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }

        try {
            DB::beginTransaction();

            if ($student->status != 2) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Student pehle se hi Active ya Pending hai.'
                ]);
            }

            $student->update(['status' => 1]);

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
        $query = Student::select('id', 'user_id', 'class_id', 'father_name', 'roll_no', 'phone', 'dob', 'status')
        ->with(['user:id,name', 'Classes:id,class_name'])
        ->latest();

        if ($status === 'pending') {
            $query->where('status', 0);
        } elseif ($status === 'inactive') {
            $query->where('status', 2);
        } else {
            $query->where('status', 1);
        }

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

        $students = $query->paginate(10);

        $pendingCount = Student::where('status', 0)->count();

        return view('students.index', compact('students', 'search', 'pendingCount'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $student = new Student();
        $classes = Classes::select('id', 'class_name')->get();
        return view('students.create', compact('student', 'classes'));
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
            'class_id' => 'required|exists:classes,id',
            'phone' => 'required|digits:10',
        ]);
        try {
            DB::beginTransaction();
            $userData  = $request->only(['name', 'email']);
            $user = User::create($userData + [
                'password' => Hash::make('student123'),
                'role' => 'student',
            ]);

            $user->student()->create($request->only(
                [
                    'dob',
                    'class_id',
                    'phone',
                    'roll_no',
                    'father_name'
                ]
            ) + ['status' => '0']);

            DB::commit();

            return redirect()->route('admin.students.index')
                ->with('success', 'Student Added Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error_msg', 'Update fail ho gaya: ' . $e->getMessage());
        }
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
    public function edit(Student $student)
    {
        $classes = Classes::select('id', 'class_name')->get();
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $user = $student->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'father_name' => 'nullable|string|max:100',
            'roll_no' => 'nullable|unique:students,roll_no,' . $student->id,
            'dob'   => 'required|date',
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|digits:10',
        ]);
        try {

            DB::beginTransaction();
            $user->update($request->only([
                'name',
                'email'
            ]));

            $student->update($request->only([
                'roll_no',
                'class_id',
                'father_name',
                'phone',
                'dob'
            ]));

            DB::commit();
            return redirect()->route('admin.students.index')
                ->with('success', 'Student profile updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error_msg', 'Update fail ho gaya: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {

        DB::beginTransaction();

        try {
            if ($student->status == 1) {
                $student->update(['status' => 2]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Student Successfully Inactivated!'
                ]);
            }

            if ($student->status == 0) {
                if ($student->user) {
                    $student->user->delete();
                }
                $student->delete();

                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pending request rejected and successfully Deleted!'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Student is already Inactive or Invalid Status!'
            ], 403);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkPromote(Request $request)
    {
        if (empty($request->ids)) return response()->json(['status' => 'error', 'message' => 'Select students!'], 400);

        DB::beginTransaction();
        try {
            $ids = $request->ids;
            Student::whereIn('id', $ids)->where('class_id', 4)->update(['status' => 2]);
            Student::whereIn('id', $ids)->where('class_id', '<', 4)->increment('class_id');

            DB::commit();
            return response()->json(['status' => 'success', 'message' => count($ids) . ' Students promoted successfully!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Promotion failed.'], 500);
        }
    }

    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {
        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Pehle students select karein!'], 400);
        }

        DB::beginTransaction();

        try {

            $query = Student::whereIn('id', $ids)->update(['status' => $newStatus]);
            
            DB::commit();
            return response()->json(['status' => 'success', 'message' => $successMsg]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }
    public function approve(Student $student)
    {
        return $this->performBulkStatusUpdate([$student->id], 1, 'Student Admission Approved Successfully!');
    }


    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'Selected students approve ho gaye hain!');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'Selected students re-activate ho gaye hain!');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, 'Selected students inactive list mein move ho gaye!');
    }

    public function bulkDelete(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'message' => 'Selection khali hai!'], 400);
        }

        DB::beginTransaction();
        try {
            $userIds = Student::whereIn('id', $request->ids)->pluck('user_id');
            User::whereIn('id', $userIds)->delete();
            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Selected records delete kar diye gaye!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 500);
        }
    }
}
