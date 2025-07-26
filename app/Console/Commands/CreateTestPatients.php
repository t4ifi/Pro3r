<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Paciente;

class CreateTestPatients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patients:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test patients for development';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si ya hay pacientes
        if (Paciente::count() > 0) {
            $this->info('Ya existen pacientes en la base de datos.');
            if (!$this->confirm('¿Deseas agregar más pacientes de prueba?')) {
                return;
            }
        }

        $pacientes = [
            [
                'nombre_completo' => 'María García López',
                'telefono' => '+1 234 567 8901',
                'fecha_nacimiento' => '1985-03-15',
                'ultima_visita' => '2025-01-20'
            ],
            [
                'nombre_completo' => 'Carlos Rodríguez Martín',
                'telefono' => '+1 234 567 8902',
                'fecha_nacimiento' => '1990-07-22',
                'ultima_visita' => '2025-02-10'
            ],
            [
                'nombre_completo' => 'Ana Fernández Ruiz',
                'telefono' => '+1 234 567 8903',
                'fecha_nacimiento' => '1978-11-08',
                'ultima_visita' => '2025-01-15'
            ],
            [
                'nombre_completo' => 'Luis Sánchez Torres',
                'telefono' => '+1 234 567 8904',
                'fecha_nacimiento' => '1995-04-30',
                'ultima_visita' => '2025-02-05'
            ],
            [
                'nombre_completo' => 'Carmen Jiménez Vega',
                'telefono' => '+1 234 567 8905',
                'fecha_nacimiento' => '1982-09-12',
                'ultima_visita' => '2025-01-28'
            ]
        ];

        $this->info('Creando pacientes de prueba...');

        foreach ($pacientes as $pacienteData) {
            try {
                $paciente = Paciente::create($pacienteData);
                $this->info("✓ Paciente creado: {$paciente->nombre_completo} (ID: {$paciente->id})");
            } catch (\Exception $e) {
                $this->error("✗ Error al crear paciente: {$pacienteData['nombre_completo']} - {$e->getMessage()}");
            }
        }

        $this->info('¡Pacientes de prueba creados exitosamente!');
        $this->info('Total de pacientes en base de datos: ' . Paciente::count());
    }
}
