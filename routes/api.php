<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\GeneralDiagnosticController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\PrescriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group (['as' => 'api.'], function () {
    Route::apiResource('patients', PatientController::class)->middleware(['auth:sanctum', 'role:admin']);
    Route::apiResource('general-diagnostics', GeneralDiagnosticController::class);
    Route::apiResource('records', RecordController::class);
    Route::get('prescriptions/{prescription}', [PrescriptionController::class, 'show'])->middleware('auth:sanctum')->name('prescriptions.show');
});