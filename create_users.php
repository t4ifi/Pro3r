<?php

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

// Crear dentista
$dentista = new Usuario();
$dentista->usuario = 'dentista';
$dentista->nombre = 'Dr. Juan Pérez';
$dentista->rol = 'dentista';
$dentista->password_hash = Hash::make('123456');
$dentista->activo = true;
$dentista->save();

// Crear recepcionista
$recepcionista = new Usuario();
$recepcionista->usuario = 'recepcionista';
$recepcionista->nombre = 'María González';
$recepcionista->rol = 'recepcionista';
$recepcionista->password_hash = Hash::make('123456');
$recepcionista->activo = true;
$recepcionista->save();

echo "Usuarios creados exitosamente\n";
echo "Dentista ID: " . $dentista->id . "\n";
echo "Recepcionista ID: " . $recepcionista->id . "\n";
