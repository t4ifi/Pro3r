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
        \Log::info('Fecha recibida en API:', ['fecha' => $fecha]);
        $query = Cita::with(['paciente', 'usuario']);
        if ($fecha) {
            \Log::info('Aplicando filtro whereDate', ['whereDate' => $fecha]);
            $query->whereDate('fecha', $fecha);
        }
        $citas = $query->orderBy('fecha')->get();
        \Log::info('Citas encontradas:', ['count' => $citas->count(), 'citas' => $citas->pluck('fecha')]);
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

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();
        return response()->json(['success' => true]);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'fecha' => 'required|date',
    //         'motivo' => 'required|string',
    //         'paciente_id' => 'required|exists:pacientes,id',
    //         'usuario_id' => 'required|exists:usuarios,id',
    //     ]);
    //     $cita = Cita::create([
    //         'fecha' => $validated['fecha'],
    //         'motivo' => $validated['motivo'],
    //         'estado' => 'pendiente',
    //         'paciente_id' => $validated['paciente_id'],
    //         'usuario_id' => $validated['usuario_id'],
    //     ]);
    //     return response()->json(['success' => true, 'cita' => $cita->fresh(['paciente', 'usuario'])]);
    // }
}
