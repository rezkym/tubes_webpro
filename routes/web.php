<?php

use App\Http\Controllers\AdminHomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSubjectController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth'])->get('/to_role', function () {
    $user = Auth::user();
    
    return redirect()->route($user->getRoleNames()->first() . '.home');

});

// routes/web.php
Route::get('/api/student-numbers/search', [StudentProfileController::class, 'searchStudentNumbers'])
    ->name('api.student-numbers.search');

Route::group(['middleware' => 'auth'], function () {

    
    // Create group for every role
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('admin.home');
        Route::resource('school-classes', SchoolClassController::class)->middleware('permission:manage classes');
        Route::resource('school-subjects', SchoolSubjectController::class)->middleware('permission:manage subjects');
        Route::resource('students', StudentProfileController::class)->middleware('role:admin|teacher');
        Route::resource('teachers', TeacherProfileController::class);
        Route::resource('users', UserController::class);
    });

    Route::prefix('teacher')->middleware('role:teacher')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('teacher.home');
    });

    Route::prefix('student')->middleware('role:student')->group(function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('student.home');
    });

    

    
    // create dashboard route
    Route::get('/dashboard', function () {
        "hi";
    })->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Attendance routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('attendance/get-students', [AttendanceController::class, 'getStudents'])->name('attendance.getStudents');
    Route::get('attendance/{class}/{subject}/{date}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('attendance/{class}/{subject}/{date}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::get('attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
});
