<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function index()
    {
        return Municipio::with('distrito')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_municipio' => 'required|unique:municipios,nombre_municipio',
            'id_distrito' => 'required|exists:distritos,id',
        ]);

        $municipio = Municipio::create($request->all());
        return response()->json($municipio, 201);
    }

    public function show($id)
    {
        return Municipio::with('distrito')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $municipio = Municipio::findOrFail($id);

        $request->validate([
            'nombre_municipio' => 'required|unique:municipios,nombre_municipio,' . $municipio->id,
            'id_distrito' => 'required|exists:distritos,id',
        ]);

        $municipio->update($request->all());
        return response()->json($municipio);
    }

    public function destroy($id)
    {
        $municipio = Municipio::findOrFail($id);
        $municipio->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}

