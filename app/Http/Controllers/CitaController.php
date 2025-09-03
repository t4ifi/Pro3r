<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Permitir filtrar por fecha (YYYY-MM-DD)
            $fecha = $request->query('fecha');
            
            // Usar consulta directa con JOIN para evitar problemas de mbstring
            $query = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('usuarios', 'citas.usuario_id', '=', 'usuarios.id')
                ->select(
                    'citas.id',
                    'citas.fecha',
                    'citas.motivo',
                    'citas.estado',
                    'citas.fecha_atendida',
                    'citas.paciente_id',
                    'citas.usuario_id',
                    'pacientes.nombre_completo',
                    'usuarios.nombre as usuario_nombre',
                    'citas.created_at',
                    'citas.updated_at'
                );
            
            if ($fecha) {
                $query->whereDate('citas.fecha', $fecha);
            }
            
            $citas = $query->orderBy('citas.fecha')->get();
            
            return response()->json($citas);
        } catch (\Exception $e) {
            \Log::error('Error al obtener citas:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $data = $request->only(['estado', 'fecha_atendida']);
            
            $updateData = ['updated_at' => now()];
            
            if (isset($data['estado'])) {
                $updateData['estado'] = $data['estado'];
                if ($data['estado'] === 'atendida') {
                    $updateData['fecha_atendida'] = now();
                }
            }
            
            // Actualizar usando consulta directa
            DB::table('citas')->where('id', $id)->update($updateData);
            
            // Obtener la cita actualizada
            $cita = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('usuarios', 'citas.usuario_id', '=', 'usuarios.id')
                ->select(
                    'citas.id',
                    'citas.fecha',
                    'citas.motivo',
                    'citas.estado',
                    'citas.fecha_atendida',
                    'citas.paciente_id',
                    'citas.usuario_id',
                    'pacientes.nombre_completo',
                    'usuarios.nombre as usuario_nombre',
                    'citas.created_at',
                    'citas.updated_at'
                )
                ->where('citas.id', $id)
                ->first();
            
            return response()->json(['success' => true, 'cita' => $cita]);
        } catch (\Exception $e) {
            \Log::error('Error al actualizar cita:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
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

            // Buscar o crear paciente por nombre usando consulta directa
            $paciente = DB::table('pacientes')
                ->where('nombre_completo', $validated['nombre_completo'])
                ->first();
            
            if (!$paciente) {
                // Si no existe el paciente, crear uno básico
                $pacienteId = DB::table('pacientes')->insertGetId([
                    'nombre_completo' => $validated['nombre_completo'],
                    'telefono' => null,
                    'fecha_nacimiento' => null,
                    'ultima_visita' => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $pacienteId = $paciente->id;
            }

            // Obtener usuario automáticamente
            try {
                $usuarioId = $this->obtenerUsuarioAutomatico();
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error del sistema: ' . $e->getMessage()
                ], 500);
            }

            // Crear la cita usando consulta directa
            $citaId = DB::table('citas')->insertGetId([
                'fecha' => $validated['fecha'],
                'motivo' => $validated['motivo'],
                'estado' => $validated['estado'] ?? 'pendiente',
                'paciente_id' => $pacienteId,
                'usuario_id' => $usuarioId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Obtener la cita creada con los datos del paciente y usuario
            $cita = DB::table('citas')
                ->leftJoin('pacientes', 'citas.paciente_id', '=', 'pacientes.id')
                ->leftJoin('usuarios', 'citas.usuario_id', '=', 'usuarios.id')
                ->select(
                    'citas.id',
                    'citas.fecha',
                    'citas.motivo',
                    'citas.estado',
                    'citas.fecha_atendida',
                    'citas.paciente_id',
                    'citas.usuario_id',
                    'pacientes.nombre_completo',
                    'usuarios.nombre as usuario_nombre',
                    'citas.created_at',
                    'citas.updated_at'
                )
                ->where('citas.id', $citaId)
                ->first();

            return response()->json(['success' => true, 'cita' => $cita]);
        } catch (\Exception $e) {
            \Log::error('Error al crear cita:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::table('citas')->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Error al eliminar cita:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Error interno del servidor: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Obtener usuario automáticamente con sistema de fallback inteligente
     */
    private function obtenerUsuarioAutomatico()
    {
        // Intentar obtener usuario de la sesión primero
        $usuario = session('user');
        
        if ($usuario) {
            \Log::info('Usuario autenticado encontrado: ' . $usuario['nombre'] . ' (ID: ' . $usuario['id'] . ')');
            return $usuario['id'];
        }
        
        \Log::info('No hay sesión activa, buscando usuario automáticamente...');
        
        // Prioridad 1: Buscar dentistas activos
        $dentista = DB::table('usuarios')
            ->where('rol', 'dentista')
            ->where('activo', true)
            ->orderBy('id', 'asc')
            ->first();
        
        if ($dentista) {
            \Log::info('Usando dentista automático: ' . $dentista->nombre . ' (ID: ' . $dentista->id . ')');
            return $dentista->id;
        }
        
        // Prioridad 2: Buscar cualquier usuario activo
        $usuarioGeneral = DB::table('usuarios')
            ->where('activo', true)
            ->orderBy('id', 'asc')
            ->first();
        
        if ($usuarioGeneral) {
            \Log::info('Usando usuario general automático: ' . $usuarioGeneral->nombre . ' (ID: ' . $usuarioGeneral->id . ')');
            return $usuarioGeneral->id;
        }
        
        // Último recurso: Buscar cualquier usuario
        $cualquierUsuario = DB::table('usuarios')
            ->orderBy('id', 'asc')
            ->first();
        
        if ($cualquierUsuario) {
            \Log::warning('Usando último recurso - usuario: ' . $cualquierUsuario->nombre . ' (ID: ' . $cualquierUsuario->id . ')');
            return $cualquierUsuario->id;
        }
        
        // No hay usuarios en el sistema
        \Log::error('No se encontraron usuarios en el sistema');
        throw new \Exception('No hay usuarios disponibles en el sistema');
    }
}
