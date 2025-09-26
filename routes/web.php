<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;

Route::resource('patients', PatientController::class);




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


