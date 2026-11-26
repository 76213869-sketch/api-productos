<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rutas públicas: no necesitan autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas privadas: requieren un Bearer Token válido
Route::middleware('auth:sanctum')->group(function () {

    // Obtener información del usuario autenticado
    Route::get('/profile', [AuthController::class, 'profile']);

    // Cerrar sesión y revocar el token actual
    Route::post('/logout', [AuthController::class, 'logout']);

});