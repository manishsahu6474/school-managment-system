<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Services\StudentService;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Resources\StudentResource;
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
        try {
            $request->validate(['status' => 'nullable|in:active,pending,inactive']);
            $data = $this->service->getStudentsList($request->all());
            return StudentResource::collection($data['students'])
                ->additional([
                    'status' => 'success',
                    'search' => $request->search,
                    'total_pending' => $data['pending_count']
                ]);
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
            $user = $this->service->storeStudent($request->validated());
            return (new StudentResource($user->student))
                ->additional([
                    'status' => 'success',
                    'message' => 'Data Saved Successfully!'
                ])->response()->setStatusCode(201);
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
            $this->service->updateStudent($student, $request->validated());
            return (new StudentResource($student))
                ->additional([
                    'status' => 'success',
                    'message' => 'Student Data Updated Successfully!',
                ])->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
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
