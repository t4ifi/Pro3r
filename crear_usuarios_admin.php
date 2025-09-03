<?php

/**
 * Archivo para crear usuarios administrativos en DentalSync
 * Crea 2 usuarios: admin (dentista) y admin2 (recepcionista)
 * 
 * Autor: Sistema DentalSync
 * Fecha: 3 de septiembre de 2025
 */

require_once __DIR__ . '/vendor/autoload.php';

// Cargar configuración de Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

try {
    echo "🦷 DentalSync - Creador de Usuarios Administrativos\n";
    echo "================================================\n\n";

    // Verificar conexión a la base de datos
    echo "📡 Verificando conexión a la base de datos...\n";
    DB::connection()->getPdo();
    echo "✅ Conexión exitosa\n\n";

    // Datos de los usuarios a crear
    $usuarios = [
        [
            'usuario' => 'admin',
            'nombre' => 'Administrador Principal',
            'rol' => 'dentista',
            'password' => 'admin123',
            'activo' => true
        ],
        [
            'usuario' => 'admin2',
            'nombre' => 'Administrador Recepcionista',
            'rol' => 'recepcionista', 
            'password' => 'admin123',
            'activo' => true
        ]
    ];

    echo "👥 Creando usuarios administrativos...\n";
    echo "=====================================\n\n";

    foreach ($usuarios as $userData) {
        // Verificar si el usuario ya existe
        $existeUsuario = DB::table('usuarios')
            ->where('usuario', $userData['usuario'])
            ->first();

        if ($existeUsuario) {
            echo "⚠️  Usuario '{$userData['usuario']}' ya existe. Actualizando...\n";
            
            // Actualizar usuario existente
            DB::table('usuarios')
                ->where('usuario', $userData['usuario'])
                ->update([
                    'nombre' => $userData['nombre'],
                    'rol' => $userData['rol'],
                    'password_hash' => Hash::make($userData['password']),
                    'activo' => $userData['activo'],
                    'updated_at' => now()
                ]);
            
            echo "✅ Usuario '{$userData['usuario']}' actualizado exitosamente\n";
        } else {
            // Crear nuevo usuario
            $userId = DB::table('usuarios')->insertGetId([
                'usuario' => $userData['usuario'],
                'nombre' => $userData['nombre'],
                'rol' => $userData['rol'],
                'password_hash' => Hash::make($userData['password']),
                'activo' => $userData['activo'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Usuario '{$userData['usuario']}' creado exitosamente (ID: {$userId})\n";
        }

        // Mostrar información del usuario
        echo "   📋 Nombre: {$userData['nombre']}\n";
        echo "   🔐 Usuario: {$userData['usuario']}\n";
        echo "   🏷️  Rol: {$userData['rol']}\n";
        echo "   🔑 Contraseña: {$userData['password']}\n";
        echo "   ✅ Estado: " . ($userData['activo'] ? 'Activo' : 'Inactivo') . "\n\n";
    }

    // Mostrar resumen final
    echo "📊 RESUMEN FINAL\n";
    echo "================\n";
    
    $totalUsuarios = DB::table('usuarios')->count();
    $totalDentistas = DB::table('usuarios')->where('rol', 'dentista')->count();
    $totalRecepcionistas = DB::table('usuarios')->where('rol', 'recepcionista')->count();
    $totalActivos = DB::table('usuarios')->where('activo', true)->count();
    
    echo "👥 Total de usuarios en sistema: {$totalUsuarios}\n";
    echo "🦷 Dentistas: {$totalDentistas}\n";
    echo "📋 Recepcionistas: {$totalRecepcionistas}\n";
    echo "✅ Usuarios activos: {$totalActivos}\n\n";

    // Listar todos los usuarios
    echo "📋 USUARIOS EN EL SISTEMA\n";
    echo "=========================\n";
    
    $todosUsuarios = DB::table('usuarios')->get();
    
    foreach ($todosUsuarios as $usuario) {
        $estado = $usuario->activo ? '✅' : '❌';
        echo "{$estado} ID: {$usuario->id} | Usuario: {$usuario->usuario} | Nombre: {$usuario->nombre} | Rol: {$usuario->rol}\n";
    }

    echo "\n🎉 PROCESO COMPLETADO EXITOSAMENTE\n";
    echo "==================================\n";
    echo "Los usuarios administrativos han sido creados/actualizados correctamente.\n";
    echo "Ya puedes acceder al sistema con las credenciales mostradas arriba.\n\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . "\n";
    echo "📍 Línea: " . $e->getLine() . "\n\n";
    
    echo "💡 POSIBLES SOLUCIONES:\n";
    echo "1. Verificar que la base de datos esté configurada correctamente\n";
    echo "2. Ejecutar: php artisan migrate\n";
    echo "3. Verificar credenciales en .env\n";
    echo "4. Verificar que la tabla 'usuarios' exista\n\n";
    
    exit(1);
}

?>
