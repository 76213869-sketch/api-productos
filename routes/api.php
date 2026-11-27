<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;

// Rutas públicas: autenticación
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rutas privadas: requieren autenticación mediante Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Perfil y cierre de sesión
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // CRUD de productos
    Route::get('/productos', [ProductController::class, 'index']);
    Route::post('/productos', [ProductController::class, 'store']);
    Route::get('/productos/{id}', [ProductController::class, 'show']);
    Route::put('/productos/{id}', [ProductController::class, 'update']);
    Route::patch('/productos/{id}', [ProductController::class, 'update']);
    Route::delete('/productos/{id}', [ProductController::class, 'destroy']);
});