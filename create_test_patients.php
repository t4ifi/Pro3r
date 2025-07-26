<?php

require 'vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\Paciente;

try {
    // Crear algunos pacientes de prueba
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

    foreach ($pacientes as $pacienteData) {
        $paciente = Paciente::create($pacienteData);
        echo "Paciente creado: {$paciente->nombre_completo} (ID: {$paciente->id})\n";
    }

    echo "\n¡Pacientes de prueba creados exitosamente!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
