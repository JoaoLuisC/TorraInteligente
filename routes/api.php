<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Open\Controllers\SensorApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Open (Externa)
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/sensor/health', [SensorApiController::class, 'health']);

// ESP8266 endpoints (público)
Route::post('/sensor/data', [SensorApiController::class, 'receiveData']);
Route::get('/sensor/realtime/{deviceKey}', [SensorApiController::class, 'realtime']);
Route::get('/sensor/chart/{deviceKey}', [SensorApiController::class, 'chartData']);

// Endpoints com autenticação (para dashboard web)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sensor/statistics', [SensorApiController::class, 'statistics']);
    Route::get('/sensor/sessions', [SensorApiController::class, 'sessions']);
});
