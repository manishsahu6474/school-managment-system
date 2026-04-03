<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;
use App\Services\SubjectService;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $subjectService) {}
    public function index()
    {
        try {
            $subjects = $this->subjectService->getAllSubject();

            return response()->json([
                'status' => 'success',
                'data' => $subjects
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went Wrong: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject_name' =>  'required|regex:/^[a-zA-Z\s]+$/|max:100|unique:subjects,subject_name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'error' => $validator->errors()
            ], 422);
        }
        try {
            $this->subjectService->createSubject($request->all());
            return response()->json([
                'status' => 'success',
                'message' => 'Subject successfully Added!'
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Something went Wrong: ' . $e->getMessage()], 500);
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
            return response()->json(['status' => 'error', 'message' => 'Something went Wrong: ' . $e->getMessage()], 500);
        }
    }
}
