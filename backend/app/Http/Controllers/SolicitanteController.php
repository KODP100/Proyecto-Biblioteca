<?php

namespace App\Http\Controllers;

use App\Models\Solicitante;
use Illuminate\Http\Request;

class SolicitanteController extends Controller
{
    // GET /api/solicitantes
    public function index()
    {
        return Solicitante::with(['departamento', 'municipio', 'distrito', 'user'])->get();
    }

    // POST /api/solicitantes
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer|min:0',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|unique:solicitantes,correo',
            'id_departamento' => 'required|exists:departamentos,id',
            'id_municipio' => 'required|exists:municipios,id',
            'id_distrito' => 'required|exists:distritos,id',
            'user_id' => 'required|exists:users,id',
            'alta_solicitante' => 'nullable|date',
            'baja_solicitante' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
        ]);

        $solicitante = Solicitante::create($request->all());
        return response()->json($solicitante, 201);
    }

    // GET /api/solicitantes/{id}
    public function show($id)
    {
        return Solicitante::with(['departamento', 'municipio', 'distrito', 'user'])->findOrFail($id);
    }

    // PUT/PATCH /api/solicitantes/{id}
    public function update(Request $request, $id)
    {
        $solicitante = Solicitante::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'edad' => 'sometimes|required|integer|min:0',
            'telefono' => 'sometimes|required|string|max:20',
            'correo' => 'sometimes|required|email|unique:solicitantes,correo,' . $solicitante->id,
            'id_departamento' => 'sometimes|required|exists:departamentos,id',
            'id_municipio' => 'sometimes|required|exists:municipios,id',
            'id_distrito' => 'sometimes|required|exists:distritos,id',
            'user_id' => 'sometimes|required|exists:users,id',
            'alta_solicitante' => 'nullable|date',
            'baja_solicitante' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
        ]);

        $solicitante->update($request->all());
        return response()->json($solicitante);
    }

    // DELETE /api/solicitantes/{id}
    public function destroy($id)
    {
        Solicitante::destroy($id);
        return response()->json(null, 204);
    }
}
