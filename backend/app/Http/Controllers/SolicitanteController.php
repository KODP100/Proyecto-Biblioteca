<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitante;

class SolicitanteController extends Controller
{
    // Listar todos los solicitantes
    public function index()
    {
        $solicitantes = Solicitante::with('departamento', 'municipio', 'distrito')->get();
        return response()->json($solicitantes);
    }

    // Crear un nuevo solicitante
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'id_municipio' => 'required|exists:municipios,id_municipio',
            'id_distrito' => 'required|exists:distritos,id_distrito',
            'sexo' => 'required|string|max:1',
            'direccion' => 'required|string|max:255',
        ]);

        $solicitante = Solicitante::create($request->all());
        return response()->json($solicitante, 201);
    }

    // Mostrar un solicitante especifico
    public function show($id)
    {
        $solicitante = Solicitante::with('departamento', 'municipio', 'distrito')->findOrFail($id);
        return response()->json($solicitante);
    }

    // Actualizar un solicitante
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
            'id_departamento' => 'required|exists:departamentos,id_departamento',
            'id_municipio' => 'required|exists:municipios,id_municipio',
            'id_distrito' => 'required|exists:distritos,id_distrito',
            'sexo' => 'required|string|max:1',
            'direccion' => 'required|string|max:255',
        ]);

        $solicitante = Solicitante::findOrFail($id);
        $solicitante->update($request->all());
        return response()->json($solicitante);
    }

    // Eliminar un solicitante
    public function destroy($id)
    {
        $solicitante = Solicitante::findOrFail($id);
        $solicitante->delete();
        return response()->json(null, 204);
    }
}
