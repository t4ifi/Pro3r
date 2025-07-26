<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;

class PacienteController extends Controller
{
    public function index()
    {
        return response()->json(Paciente::all());
    }

    public function show($id)
    {
        $paciente = Paciente::findOrFail($id);
        return response()->json($paciente);
    }

    public function update(Request $request, $id)
    {
        $paciente = Paciente::findOrFail($id);
        $paciente->nombre_completo = $request->input('nombre_completo');
        $paciente->telefono = $request->input('telefono');
        $paciente->fecha_nacimiento = $request->input('fecha_nacimiento');
        $paciente->save();
        return response()->json(['message' => 'Paciente actualizado', 'paciente' => $paciente]);
    }

    public function store(Request $request)
    {
        $paciente = new Paciente();
        $paciente->nombre_completo = $request->input('nombre_completo');
        $paciente->telefono = $request->input('telefono');
        $paciente->fecha_nacimiento = $request->input('fecha_nacimiento');
        $paciente->save();
        return response()->json(['message' => 'Paciente creado', 'paciente' => $paciente], 201);
    }
}
