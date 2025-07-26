<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateTestPatients extends Command
{
    protected $signature = 'patients:create-test';
    protected $description = 'Crear pacientes de prueba con la estructura correcta de la base de datos';

    public function handle()
    {
        $pacientesData = [
            [
                'nombre_completo' => 'María Elena García López',
                'telefono' => '+51 987 654 321',
                'fecha_nacimiento' => '1985-03-15',
                'ultima_visita' => '2025-01-20'
            ],
            [
                'nombre_completo' => 'Carlos Andrés Rodríguez Martín',
                'telefono' => '+51 976 543 210',
                'fecha_nacimiento' => '1978-08-22',
                'ultima_visita' => '2025-01-18'
            ],
            [
                'nombre_completo' => 'Ana Sofía Fernández Ruiz',
                'telefono' => '+51 965 432 109',
                'fecha_nacimiento' => '1992-11-08',
                'ultima_visita' => '2025-01-15'
            ],
            [
                'nombre_completo' => 'Luis Fernando Sánchez Torres',
                'telefono' => '+51 954 321 098',
                'fecha_nacimiento' => '1975-06-30',
                'ultima_visita' => '2025-01-12'
            ],
            [
                'nombre_completo' => 'Carmen Isabel Jiménez Vega',
                'telefono' => '+51 943 210 987',
                'fecha_nacimiento' => '1988-12-03',
                'ultima_visita' => '2025-01-10'
            ],
            [
                'nombre_completo' => 'Jorge Alberto Morales Castro',
                'telefono' => '+51 932 109 876',
                'fecha_nacimiento' => '1982-04-17',
                'ultima_visita' => '2025-01-08'
            ],
            [
                'nombre_completo' => 'Patricia Beatriz Herrera Núñez',
                'telefono' => '+51 921 098 765',
                'fecha_nacimiento' => '1990-09-25',
                'ultima_visita' => '2025-01-05'
            ],
            [
                'nombre_completo' => 'Roberto Daniel Castillo Vargas',
                'telefono' => '+51 910 987 654',
                'fecha_nacimiento' => '1979-07-14',
                'ultima_visita' => '2025-01-03'
            ],
            [
                'nombre_completo' => 'Silvia Esperanza Ramos Delgado',
                'telefono' => '+51 909 876 543',
                'fecha_nacimiento' => '1987-01-28',
                'ultima_visita' => '2025-01-01'
            ]
        ];

        $this->info('🦷 Creando pacientes de prueba para DentalSYNC2...');
        $this->info('');
        
        foreach ($pacientesData as $data) {
            // Verificar si el paciente ya existe usando consulta directa
            $exists = DB::table('pacientes')
                ->where('nombre_completo', $data['nombre_completo'])
                ->exists();
                        
            if (!$exists) {
                DB::table('pacientes')->insert([
                    'nombre_completo' => $data['nombre_completo'],
                    'telefono' => $data['telefono'],
                    'fecha_nacimiento' => $data['fecha_nacimiento'],
                    'ultima_visita' => $data['ultima_visita'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $this->info("✅ Paciente creado: {$data['nombre_completo']} - {$data['telefono']}");
            } else {
                $this->warn("⚠️  Paciente ya existe: {$data['nombre_completo']}");
            }
        }

        $this->info('');
        $this->info('🎉 Proceso completado!');
        $this->info('📊 Total de pacientes en la base de datos: ' . DB::table('pacientes')->count());
        $this->info('');
        $this->info('💡 Puedes usar estos pacientes para:');
        $this->info('   • Registrar tratamientos y observaciones');
        $this->info('   • Crear citas médicas');
        $this->info('   • Probar la funcionalidad del sistema');
        $this->info('');
        $this->info('🔗 Comandos relacionados:');
        $this->info('   php artisan treatments:create-test   # Crear tratamientos de prueba');
        $this->info('   php artisan users:create-test        # Crear usuarios de prueba');
    }
}