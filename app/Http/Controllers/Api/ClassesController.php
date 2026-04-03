<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Services\ClassesService;

class ClassesController extends Controller
{
    public function __construct(protected ClassesService $classesService) {}
    public function index()
    {
        try {
            $classdata = $this->classesService->getAllClassWithCount();
            return response()->json([
                'status' => 'success',
                'class_data' => $classdata
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Classes can not fetch!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showStudents(Request $request, Classes $classes)
    {
        try {
            $search = $request->search;
            $students = $this->classesService->getStudentsByClass($classes, $request->search);
            return response()->json([
                'status' => 'success',
                'search' => $search,
                'data' => $students
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Students can not fetch : ' . $e->getMessage()
            ], 500);
        }
    }
}
