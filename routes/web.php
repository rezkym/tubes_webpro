<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SchoolClassController;

Route::get('/home', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    

    Route::resource('school-classes', SchoolClassController::class);

    // create dashboard route
    Route::get('/dashboard', function () {
        "hi";
    })->name('dashboard');
});

