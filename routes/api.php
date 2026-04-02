<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum', 'isAdmin')->group(function () {

    Route::get('/user-profile', function (Request $request) {

        return $request->user();
    });
    Route::prefix('students')->group(function () {
        // 1. Bulk Operations 
        Route::post('bulk-approve', [StudentController::class, 'bulkApprove']);
        Route::post('bulk-activate', [StudentController::class, 'bulkActivate']);
        Route::post('bulk-inactivate', [StudentController::class, 'bulkInactivate']);
        Route::post('bulk-promote', [StudentController::class, 'bulkPromote']);
        Route::delete('bulk-delete', [StudentController::class, 'bulkDelete']);

        // 2. Specific Action 
        Route::patch('{student}/toggle-status', [StudentController::class, 'toggleStatus']);
    });
    // 3. Standard API Resource
    Route::apiResource('/students', StudentController::class);

    Route::prefix('teachers')->group(function () {
        // 1. Bulk Operations 
        Route::post('bulk-approve', [TeacherController::class, 'bulkApprove']);
        Route::post('bulk-activate', [TeacherController::class, 'bulkActivate']);
        Route::post('bulk-inactivate', [TeacherController::class, 'bulkInactivate']);
        Route::delete('bulk-delete', [TeacherController::class, 'bulkDelete']);

        // 2. Specific Action 
        Route::patch('{teacher}/toggle-status', [TeacherController::class, 'toggleStatus']);
    });
    // 3. Standard API Resource
    Route::apiResource('/teachers', TeacherController::class);
});
