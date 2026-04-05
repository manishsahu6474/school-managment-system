<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Classes;
use App\Services\ClassesService;
use App\Http\Resources\ClassesResource;
use App\Http\Resources\StudentResource;

class ClassesController extends Controller
{
    public function __construct(protected ClassesService $classesService) {}
    public function index()
    {
        try {
            $classdata = $this->classesService->getAllClassWithCount();
            return ClassesResource::collection($classdata)
                ->additional([
                    'status' => 'success',
                ]);
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
            $students = $this->classesService->getStudentsByClass($classes, $request->search);
            return StudentResource::collection($students)
                ->additional([
                    'status' => 'success',
                    'search' => $request->search,
                    'class_info' => [
                        'id' => $classes->id,
                        'name' => $classes->class_name
                    ]
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Students can not fetch : ' . $e->getMessage()
            ], 500);
        }
    }
}
