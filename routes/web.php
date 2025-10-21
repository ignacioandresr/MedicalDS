<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosticController;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\SymptomController;

Route::resource('patients', PatientController::class);
Route::resource('diagnostics', DiagnosticController::class);

Route::resource('records', RecordController::class);
Route::resource('symptoms', SymptomController::class);




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\VisitorController;
Route::get('/visitor/register', [VisitorController::class, 'create'])->name('visitor.register');
Route::get('/visitor/login', function() { return view('visitors.login'); })->name('visitor.login.form');
Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');

Route::post('/visitor/login', [VisitorController::class, 'login'])->name('visitor.login');

// Russian home page for visitors
Route::get('/visitor/home_ru', function() {
    return view('visitors.home_ru');
})->name('visitor.home.ru');


