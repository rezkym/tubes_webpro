<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\SchoolSubjectController;
use App\Http\Controllers\StudentProfileController;

Route::get('/home', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    

    Route::resource('school-classes', SchoolClassController::class)->middleware('permission:manage classes');
    Route::resource('school-subjects', SchoolSubjectController::class)->middleware('permission:manage subjects');
    Route::resource('students', StudentProfileController::class)->middleware('role:admin|teacher');
    
    // create dashboard route
    Route::get('/dashboard', function () {
        "hi";
    })->name('dashboard');
});

