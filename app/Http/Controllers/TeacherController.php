<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use App\Models\Classes;
use Illuminate\Http\Request;
use App\Services\TeacherService;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Traits\HandlesBulkActions;

class TeacherController extends Controller
{
    use HandlesBulkActions;

    protected string $resourceLabel = 'Teacher';
    protected string $model = Teacher::class;
    public function __construct(protected TeacherService $service) {}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->service->getTeachersList($request->all());
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
            $this->service->storeTeacher($request->validated());
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

            $this->service->updateTeacher($teacher, $request->validated());
            return Redirect()->route('admin.teachers.index')
                ->with('success', 'Teacher updated successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error_msg', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
