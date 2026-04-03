<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateStudentRequest; 
use App\Models\Student;
use Illuminate\Support\Str;
use App\Services\StudentService;
use App\Http\Requests\StoreStudentRequest;


class StudentController extends Controller
{
    public function __construct(protected StudentService $studentService) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $request->validate(['status' => 'nullable|in:active,pending,inactive']);
            $data = $this->studentService->getStudentsList($request->all());
            return response()->json([
                'status' => 'success',
                'search' => $request->search,
                'total_pending' => $data['pending_count'],
                'data' => $data['students']
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
    public function store(StoreStudentRequest $request)
    {
        try {
            $user = $this->studentService->storeStudent($request->validated());
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
    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            $this->studentService->updateStudent($student, $request->validated());
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
