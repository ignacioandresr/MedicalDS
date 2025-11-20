<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DiagnosticController;

use App\Http\Controllers\RecordController;
use App\Http\Controllers\SymptomController;

Route::middleware(['auth', 'block.martian'])->group(function () {
    Route::resource('patients', PatientController::class);
    Route::resource('diagnostics', DiagnosticController::class);
    Route::get('diagnostics/suggest', [DiagnosticController::class, 'suggest'])->name('diagnostics.suggest');
    Route::resource('general-diagnostics', App\Http\Controllers\GeneralDiagnosticController::class);
    Route::resource('records', RecordController::class);
    Route::resource('symptoms', SymptomController::class);
    Route::post('symptoms/suggest', [SymptomController::class, 'suggest'])->name('symptoms.suggest');
    Route::resource('appointments', App\Http\Controllers\AppointmentController::class);
});




Route::get('/', function () {
    // Asegurar que la página de inicio siempre esté en español
    session()->put('locale', 'es');
    app()->setLocale('es');
    return view('welcome');
});

// Switch application locale (only allow 'es' and 'ru')
Route::get('lang/{locale}', function ($locale, \Illuminate\Http\Request $request) {
    $allowed = ['es', 'ru'];
    if (!in_array($locale, $allowed)) {
        abort(404);
    }
    session()->put('locale', $locale);
    app()->setLocale($locale);
    
    // Si hay un parámetro redirect, redirigir allí
    if ($request->has('redirect')) {
        $redirectUrl = $request->input('redirect');
        return redirect($redirectUrl);
    }
    
    return redirect()->back();
})->name('lang.switch');

Auth::routes();

// Ruta home protegida: bloquear visitante (locale ru) excepto admin
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware(['auth','block.martian'])->name('home');

// Eliminar duplicado /home y segunda llamada redundante a Auth::routes()

use App\Http\Controllers\ProfileController;
// Bloquear visitante (locale ru) en rutas de perfil estándar
Route::middleware(['auth','block.martian'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitorTrainingController;
use App\Http\Controllers\ClinicalCaseController;
use App\Http\Controllers\AdminGateController;
use App\Http\Controllers\AdminRegisterController;

Route::middleware('deny.standard.visitor')->group(function() {
    Route::get('/visitor/register', [VisitorController::class, 'create'])->name('visitor.register');
    Route::get('/visitor/login', function() { return view('visitors.login'); })->name('visitor.login.form');
    // Evitar 405 en GET /visitor: redirigir siempre a la pantalla de bienvenida marciana
    Route::get('/visitor', function() {
        return redirect()->route('visitor.welcome.ru');
    });
    Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');
    Route::post('/visitor/login', [VisitorController::class, 'login'])->name('visitor.login');
    Route::get('/visitor/welcome_ru', function(\Illuminate\Http\Request $request) {
        // Forzar ruso siempre al entrar a la bienvenida marciana
        $request->session()->put('locale', 'ru');
        \Illuminate\Support\Facades\App::setLocale('ru');
        return view('visitors.welcome_ru');
    })->name('visitor.welcome.ru');
    Route::get('/visitor/welcome_es', function(\Illuminate\Http\Request $request) {
        // Variante en español de la pantalla de bienvenida marciana
        $request->session()->put('locale', 'es');
        \Illuminate\Support\Facades\App::setLocale('es');
        return view('visitors.welcome_ru'); // reutiliza la misma vista, las traducciones cambian por locale
    })->name('visitor.welcome.es');
});

Route::get('/admin/gate', [AdminGateController::class, 'showGateForm'])->name('admin.gate.form');
Route::post('/admin/gate', [AdminGateController::class, 'validateGate'])->name('admin.gate.validate');

Route::get('/register/admin', [AdminRegisterController::class, 'showRegistrationForm'])->name('register.admin.form');
Route::post('/register/admin', [AdminRegisterController::class, 'register'])->name('register.admin');


// Visitor offline simulation toggles (set a cookie used by the service worker)
Route::get('/visitor/simulate-offline/on', function () {
    return redirect()->back()->withCookie(cookie('simulate_offline', '1', 60));
})->name('visitor.simulate.off.on');

Route::get('/visitor/simulate-offline/off', function () {
    return redirect()->back()->withCookie(cookie('simulate_offline', '', -1));
})->name('visitor.simulate.off.off');

Route::middleware('visitor.auth')->group(function () {
    Route::get('/visitor/home_ru', function() {
        return view('visitors.home_ru');
    })->name('visitor.home.ru');

    Route::post('/visitor/leave', function (\Illuminate\Http\Request $request) {
        $request->session()->forget('visitor_authenticated');
        // Establecer idioma a español al salir de la interfaz marciana
        $request->session()->put('locale', 'es');
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


