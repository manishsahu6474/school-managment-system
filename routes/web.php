<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeacherController;
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

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard'); */
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard Route (Jo total counts dikhayega)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Student Module Routes
    Route::resource('students', StudentController::class);

    // 3. Teacher Module Routes
    Route::resource('teachers', TeacherController::class);
});
require __DIR__.'/auth.php';
//Route::resource('students', StudentController::class);
