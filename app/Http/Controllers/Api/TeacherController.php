<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Services\TeacherService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Traits\HandlesBulkActions;
use App\Http\Resources\TeacherResource;
use Exception;

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
            $request->validate(['status' => 'nullable|in:active,pending,inactive']);
            $data = $this->service->getTeachersList($request->all());

            return TeacherResource::collection($data['teachers'])
                ->additional([
                    'status' => 'success',
                    'search' => $request->search,
                    'pending_count' => $data['pending_count']
                ]);
        } catch (Exception $e) {
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
            $teacher = $this->service->storeTeacher($request->validated());
            return (new TeacherResource($teacher))
                ->additional([
                    'status' => 'success',
                    'message' => 'Teacher Data Saved Successfully!',
                ])->response()->setStatusCode(201);
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
            $result = $this->service->updateTeacher($teacher, $request->validated());
            return (new TeacherResource($teacher))
                ->additional([
                    'status' => 'success',
                    'message' => 'Teacher Data Updated Successfully!',
                ])->response()->setStatusCode(200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
