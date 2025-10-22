<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosticController;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\SymptomController;

// Medical resources: protect with auth and block access when session locale is 'ru'
Route::middleware(['auth', 'block.martian'])->group(function () {
    Route::resource('patients', PatientController::class);
    Route::resource('diagnostics', DiagnosticController::class);
    Route::resource('records', RecordController::class);
    Route::resource('symptoms', SymptomController::class);
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
});




Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

use App\Http\Controllers\ProfileController;
// Profile routes for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\VisitorController;
Route::get('/visitor/register', [VisitorController::class, 'create'])->name('visitor.register');
Route::get('/visitor/login', function() { return view('visitors.login'); })->name('visitor.login.form');
Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');

Route::post('/visitor/login', [VisitorController::class, 'login'])->name('visitor.login');

// Russian home page for visitors
Route::get('/visitor/home_ru', function() {
    return view('visitors.home_ru');
})->name('visitor.home.ru');

// Simple training page for Russian visitor (martian)
Route::get('/visitor/training_ru', function() {
    return view('visitors.training_ru');
})->name('visitor.training.ru');


