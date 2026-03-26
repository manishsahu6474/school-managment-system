<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
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
                ->with(['user:id,name', 'Classes:id,class_name'])
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
                        ->orWhereHas('Classes', function ($c) use ($search) {
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
                'dob'   => 'required|date',
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
        DB::beginTransaction();
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make('password'), // Default password
                'role'     => 'student',
            ]);

            $user->student()->create(
                [
                    'father_name' => $request->father_name,
                    'roll_no'     => $request->roll_no,
                    'dob'         => $request->dob,
                    'class_id'    => $request->class_id,
                    'phone'       => $request->phone,
                    'status'      => 0,
                ]
            );

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Data Saved Successfully!',
                'user_id' => $user->id

            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

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

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'father_name' => 'nullable|string|max:100',
            'roll_no' => 'nullable|string|max:10|unique:students,roll_no,' . $student->id,
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
            return response()->json([
                'status' => 'success',
                'message' => 'Student Data Update Successfully!',
                'student_id' => $student->id

            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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

        DB::beginTransaction();
        try {

            if ($student->status == 1) {
                $student->update(['status' => 2]);
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Student Successfully Inactivated!'
                ]);
            } elseif ($student->status == 0) {
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
                'message' => 'Deletion failed!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function toggleStatus(Request $request, Student $student)
    {
        if (!$request->expectsJson()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }

        try {
            DB::beginTransaction();

            if ($student->status != 2) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'This Student is Already Acitvated or Pending'

                ], 403);
            }

            $student->update(['status' => 1]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Student successfully activated!'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'something wrong: ' . $e->getMessage()
            ], 500);
        }
    }
    public function bulkPromote(Request $request)
    {
        $rawids = $request->ids;
        if (empty($rawids) || !is_array($rawids)) {
            return response()->json(['status' => 'error', 'message' => 'Please select valid ids !'], 400);
        }
        $ids = array_unique($rawids);

        if (count($ids) > 50) {
            return response()->json(['status' => 'error', 'message' => 'Only 50 selection are processed.'], 400);
        }

        $validator = Validator::make(['ids' => $ids], [
            'ids.*' => 'integer|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some ids invalids does not match in database.'
            ], 422);
        }
        DB::beginTransaction();
        try {

            $passoutCount =  Student::whereIn('id', $ids)->where('status', 1)->where('class_id', 4)->update(['status' => 2]);
            $promotedCount =  Student::whereIn('id', $ids)->where('status', 1)->where('class_id', '<', 4)->increment('class_id');

            DB::commit();

            $totalprocessed = $promotedCount + $passoutCount;
            if ($totalprocessed == 0) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Promotion skipped. Please ensure selected students are currently Active.'
                ]);
            }
            $entity = ($totalprocessed > 1) ? 'Students' : 'Student';
            return response()->json([
                'status' => 'success',
                'message' => "{$totalprocessed} {$entity} promoted to the next academic level successfully."
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Promotion failed.'], 500);
        }
    }
    public function bulkDelete(Request $request)
    {
        $rawids = $request->ids;
        if (empty($rawids) || !is_array($rawids)) {
            return response()->json(['status' => 'error', 'message' => 'Please select valid ids !'], 400);
        }
        $ids = array_unique($rawids);

        if (count($ids) > 50) {
            return response()->json(['status' => 'error', 'message' => 'Only 50 selection are processed.'], 400);
        }

        $validator = Validator::make(['ids' => $ids], [
            'ids.*' => 'integer|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some ids invalids does not match in database.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $userIds = Student::whereIn('id', $ids)
                ->where('status', 0)
                ->pluck('user_id');

            $deleteCount = $userIds->count();

            if ($deleteCount == 0) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No pending records found. Active students cannot be deleted permanently.'
                ], 200);
            }

            User::whereIn('id', $userIds)->delete();

            DB::commit();
            $entity = ($deleteCount > 1) ? 'Students' : 'Student';
            return response()->json(['status' => 'success', 'message' => "{$deleteCount} Pending {$entity} deleted permanently."]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Deletion failed. Try again.'], 500);
        }
    }
    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {
        if (empty($ids) || !is_array($ids)) {
            return response()->json(['status' => 'error', 'message' => 'Please select valid ids !'], 400);
        }

        if (count($ids) > 50) {
            return response()->json(['status' => 'error', 'message' => 'Only 50 selection are processed.'], 400);
        }

        $validator = Validator::make(['ids' => $ids], [
            'ids.*' => 'integer|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Some ids invalids does not match in database.'
            ], 422);
        }
        DB::beginTransaction();

        try {


            $query = Student::whereIn('id', $ids)->where('status', '!=', $newStatus);

            if ($newStatus == 1) {
                $query->whereIN('status', [0, 2]);
            } elseif ($newStatus == 2) {
                $query->where('status', 1);
            }

            $hasInactive = (clone $query)->where('status', 2)->exists();
            $hasPending = (clone $query)->where('status', 0)->exists();

            $updatedCount = $query->update(['status' => $newStatus]);
            DB::commit();
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
            return response()->json([
                'status' => 'success',
                'message' => $updatedCount . ' ' . ($updatedCount > 1 ? ' Students ' : 'Student') . ' ' . $finalMsg
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => 'Database error: Action perform nahi ho paya.'], 500);
        }
    }
    public function approve(Student $student)
    {
        return $this->performBulkStatusUpdate([$student->id], 1, 'approved successfully');
    }


    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 'processed successfully');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, 're-activated successfully');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, 'moved to inactive list successfully');
    }
}
