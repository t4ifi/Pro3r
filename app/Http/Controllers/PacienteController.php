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
                'telefono' => 'required|string|max:20',
                'fecha_nacimiento' => 'required|date|before:today',
                'motivo_consulta' => 'required|string|max:1000',
                'alergias' => 'nullable|string|max:1000',
                'observaciones' => 'nullable|string|max:1000',
            ], [
                // Mensajes personalizados en español
                'nombre_completo.required' => 'El nombre completo es obligatorio',
                'nombre_completo.string' => 'El nombre debe ser un texto válido',
                'nombre_completo.max' => 'El nombre no puede tener más de 255 caracteres',
                'telefono.required' => 'El teléfono es obligatorio',
                'telefono.string' => 'El teléfono debe ser un texto válido',
                'telefono.max' => 'El teléfono no puede tener más de 20 caracteres',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
                'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
                'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
                'motivo_consulta.required' => 'El motivo de consulta es obligatorio',
                'motivo_consulta.max' => 'El motivo de consulta no puede tener más de 1000 caracteres',
                'alergias.max' => 'Las alergias no pueden tener más de 1000 caracteres',
                'observaciones.max' => 'Las observaciones no pueden tener más de 1000 caracteres',
            ]);

            // Agregar fecha de última visita automáticamente (hoy)
            $validated['ultima_visita'] = now()->toDateString();

            // Crear el paciente
            $paciente = Paciente::create($validated);
            
            // Registrar en logs
            \Log::info('Nuevo paciente creado', [
                'paciente_id' => $paciente->id,
                'nombre' => $paciente->nombre_completo,
                'telefono' => $paciente->telefono,
                'motivo_consulta' => $paciente->motivo_consulta
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Paciente registrado exitosamente',
                'paciente' => $paciente
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error al crear paciente', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor al crear paciente'
            ], 500);
        }
    }
}
