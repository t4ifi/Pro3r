<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)->where('activo', true)->first();
        if (!$usuario || !Hash::check($request->password, $usuario->password_hash)) {
            return response()->json(['message' => 'Usuario o contraseña incorrectos.'], 401);
        }

        // Aquí podrías generar un token JWT o Laravel Sanctum si lo deseas
        // Por ahora, solo devolvemos los datos básicos
        return response()->json([
            'id' => $usuario->id,
            'usuario' => $usuario->usuario,
            'nombre' => $usuario->nombre,
            'rol' => $usuario->rol,
            'message' => 'Inicio de sesión exitoso.'
        ]);
    }
}
