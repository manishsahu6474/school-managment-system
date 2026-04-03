<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Str;

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
        $search = $request->search;
        $request->validate(['status' => 'nullable|in:active,pending,inactive']);
        $data = $this->studentService->getStudentsList($request->all());
        $students = $data['students'];
        $pendingCount = $data['pending_count'];
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
        $request->merge(['roll_no' => Student::formatRollno($request->roll_no)]);
        $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'father_name' => 'required|string|max:100',
            'roll_no'  => 'nullable|string|max:10|unique:students,roll_no',
            'dob' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d'), 'after:' . now()->subYears(20)->format('Y-m-d')],
            'class_id' => 'required|exists:classes,id',
            'phone' => 'required|digits:10',
        ]);

        try {

            $this->studentService->storeStudent($request->all());
            return redirect()->route('admin.students.index')
                ->with('success', 'Student Added Successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Something went wrong! : ' . $e->getMessage());
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
        $request->merge(['roll_no' => Student::formatRollno($request->roll_no)]);
        $user = $student->user;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'father_name' => 'required|string|max:100',
            'roll_no' => 'required|string|max:10|unique:students,roll_no,' . $student->id,
            'dob' => ['required', 'date', 'before:' . now()->subYears(5)->format('Y-m-d'), 'after:' . now()->subYears(20)->format('Y-m-d')],
            'class_id' => 'required|exists:classes,id',
            'phone' => 'required|digits:10',
        ]);
        try {

            $this->studentService->updateStudent($student, $request->all());
            return redirect()->route('admin.students.index')
                ->with('success', 'Student profile updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Something went wrong!: ' . $e->getMessage());
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

    private function performBulkStatusUpdate($ids, $newStatus, $successMsg)
    {

        try {

            $result = $this->studentService->bulkStatusUpdate(Student::class, (array)$ids, $newStatus);

            $updatedCount = $result['count'];
            $hasPending   = $result['hasPending'];
            $hasInactive  = $result['hasInactive'];
            if ($updatedCount == 0) {
                return response()->json([
                    'status'  => 'info',
                    'message' => 'No changes made. Records are already in the target state.'
                ], 200);
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

            $label = ($updatedCount > 1) ? 'Students' : 'Student';

            return response()->json([
                'status'  => 'success',
                'message' => "{$updatedCount} {$label} {$finalMsg}"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function toggleStatus(Request $request, Student $student)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Invalid Request'], 400);
        }
        return $this->performBulkStatusUpdate([$student->id], 1, 'Status Changed Successfully!');
    }
    public function bulkApprove(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' Processed Successfully!');
    }

    public function bulkActivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 1, ' Re-activate successfully!');
    }

    public function bulkInactivate(Request $request)
    {
        return $this->performBulkStatusUpdate($request->ids, 2, ' moved to inactive list successfully!');
    }

    public function bulkDelete(Request $request)
    {

        try {
            $result = $this->studentService->BulkDelete(Student::class, $request->ids);

            if ($result['count'] == 0) {
                return response()->json(['status' => 'info', 'message' => 'No pending records found to delete.'], 200);
            }

            $entity = Str::plural('Student', $result['count']);
            return response()->json(['status' => 'success', 'message' => "{$result['count']} Pending {$entity} deleted permanently."], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
}
