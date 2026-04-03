<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use Illuminate\Support\Str;
use Exception;

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
            $request->validate(['status' => 'nullable|in:active,pending,inactive']);
            $data = $this->teacherService->getTeachersList($request->all());

            return response()->json([
                'status' => 'success',
                'search' => $request->search,
                'pending_count' => $data['pending_count'],
                'data' => $data['teachers'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
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
            $result = $this->teacherService->storeTeacher($request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Teacher Data Saved Successfully!',
                'teacher_id' => $result->id
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
    public function update(UpdateTeacherRequest $request, Teacher $teacher)
    {
       
        try {
           $result = $this->teacherService->updateTeacher($teacher, $request->validated());
            return response()->json([
                'status' => 'success',
                'message' => 'Teacher Data Updated Successfully!',
                'teacher_id' => $result->id
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
