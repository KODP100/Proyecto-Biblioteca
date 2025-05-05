<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EstadoLibro;

class EstadoLibroController extends Controller
{
    // Listar todos los estados de libros
    public function index()
    {
        $estados = EstadoLibro::all();
        return response()->json($estados);
    }

    // Crear un nuevo estado de libro
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $estado = EstadoLibro::create($request->all());
        return response()->json($estado, 201);
    }

    // Mostrar un estado de libro especifico
    public function show($id)
    {
        $estado = EstadoLibro::findOrFail($id);
        return response()->json($estado);
    }

    // Actualizar un estado de libro
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $estado = EstadoLibro::findOrFail($id);
        $estado->update($request->all());
        return response()->json($estado);
    }

    // Eliminar un estado de libro
    public function destroy($id)
    {
        $estado = EstadoLibro::findOrFail($id);
        $estado->delete();
        return response()->json(null, 204);
    }
}
