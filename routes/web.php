<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;
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
});


Route::get('/dashboard', function () {

    // 1. User ka Role check karo
    $role = Auth::user()->role;

    // 2. Traffic Police Logic (Sabke liye kaam karega: Login & Register)
    if ($role === 'admin') {
        // Agar Admin hai -> Admin Dashboard par bhejo
        return redirect()->route('admin.dashboard');
    } elseif ($role === 'teacher') {
        // Agar Teacher hai -> Teacher Dashboard dikhao
        return view('teachers.dashboard');
    } else {
        // Agar Student hai (Registration ke baad yehi chalega)
        // -> Student Dashboard dikhao
        return view('students.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')       // URL: /admin/students
    ->name('admin.')        // Route Name: admin.students.index
    ->group(function () {

        // 1. Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // 2. RESOURCE ROUTE (Ye hai wo magic line)
        Route::post('students/{id}/approve', [StudentController::class, 'approve'])->name('students.approve');
        // Admin ko Student par pura control de diya
        Route::post('students/status/{id}', [StudentController::class, 'toggleStatus'])->name('students.status');
        // Student ko approve karne ke liye route
        Route::post('students/bulk-promote', [StudentController::class, 'bulkPromote'])->name('students.bulkPromote');
        Route::resource('students', StudentController::class);
        // Admin ko Teacher par pura control de diya

        Route::resource('teachers', TeacherController::class);
        Route::post('teachers/status/{id}', [TeacherController::class, 'toggleStatus'])->name('teachers.status');
        Route::get('classes/{class_name}', [ClassesController::class, 'showStudents'])->name('classes.students');
        Route::resource('classes', ClassesController::class);
    });
require __DIR__ . '/auth.php';
