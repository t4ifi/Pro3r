<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;

class CreateTestUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test users for development (dentist and receptionist)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Verificar si ya hay usuarios
        if (Usuario::count() > 0) {
            $this->info('Ya existen usuarios en la base de datos.');
            if (!$this->confirm('¿Deseas agregar más usuarios de prueba?')) {
                return;
            }
        }

        $usuarios = [
            [
                'usuario' => 'dentista',
                'nombre' => 'Dr. Juan Carlos Pérez',
                'rol' => 'dentista',
                'password_hash' => password_hash('dentista123', PASSWORD_DEFAULT),
                'activo' => true
            ],
            [
                'usuario' => 'recepcionista',
                'nombre' => 'María Elena García',
                'rol' => 'recepcionista',
                'password_hash' => password_hash('recepcion123', PASSWORD_DEFAULT),
                'activo' => true
            ],
            [
                'usuario' => 'admin',
                'nombre' => 'Administrador Sistema',
                'rol' => 'dentista',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'activo' => true
            ]
        ];

        $this->info('Creando usuarios de prueba...');

        foreach ($usuarios as $usuarioData) {
            try {
                // Verificar si el usuario ya existe
                $existingUser = Usuario::where('usuario', $usuarioData['usuario'])->first();
                
                if ($existingUser) {
                    $this->warn("⚠️  Usuario '{$usuarioData['usuario']}' ya existe, saltando...");
                    continue;
                }

                $usuario = Usuario::create($usuarioData);
                $this->info("✓ Usuario creado: {$usuario->nombre} (@{$usuario->usuario}) - Rol: {$usuario->rol}");
            } catch (\Exception $e) {
                $this->error("✗ Error al crear usuario: {$usuarioData['usuario']} - {$e->getMessage()}");
            }
        }

        $this->info('');
        $this->info('🎉 ¡Usuarios de prueba creados exitosamente!');
        $this->info('Total de usuarios en base de datos: ' . Usuario::count());
        
        $this->info('');
        $this->info('📝 Credenciales de acceso:');
        $this->info('┌─────────────────┬─────────────────┬──────────────────┐');
        $this->info('│ Usuario         │ Contraseña      │ Rol              │');
        $this->info('├─────────────────┼─────────────────┼──────────────────┤');
        $this->info('│ dentista        │ dentista123     │ dentista         │');
        $this->info('│ recepcionista   │ recepcion123    │ recepcionista    │');
        $this->info('│ admin           │ admin123        │ dentista         │');
        $this->info('└─────────────────┴─────────────────┴──────────────────┘');
        
        $this->info('');
        $this->info('🔗 Ambos roles tienen acceso a "Editar Pacientes" en la misma interfaz');
    }
}
