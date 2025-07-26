<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tratamiento;
use App\Models\HistorialClinico;
use App\Models\Paciente;
use App\Models\Usuario;

class CreateTestTreatments extends Command
{
    protected $signature = 'treatments:create-test';
    protected $description = 'Crear tratamientos de prueba';

    public function handle()
    {
        // Verificar que existan pacientes y usuarios
        $pacientes = Paciente::take(3)->get();
        $dentista = Usuario::where('rol', 'dentista')->first();

        if ($pacientes->isEmpty() || !$dentista) {
            $this->error('Se necesitan pacientes y al menos un dentista para crear tratamientos de prueba.');
            return;
        }

        $tratamientosData = [
            [
                'descripcion' => 'Limpieza dental profunda y aplicación de flúor',
                'fecha_inicio' => '2025-01-15',
                'estado' => 'activo',
                'observaciones' => 'Paciente con buena higiene oral. Se recomienda uso de hilo dental diario.'
            ],
            [
                'descripcion' => 'Tratamiento de conducto en molar superior derecho',
                'fecha_inicio' => '2025-01-10',
                'estado' => 'activo',
                'observaciones' => 'Dolor moderado reportado. Procedimiento en 3 sesiones.'
            ],
            [
                'descripcion' => 'Ortodoncia con brackets metálicos',
                'fecha_inicio' => '2025-01-01',
                'estado' => 'activo',
                'observaciones' => 'Duración estimada: 18 meses. Control mensual requerido.'
            ],
            [
                'descripcion' => 'Extracción de muela del juicio',
                'fecha_inicio' => '2024-12-20',
                'estado' => 'finalizado',
                'observaciones' => 'Procedimiento exitoso. Recuperación normal.'
            ],
            [
                'descripcion' => 'Blanqueamiento dental profesional',
                'fecha_inicio' => '2024-12-15',
                'estado' => 'finalizado',
                'observaciones' => 'Resultado satisfactorio. Evitar alimentos pigmentantes.'
            ]
        ];

        $this->info('Creando tratamientos de prueba...');

        foreach ($tratamientosData as $index => $data) {
            $paciente = $pacientes[$index % $pacientes->count()];
            
            $tratamiento = Tratamiento::create([
                'descripcion' => $data['descripcion'],
                'fecha_inicio' => $data['fecha_inicio'],
                'estado' => $data['estado'],
                'paciente_id' => $paciente->id, // Usar 'id' en lugar de 'id_paciente'
                'usuario_id' => $dentista->id
            ]);

            // Crear entrada en historial clínico
            HistorialClinico::create([
                'fecha_visita' => $data['fecha_inicio'],
                'tratamiento' => $data['descripcion'],
                'observaciones' => $data['observaciones'],
                'paciente_id' => $paciente->id, // Usar 'id' en lugar de 'id_paciente'
                'tratamiento_id' => $tratamiento->id
            ]);

            $this->info("✅ Tratamiento creado: {$data['descripcion']} para {$paciente->nombre_completo}");
        }

        // Crear observaciones adicionales para algunos tratamientos
        $tratamientosActivos = Tratamiento::where('estado', 'activo')->get();
        
        foreach ($tratamientosActivos->take(2) as $tratamiento) {
            HistorialClinico::create([
                'fecha_visita' => '2025-01-18',
                'tratamiento' => 'Seguimiento y evaluación',
                'observaciones' => 'Evolución favorable. Paciente sin molestias. Continúa con el tratamiento según lo planificado.',
                'paciente_id' => $tratamiento->paciente_id,
                'tratamiento_id' => $tratamiento->id
            ]);

            $this->info("✅ Observación de seguimiento agregada para tratamiento: {$tratamiento->descripcion}");
        }

        $this->info('');
        $this->info('🎉 Tratamientos de prueba creados exitosamente!');
        $this->info('📊 Total creados: ' . count($tratamientosData));
        $this->info('🔄 Tratamientos activos: ' . Tratamiento::where('estado', 'activo')->count());
        $this->info('✅ Tratamientos finalizados: ' . Tratamiento::where('estado', 'finalizado')->count());
    }
}
