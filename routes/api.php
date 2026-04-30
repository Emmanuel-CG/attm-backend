<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;

// 🔓 RUTAS PÚBLICAS
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// 🔐 RECUPERACIÓN
Route::post('/forgot-password', [AuthController::class, 'sendRecoveryEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// 👤 USUARIO
Route::post('/update-profile', [AuthController::class, 'updateProfile']);
Route::post('/logout', [AuthController::class, 'logout']);

//AUTOS
Route::post('/cars', [CarController::class, 'store']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/mine', [CarController::class, 'myCars']);
Route::get('/cars/{id}', [CarController::class, 'show']);
Route::put('/cars/{id}', [CarController::class, 'update']);
Route::delete('/cars/{id}', [CarController::class, 'destroy']);
Route::get('/cars/featured', [CarController::class, 'featured']);
