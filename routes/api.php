<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PlacaController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/citas', [CitaController::class, 'index']);
Route::post('/citas', [CitaController::class, 'store']);
Route::put('/citas/{id}', [CitaController::class, 'update']);
Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
Route::get('/pacientes', [PacienteController::class, 'index']);
Route::get('/pacientes/{id}', [PacienteController::class, 'show']);
Route::put('/pacientes/{id}', [PacienteController::class, 'update']);
Route::post('/pacientes', [PacienteController::class, 'store']);

// Rutas para placas dentales
Route::get('/placas', [PlacaController::class, 'index']);
Route::get('/placas/{id}', [PlacaController::class, 'show']);
Route::post('/placas', [PlacaController::class, 'store']);
Route::put('/placas/{id}', [PlacaController::class, 'update']);
Route::delete('/placas/{id}', [PlacaController::class, 'destroy']);