<?php

// En routes/api.php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {
    // Rutas para servicios
    Route::apiResource('servicios', ServicioController::class);
    Route::patch('servicios/{servicio}/toggle-status', [ServicioController::class, 'toggleStatus']);
});

// Si estás usando auth básico de Laravel en lugar de Sanctum, cambia por:
// Route::middleware('auth')->group(function () {
//     Route::apiResource('servicios', ServicioController::class);
//     Route::patch('servicios/{servicio}/toggle-status', [ServicioController::class, 'toggleStatus']);
// });