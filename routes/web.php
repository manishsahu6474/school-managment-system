<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\SubjectController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('welcome');
})->middleware('prevent-back');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'prevent-back'])
    ->name('dashboard');
    
Route::middleware(['auth','isAdmin', 'verified', 'prevent-back'])
    ->prefix('admin')    
    ->name('admin.')        
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('profile', [AdminController::class, 'showprofile'])->name('profile');
        Route::post('update-profile', [AdminController::class, 'update'])->name('update-profile');
        Route::prefix('students')->name('students.')->group(function () {

            Route::post('/bulk-approve', [StudentController::class, 'bulkApprove'])->name('bulkApprove');
            Route::post('/bulk-activate', [StudentController::class, 'bulkActivate'])->name('bulkActivate');
            Route::post('/bulk-inactivate', [StudentController::class, 'bulkInactivate'])->name('bulkInactivate');
            Route::post('/bulk-promote', [StudentController::class, 'bulkPromote'])->name('bulkPromote');
            Route::post('/bulk-delete', [StudentController::class, 'bulkDelete'])->name('bulkDelete');

            // --- Individual Actions ---
            Route::post('/{student}/approve', [StudentController::class, 'approve'])->name('approve');
            Route::post('/status/{student}/toggel-status', [StudentController::class, 'toggleStatus'])->name('toggle-status');
        });
        Route::resource('students', StudentController::class);

        Route::prefix('teachers')->name('teachers.')->group(function () {
            Route::post('/bulk-activate', [TeacherController::class, 'bulkActivate'])->name('bulkActivate');
            Route::post('/bulk-approve', [TeacherController::class, 'bulkApprove'])->name('bulkApprove');
            Route::post('/bulk-inactivate', [TeacherController::class, 'bulkInactivate'])->name('bulkInactivate');
            Route::post('/bulk-delete', [TeacherController::class, 'bulkDelete'])->name('bulkDelete');
            Route::post('/{teacher}/approve', [TeacherController::class, 'approve'])->name('approve');
            Route::post('/status/{teacher}', [TeacherController::class, 'toggleStatus'])->name('status');
        });

        Route::resource('teachers', TeacherController::class);
        Route::get('classes/{classes}', [ClassesController::class, 'showStudents'])->name('classes.students');
        Route::resource('classes', ClassesController::class);
        Route::resource('subjects', SubjectController::class);
    });
require __DIR__ . '/auth.php';
