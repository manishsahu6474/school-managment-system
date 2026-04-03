<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\TeacherService;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;

class TeacherController extends Controller
{
    public function __construct(protected TeacherService $teacherService) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->teacherService->getTeachersList($request->all());
            $teachers = $data['teachers'];
            $pendingCount = $data['pending_count'];
            $search = $request->search;
            return view('teachers.index', compact('teachers', 'pendingCount', 'search'));
        } catch (\Exception $e) {
            return back()->with('error_msg', $e->getMessage());
        }
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
    public function store(StoreTeacherRequest $request)
    {
        try {
            $this->teacherService->storeTeacher($request->validated());
            return redirect()->route('admin.teachers.index')->with('success', 'New Teacher Added Successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Something Went Wrong: ' . $e->getMessage());
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
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
        try {

            $this->teacherService->updateTeacher($teacher, $request->validated());
            return Redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Something went wrong: ' . $e->getMessage());
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
