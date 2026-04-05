<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Str;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Traits\HandlesBulkActions;

class StudentController extends Controller
{
    use HandlesBulkActions;
    protected string $resourceLabel = 'Student';
    protected string $model = Student::class;
    public function __construct(protected StudentService $service) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $request->validate(['status' => 'nullable|in:active,pending,inactive']);
        $data = $this->service->getStudentsList($request->all());
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
    public function store(StoreStudentRequest $request)
    {
        try {

            $this->service->storeStudent($request->validated());
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
    public function update(UpdateStudentRequest $request, Student $student)
    {

        try {
            $this->service->updateStudent($student, $request->validated());
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
    
    public function bulkPromote(Request $request)
    {

        try {
            $result = $this->service->bulkPromote($request->ids);
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
}
