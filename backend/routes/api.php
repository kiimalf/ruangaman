<?php

use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Session Routes (Anonymous — No Auth Required)
|--------------------------------------------------------------------------
|
| These routes handle the anonymous expert system session flow.
| No authentication is required — sessions are identified by UUID tokens.
|
*/

Route::prefix('session')->group(function () {
    Route::post('/start', [SessionController::class, 'start']);
    Route::post('/answer', [SessionController::class, 'answer']);
    Route::get('/conclude', [SessionController::class, 'conclude']);
});
