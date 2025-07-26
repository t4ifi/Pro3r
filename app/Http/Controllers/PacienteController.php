<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index()
    {
        try {
            $pacientes = \DB::table('pacientes')->get();
            return response()->json($pacientes);
        } catch (\Exception $e) {
            \Log::error("Error al obtener pacientes: " . $e->getMessage());
            return response()->json(['error' => 'Error al cargar pacientes'], 500);
        }
    }

    /**
     * Mostrar información específica de un paciente
     */
    public function show($id)
    {
        try {
            \Log::info("Buscando paciente con ID: {$id}");
            
            $paciente = \DB::table('pacientes')->where('id', $id)->first();
            
            if (!$paciente) {
                \Log::error("Paciente no encontrado con ID: {$id}");
                return response()->json(['error' => 'Paciente no encontrado'], 404);
            }
            
            \Log::info("Paciente encontrado: {$paciente->nombre_completo}");
            
            return response()->json($paciente);
        } catch (\Exception $e) {
            \Log::error("Error al buscar paciente: {$e->getMessage()}");
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Actualizar información de un paciente
     */
    public function update(Request $request, $id)
    {
        try {
            // Validar datos de entrada
            $validated = $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date|before:today',
                'ultima_visita' => 'nullable|date',
            ]);

            $paciente = Paciente::findOrFail($id);
            
            // Actualizar campos
            $paciente->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Paciente actualizado exitosamente',
                'paciente' => $paciente->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al actualizar paciente: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear un nuevo paciente
     */
    public function store(Request $request)
    {
        try {
            // Validar datos de entrada
            $validated = $request->validate([
                'nombre_completo' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:20',
                'fecha_nacimiento' => 'nullable|date|before:today',
            ]);

            // Agregar fecha de última visita automáticamente
            $validated['ultima_visita'] = now()->toDateString();

            $paciente = Paciente::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Paciente creado exitosamente',
                'paciente' => $paciente
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear paciente: ' . $e->getMessage()
            ], 500);
        }
    }
}
