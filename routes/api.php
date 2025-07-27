<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PlacaController;
use App\Http\Controllers\TratamientoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Api\WhatsappConversacionController;
use App\Http\Controllers\Api\WhatsappPlantillaController;
use App\Http\Controllers\Api\WhatsappAutomatizacionController;

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

// Rutas para tratamientos
Route::get('/tratamientos/pacientes', [TratamientoController::class, 'getPacientes']);
Route::get('/tratamientos/paciente/{pacienteId}', [TratamientoController::class, 'getTratamientosPaciente']);
Route::post('/tratamientos', [TratamientoController::class, 'store']);
Route::post('/tratamientos/{id}/observacion', [TratamientoController::class, 'addObservacion']);
Route::put('/tratamientos/{id}/finalizar', [TratamientoController::class, 'finalizar']);
Route::get('/tratamientos/historial/{pacienteId}', [TratamientoController::class, 'getHistorialClinico']);

// Rutas para sistema de pagos
Route::prefix('pagos')->group(function () {
    Route::post('/init-session', [PagoController::class, 'initSession']); // Para pruebas
    Route::get('/pacientes', [PagoController::class, 'getPacientes']);
    Route::get('/resumen', [PagoController::class, 'getResumenPagos']);
    Route::post('/registrar', [PagoController::class, 'registrarPago']);
    Route::get('/paciente/{pacienteId}', [PagoController::class, 'verPagosPaciente']);
    Route::post('/cuota', [PagoController::class, 'registrarPagoCuota']);
});

// Rutas para WhatsApp
Route::prefix('whatsapp')->group(function () {
    // Conversaciones
    Route::get('/conversaciones', [WhatsappConversacionController::class, 'index']);
    Route::post('/conversaciones', [WhatsappConversacionController::class, 'crear']);
    Route::get('/conversaciones/{conversacion}/mensajes', [WhatsappConversacionController::class, 'mensajes']);
    Route::post('/conversaciones/{conversacion}/mensajes', [WhatsappConversacionController::class, 'enviarMensaje']);
    Route::put('/conversaciones/{conversacion}/estado', [WhatsappConversacionController::class, 'actualizarEstado']);
    Route::get('/conversaciones/estadisticas', [WhatsappConversacionController::class, 'estadisticas']);
    
    // Plantillas
    Route::get('/plantillas', [WhatsappPlantillaController::class, 'index']);
    Route::post('/plantillas', [WhatsappPlantillaController::class, 'store']);
    Route::get('/plantillas/{plantilla}', [WhatsappPlantillaController::class, 'show']);
    Route::put('/plantillas/{plantilla}', [WhatsappPlantillaController::class, 'update']);
    Route::delete('/plantillas/{plantilla}', [WhatsappPlantillaController::class, 'destroy']);
    Route::post('/plantillas/{plantilla}/duplicar', [WhatsappPlantillaController::class, 'duplicar']);
    Route::put('/plantillas/{plantilla}/toggle', [WhatsappPlantillaController::class, 'toggleEstado']);
    Route::post('/plantillas/{plantilla}/usar', [WhatsappPlantillaController::class, 'incrementarUsos']);
    Route::get('/plantillas/categorias/list', [WhatsappPlantillaController::class, 'categorias']);
    Route::get('/plantillas/estadisticas/resumen', [WhatsappPlantillaController::class, 'estadisticas']);
});

// Rutas para usuarios
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UsuarioController::class, 'index']);
    Route::post('/', [UsuarioController::class, 'store']);
    Route::get('/{id}', [UsuarioController::class, 'show']);
    Route::put('/{id}', [UsuarioController::class, 'update']);
    Route::delete('/{id}', [UsuarioController::class, 'destroy']);
    Route::post('/{id}/toggle-status', [UsuarioController::class, 'toggleStatus']);
    Route::get('/estadisticas/resumen', [UsuarioController::class, 'statistics']);
});