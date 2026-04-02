<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\TeacherService;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
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
                    })->orWhereHas('classes', function ($c) use ($search) {
                        $c->where('class_name', 'like', "%$search%");
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

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'joining_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                    'after_or_equal:' . now()->subMonths(6)->format('Y-m-d'),
                ],
                'qualification' => 'required|string',
                'experience' => 'required|numeric|min:0|max:30',
                'salary' => 'required|numeric|min:1000',
                'gender' => 'required|in:male,female,other',
                'phone' => 'required|digits:10',
                'address' => 'nullable|string|min:10|max:500',
                'password' => 'required|min:8',
            ]
        );
        try {
            $teacher = $this->teacherService->storeTeacher($request->all());
            return redirect()->route('admin.teachers.index')->with('success', 'New Teacher Added Successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something Went Wrong: ' . $e->getMessage());
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
                'qualification' => 'required|string',
                'experience'    => 'required|numeric|min:0|max:30',
                'salary'        => 'required|numeric|min:1000',
                'joining_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                    'after_or_equal:' . now()->subMonths(6)->format('Y-m-d'),
                ],
                'address'       => 'nullable|string|max:500',
                'password'      => 'nullable|min:8',
                'subject_id'    =>  'required|integer',
                'class_id'    =>  'required|integer'
            ]

        );
        try {

            $this->teacherService->updateTeacher($teacher, $request->all());

            return Redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Something went wrong: ' . $e->getMessage());
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
        try {
            $result = $this->teacherService->smartDelete($teacher);
            return response()->json([
                'status' => 'success',
                'message' => $result['message']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {
        try {

            $result = $this->teacherService->bulkStatusUpdate(Teacher::class, (array)$ids, $newStatus);
            $updateCount = $result['count'];
            $hasInactive = $result['hasInactive'];
            $hasPending = $result['hasPending'];
            if ($updateCount == 0) {
                return response()->json(['status' => 'info', 'message' => 'No Data has been updated, already in targeted state!']);
            }

            $finalMsg = $successMsg;
            if ($newStatus == 1) {
                if ($hasInactive && $hasPending) {
                    $finalMsg = "Processed (Approved & Re-activated) successfully!";
                } elseif ($hasInactive) {
                    $finalMsg = "Re-activated successfully!";
                } elseif ($hasPending) {
                    $finalMsg = "Approved successfully!";
                }
            }

            $label = ($updateCount > 1) ? 'Teachers' : 'Teacher';

            return response()->json([
                'status'  => 'success',
                'message' => "{$updateCount} {$label} {$finalMsg}"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }
    public function toggleStatus(Request $request, Teacher $teacher)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }
        return $this->performBulkStatusUpdate([$teacher->id], 1, 'Status Changed Successfully!');
    }


    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'approve ho gaye hain!');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' re-activate ho gaye hain!');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, ' inactive list mein move ho gaye!');
    }

    public function bulkDelete(Request $request)
    {
        try {
            $result = $this->teacherService->bulkDelete(Teacher::class, $request->ids);
            if ($result['count'] == 0) {
                return response()->json(['status' => 'info', 'message' => 'No pending records found to delete.'], 200);
            }

            $entity = Str::plural('Teacher', $result['count']);
            return response()->json(['status' => 'success', 'message' => "{$result['count']} Pending {$entity} deleted permanently."], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 422);
        }
    }
}
