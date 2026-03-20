<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function toggleStatus(Request $request, Teacher $teacher)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }
        try {
            DB::beginTransaction();

            if ($teacher->status != 2) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Teacher already Activated/Pending.'
                ]);
            }

            $teacher->update(['status' => 1]);
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher Activated  successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something wents wrong: ' . $e->getMessage()
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

        $query = Teacher::select('id', 'user_id', 'phone', 'qualification', 'salary', 'joining_date', 'status')
            ->with(['user:id,name', 'subjects:id,subject_name', 'classes:id,class_name'])
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

                    ->orWhereHas('subjects', function ($s) use ($search) {
                        $s->where('subject_name', 'like', "%$search%");
                    });
            });
        }

        $teachers = $query->paginate(10);
        $pendingCount = Teacher::where('status', 0)->count();

        return view('teachers.index', compact('teachers', 'search', 'pendingCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teacher = new Teacher();
        $subjects = Subject::select('id', 'subject_name')->get();
        $classes = Classes::select('id', 'class_name')->get();
        return view('teachers.create', compact('teacher', 'subjects', 'classes'));
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
                'joining_date' => 'required|date',
                'qualification' => 'required',
                'experience' => 'nullable|numeric',
                'salary' => 'nullable|numeric',
                'gender' => 'required',
                'phone' => 'required|digits:10',
                'address' => 'nullable|string',
                'password' => 'required|min:8',
            ]
        );
        DB::beginTransaction();
        try {
            $user = User::create($request->only(['name', 'email']) + [
                'password' => Hash::make($request->password),
                'role' => 'teacher',
            ]);

            $teacher = $user->teacher()->create($request->only([
                'joining_date',
                'qualification',
                'experience',
                'salary',
                'gender',
                'phone',
                'address'
            ]) + ['status' => 0]);

            if ($request->subject_id && $request->class_id) {
                $teacher->subjects()->attach($request->subject_id, [
                    'class_id' => $request->class_id
                ]);
            }

            DB::commit();
            return redirect()->route('admin.teachers.index')->with('success', 'New Teacher Added Successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Teacher add nahi ho paya: ' . $e->getMessage());
        }
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
    public function edit(Teacher $teacher)
    {


        $subjects = Subject::select('id', 'subject_name')->get();
        $classes = Classes::select('id', 'class_name')->get();
        return view('teachers.edit', compact('teacher', 'subjects', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $user = $teacher->user;

        $request->validate(
            [
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email,' . $user->id,
                'phone'         => 'required|digits:10',
                'qualification' => 'required',
                'experience'    => 'nullable|numeric|min:0',
                'salary'        => 'nullable|numeric|min:0',
                'joining_date'  => 'required|date',
                'address'       => 'nullable|string|max:500',
                'password'      => 'nullable|min:8',
                'subject_id'    =>  'required',
                'class_id'    =>  'required'
            ]

        );
        DB::beginTransaction();
        try {
            $user->update($request->only(['name', 'email']));

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }
            $teacher->update($request->only([
                'phone',
                'qualification',
                'experience',
                'salary',
                'joining_date',
                'address',
                'gender',
            ]));
            if ($request->subject_id && $request->class_id) {
                $teacher->subjects()->syncWithPivotValues($request->subject_id, [
                    'class_id' => $request->class_id
                ]);
            }
            DB::commit();
            return Redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Teacher Update nahi ho paya: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Teacher $teacher)
    {
        DB::beginTransaction();
        try {
            if ($teacher->status == 1) {
                $teacher->update(['status' => 2]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Teacher successfully moved to Inactive list!'
                ]);
            }
            if ($teacher->status == 0) {
                if ($teacher->user) {
                    $teacher->user->delete();
                }
                $teacher->delete();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Teacher registration rejected and permanently deleted!'
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {
        if (empty($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Pehle Teachers select karein!'], 400);
        }

        DB::beginTransaction();

        try {

            Teacher::whereIn('id', $ids)->update(['status' => $newStatus]);

            DB::commit();
            return response()->json(['status' => 'success', 'message' => $successMsg]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }
    public function approve(Teacher $teacher)
    {
        return $this->performBulkStatusUpdate([$teacher->id], 1, 'Teacher Joining Approved Successfully!');
    }
    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'Selected Teachers approve ho gaye hain!');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'Selected Teachers re-activate ho gaye hain!');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, 'Selected Teachers inactive list mein move ho gaye!');
    }

    public function bulkDelete(Request $request)
    {
        if (empty($request->ids)) {
            return response()->json(['status' => 'error', 'message' => 'Selection khali hai!'], 400);
        }

        DB::beginTransaction();
        try {
            $userIds = Teacher::whereIn('id', $request->ids)->pluck('user_id')->toArray();
            if (!empty($userIds)) {
                User::whereIn('id', $userIds)->delete();
            }

            DB::commit();
            return response()->json(['status' => 'success', 'message' => 'Selected records delete kar diye gaye!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 500);
        }
    }
}
