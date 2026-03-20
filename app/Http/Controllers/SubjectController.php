<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::select('id', 'subject_name')
            ->with('classes:id,class_name')->latest()->paginate(10);
        return view('subjects.index', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:100|unique:subjects,subject_name',
        ]);
        DB::beginTransaction();
        try {
            Subject::create($request->only('subject_name'));
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Subject successfully kar diya gaya!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Subject add fail ho gaya: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Subject $subject)
    {
        DB::beginTransaction();
        try {

            if ($subject->teachers()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ye subject delete nahi ho sakta kyunki ye kisi Teacher ko assigned hai!'
                ], 422);
            }

            $subject->delete();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Subject delete kar diya gaya!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Deletion fail ho gaya: ' . $e->getMessage()
            ], 500);
        }
    }
}
