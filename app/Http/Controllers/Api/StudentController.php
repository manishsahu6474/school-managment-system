<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Services\StudentService;

use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
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

            $query = Student::select('id', 'user_id', 'class_id', 'father_name', 'roll_no', 'phone', 'dob', 'status')
                ->with(['user:id,name', 'classes:id,class_name'])
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
                        ->orWhere('roll_no', 'like', "%$search%")
                        ->orWhereHas('classes', function ($c) use ($search) {
                            $c->where('class_name', 'like', "%$search%");
                        });
                });
            }

            $students = $query->paginate(10);
            return response()->json([
                'status' => 'success',
                'total_pending' => Student::where('status', 0)->count(),
                'data' => $students
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
        $request->merge(['roll_no' => Student::formatRollno($request->roll_no)]);

        $validator = Validator::make(
            $request->all(),
            [
                'name'  => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                'father_name' => 'nullable|string|max:100',
                'roll_no'  => 'nullable|string|max:10|unique:students,roll_no',
                'dob' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d'), 'after:' . now()->subYears(20)->format('Y-m-d')],
                'class_id' => 'required|exists:classes,id',
                'phone' => 'required|digits:10',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        try {

            $user = $this->studentService->storeStudent($request->all());

            return response()->json([
                'status' => 'success',
                'message' => 'Data Saved Successfully!',
                'student_id' => $user->student->id
            ], 201);
        } catch (\Exception $e) {

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
    public function show(Request $request) {}


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $request->merge(['roll_no' => Student::formatRollno($request->roll_no)]);
        $user = $student->user;
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'father_name' => 'nullable|string|max:100',
                'roll_no' => 'required|string|max:10|unique:students,roll_no,' . $student->id,
                'dob' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d'), 'after:' . now()->subYears(20)->format('Y-m-d')],
                'class_id' => 'required|exists:classes,id',
                'phone' => 'nullable|digits:10',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $this->studentService->updateStudent($student, $request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Student Data Updated Successfully!',
                'student_id' => $student->id
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {

        try {
            $result = $this->studentService->smartDelete($student);
            return response()->json([
                'status' => 'success',
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
    public function toggleStatus(Request $request, Student $student)
    {
        if (!$request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }

        return $this->performBulkStatusUpdate([$student->id], 1, 'Status Changed Successfully!');
    }
    public function bulkPromote(Request $request)
    {
        try {
            $result = $this->studentService->bulkPromote($request->ids);
            return response()->json(
                [
                    'status' => 'success',
                    'message' => $result['count'] . ' ' . Str::plural('Student', $result['count']) . ' promoted!'
                ]
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $e->getMessage()
                ],
                422
            );
        }
    }
    public function bulkDelete(Request $request)
    {

        try {
            $result = $this->studentService->bulkDelete(Student::class, $request->ids);

            if ($result['count'] == 0) {
                return response()->json(['status' => 'info', 'message' => 'No pending records found to delete.'], 200);
            }
            $entity = Str::plural('Student', $result['count']);
            return response()->json(['status' => 'success', 'message' => "{$result['count']} Pending {$entity} deleted permanently."], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 422);
        }
    }
    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {

        try {

            $result = $this->studentService->bulkStatusUpdate(Student::class, (array)$ids, $newStatus);

            $updatedCount = $result['count'];
            $hasPending   = $result['hasPending'];
            $hasInactive  = $result['hasInactive'];
            if ($updatedCount == 0) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No changes were made. Selected records are already in the target state.'
                ], 200);
            }
            $finalMsg = $successMsg;

            if ($newStatus == 1) {
                if ($hasInactive && $hasPending) {
                    $finalMsg = "Processed (Approved & Re-activated) successfully!";
                } elseif ($hasInactive) {
                    $finalMsg = "Re-activated successfully!";
                } else {
                    $finalMsg = "Approved successfully!";
                }
            }

            $label = ($updatedCount > 1) ? ' Students ' : 'Student';

            return response()->json([
                'status' => 'success',
                'message' => "{$updatedCount} {$label} {$finalMsg}"
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }

    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' processed successfully');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' re-activated successfully');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, ' moved to inactive list successfully');
    }
}
