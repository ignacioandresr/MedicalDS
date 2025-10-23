<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosticController;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\SymptomController;

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
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitorTrainingController;
use App\Http\Controllers\ClinicalCaseController;
use App\Http\Controllers\AdminGateController;
use App\Http\Controllers\AdminRegisterController;

Route::get('/visitor/register', [VisitorController::class, 'create'])->name('visitor.register');
Route::get('/visitor/login', function() { return view('visitors.login'); })->name('visitor.login.form');
Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');

Route::post('/visitor/login', [VisitorController::class, 'login'])->name('visitor.login');

Route::get('/admin/gate', [AdminGateController::class, 'showGateForm'])->name('admin.gate.form');
Route::post('/admin/gate', [AdminGateController::class, 'validateGate'])->name('admin.gate.validate');

Route::get('/register/admin', [AdminRegisterController::class, 'showRegistrationForm'])->name('register.admin.form');
Route::post('/register/admin', [AdminRegisterController::class, 'register'])->name('register.admin');

Route::get('/visitor/welcome_ru', function() {
    return view('visitors.welcome_ru');
})->name('visitor.welcome.ru');

Route::middleware('visitor.auth')->group(function () {
    Route::get('/visitor/home_ru', function() {
        return view('visitors.home_ru');
    })->name('visitor.home.ru');

    Route::post('/visitor/leave', function (\Illuminate\Http\Request $request) {
        $request->session()->forget(['visitor_authenticated', 'locale']);
        try { auth()->logout(); } catch (\Throwable $e) {}
        return redirect('/');
    })->name('visitor.leave');

    Route::get('/visitor/training_ru', [VisitorTrainingController::class, 'trainingRu'])->name('visitor.training.ru');

    Route::get('/visitor/cases/{clinical_case}', [VisitorTrainingController::class, 'show'])->name('visitor.case.show');
    Route::post('/visitor/cases/{clinical_case}/attempt', [VisitorTrainingController::class, 'attempt'])->name('visitor.case.attempt');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('clinical_cases', ClinicalCaseController::class);
    Route::get('/roles/assign', [App\Http\Controllers\RoleAssignmentController::class, 'index'])->name('roles.assign');
    Route::post('/roles/assign', [App\Http\Controllers\RoleAssignmentController::class, 'update'])->name('roles.assign.update');
    Route::get('/admin/users', [App\Http\Controllers\RoleAssignmentController::class, 'users'])->name('admin.users');
    Route::delete('/admin/users/{user}', [App\Http\Controllers\RoleAssignmentController::class, 'destroy'])->name('admin.users.destroy');
});


