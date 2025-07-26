<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        // Permitir filtrar por fecha (YYYY-MM-DD)
        $fecha = $request->query('fecha');
        
        $query = Cita::with(['paciente', 'usuario']);
        
        if ($fecha) {
            $query->whereDate('fecha', $fecha);
        }
        
        $citas = $query->orderBy('fecha')->get();
        
        // Mapear para devolver nombre_completo y nombre de usuario directamente
        $citas = $citas->map(function($cita) {
            return [
                'id' => $cita->id,
                'fecha' => $cita->fecha,
                'motivo' => $cita->motivo,
                'estado' => $cita->estado,
                'fecha_atendida' => $cita->fecha_atendida,
                'paciente_id' => $cita->paciente_id,
                'usuario_id' => $cita->usuario_id,
                'nombre_completo' => $cita->paciente ? $cita->paciente->nombre_completo : null,
                'usuario_nombre' => $cita->usuario ? $cita->usuario->nombre : null,
            ];
        });
        
        return response()->json($citas);
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);
        $data = $request->only(['estado', 'fecha_atendida']);
        if (isset($data['estado'])) {
            $cita->estado = $data['estado'];
            if ($data['estado'] === 'atendida') {
                $cita->fecha_atendida = now();
            }
        }
        $cita->save();
        return response()->json(['success' => true, 'cita' => $cita]);
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'fecha' => 'required|date',
                'motivo' => 'required|string',
                'nombre_completo' => 'required|string',
                'estado' => 'string|in:pendiente,confirmada,cancelada,atendida',
            ]);

            // Buscar o crear paciente por nombre
            $paciente = \App\Models\Paciente::where('nombre_completo', $validated['nombre_completo'])->first();
            
            if (!$paciente) {
                // Si no existe el paciente, crear uno básico con solo los campos que existen
                $paciente = \App\Models\Paciente::create([
                    'nombre_completo' => $validated['nombre_completo'],
                    'telefono' => null,
                    'fecha_nacimiento' => null,
                    'ultima_visita' => now()->toDateString(),
                ]);
            }

            // Crear la cita
            $cita = Cita::create([
                'fecha' => $validated['fecha'],
                'motivo' => $validated['motivo'],
                'estado' => $validated['estado'] ?? 'pendiente',
                'paciente_id' => $paciente->id,
                'usuario_id' => 3, // Dr. Juan Pérez (dentista)
            ]);

            return response()->json(['success' => true, 'cita' => $cita->fresh(['paciente', 'usuario'])]);
        } catch (\Exception $e) {
            \Log::error('Error al crear cita:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        return response()->json(['success' => true]);
    }
}
