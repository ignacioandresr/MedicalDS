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
use App\Http\Controllers\VisitorTrainingController;
use App\Http\Controllers\ClinicalCaseController;

Route::get('/visitor/register', [VisitorController::class, 'create'])->name('visitor.register');
Route::get('/visitor/login', function() { return view('visitors.login'); })->name('visitor.login.form');
Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');

Route::post('/visitor/login', [VisitorController::class, 'login'])->name('visitor.login');

// Russian home page for visitors
Route::get('/visitor/home_ru', function() {
    return view('visitors.home_ru');
})->name('visitor.home.ru');

// Training for Russian visitors now uses controller to show clinical cases in Russian
Route::get('/visitor/training_ru', [VisitorTrainingController::class, 'trainingRu'])->name('visitor.training.ru');

// Visitor can view a single case and attempt to solve it (Russian)
Route::get('/visitor/cases/{clinical_case}', [VisitorTrainingController::class, 'show'])->name('visitor.case.show');
Route::post('/visitor/cases/{clinical_case}/attempt', [VisitorTrainingController::class, 'attempt'])->name('visitor.case.attempt');

// Admin CRUD for clinical cases
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('clinical_cases', ClinicalCaseController::class);
});


