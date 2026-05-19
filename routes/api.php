<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::post('/forgot-password', [AuthController::class, 'sendRecoveryEmail']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::post('/update-profile', [AuthController::class, 'updateProfile']);
Route::post('/logout', [AuthController::class, 'logout']);


Route::post('/cars', [CarController::class, 'store']);
Route::get('/cars', [CarController::class, 'index']);
Route::get('/cars/mine', [CarController::class, 'myCars']);


Route::get('/cars/filter', [CarController::class, 'filter']);
Route::get('/cars/featured', [CarController::class, 'featured']);


Route::get('/cars/{id}', [CarController::class, 'show']);
Route::put('/cars/{id}', [CarController::class, 'update']);
Route::delete('/cars/{id}', [CarController::class, 'destroy']);

// CONTACTOS
Route::post('/contacts', [ContactController::class, 'store']);     // crear contacto
Route::get('/contacts', [ContactController::class, 'index']);      // listar contactos
Route::patch('/contacts/{id}/read', [ContactController::class, 'markAsRead']); // marcar leído
Route::get('/contacts/car/{carId}', [ContactController::class, 'byCar']);

Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
Route::get('/admin/users', [AdminController::class, 'users']);
Route::get('/admin/cars', [CarController::class, 'adminCars']);

Route::patch(
    '/admin/cars/{id}/status',
    [CarController::class, 'updateStatus']
);

Route::post('/change-password', [AuthController::class, 'changePassword']);
Route::post('/cars/{id}/report', [CarController::class, 'report']);
Route::get('/admin/reports', [AdminController::class, 'reports']);