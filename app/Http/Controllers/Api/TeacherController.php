<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Exception;

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
        try {
            $search = $request->search;
            $request->validate(['status' => 'nullable|in:active,pending,inactive']);

            $query = Teacher::select('id', 'user_id', 'phone', 'qualification', 'salary', 'joining_date', 'status')
                ->with(['user:id,name', 'subjects:id,subject_name', 'classes:id,class_name'])
                ->latest();

            if ($request->filled('status')) {
                $status = $request->status;
                if ($status === 'pending') {
                    $query->where('status', 0);
                } elseif ($status === 'inactive') {
                    $query->where('status', 2);
                } else {
                    $query->where('status', 1);
                }
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

            return response()->json([
                'status' => 'success',
                'pending_count' => Teacher::where('status', 0)->count(),
                'data' => $teachers,

            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'phone' => 'required|unique:teachers,phone|digits:10',
            'address' => 'nullable|string|min:10|max:500',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $result = $this->teacherService->storeTeacher($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Teacher Data Saved Successfully!',
            ], 201);
        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Teacher $teacher)
    {
        $user = $teacher->user;

        $validator = Validator::make(
            $request->all(),
            [
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email,' . $user->id,
                'phone'         => 'required|digits:10|unique:teachers,phone,' . $teacher->id,
                'qualification' => 'required|string',
                'experience'    => 'required|numeric|min:0|max:30',
                'salary'        => 'required|numeric|min:1000',
                'joining_date' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                    'after_or_equal:' . now()->subMonths(6)->format('Y-m-d'),
                ],
                'address'       => 'nullable|string|min:10|max:500',
                'password'      => 'nullable|min:8',
                'subject_id'    =>  'required|integer',
                'class_id'    =>  'required|integer'
            ]

        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $this->teacherService->updateTeacher($teacher, $request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Teacher Data Updated Successfully!',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
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
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 422);
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
        } catch (Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }
    public function toggleStatus(Request $request, Teacher $teacher)
    {
        if (!$request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }
        return $this->performBulkStatusUpdate([$teacher->id], 1, 'Status Changed Successfully!');
    }
    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' approve ho gaye hain!');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' re-activate ho gaye hain!');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, ' inactive list mein move ho gaye!');
    }

}
