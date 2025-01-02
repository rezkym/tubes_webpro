<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSubjectController;
use App\Http\Controllers\StudentProfileController;
use App\Http\Controllers\TeacherProfileController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;

Route::get('/home', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    

    Route::resource('school-classes', SchoolClassController::class)->middleware('permission:manage classes');
    Route::resource('school-subjects', SchoolSubjectController::class)->middleware('permission:manage subjects');
    Route::resource('students', StudentProfileController::class)->middleware('role:admin|teacher');
    Route::resource('teachers', TeacherProfileController::class);
    Route::resource('users', UserController::class);

    
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
