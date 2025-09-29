<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosticController;

use App\Http\Controllers\RecordController;

Route::resource('patients', PatientController::class);
Route::resource('diagnostics', DiagnosticController::class);

Route::resource('records', RecordController::class);




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


