<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Services\SubjectService;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $subjectService) {}
    public function index()
    {
        try {
            $subjects = $this->subjectService->getAllSubject();

            return view('subjects.index', compact('subjects'));
        } catch (\Exception $e) {
            return back()->with('error_msg', 'Something went Wrong: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100|unique:subjects,subject_name',
        ]);
        try {
            $this->subjectService->createSubject($request->only('subject_name'));
            return response()->json([
                'status' => 'success',
                'message' => 'Subject successfully Added!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Subject $subject)
    {
        try {

            $this->subjectService->deleteSubject($subject);
            return response()->json([
                'status' => 'success',
                'message' => 'Subject deleted Successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
