<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Existencia;

class ExistenciaController extends Controller
{
    // Listar todas las existencias
    public function index()
    {
        $existencias = Existencia::with('libro')->get();
        return response()->json($existencias);
    }

    // Crear una nueva existencia
    public function store(Request $request)
    {
        $request->validate([
            'id_libro' => 'required|exists:libros,id_libro',
            'existencia' => 'required|integer|min:0',
            'reservado' => 'required|integer|min:0',
            'prestado' => 'required|integer|min:0',
        ]);

        $existencia = Existencia::create($request->all());
        return response()->json($existencia, 201);
    }

    // Mostrar una existencia especifica
    public function show($id)
    {
        $existencia = Existencia::with('libro')->findOrFail($id);
        return response()->json($existencia);
    }

    // Actualizar una existencia
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_libro' => 'required|exists:libros,id_libro',
            'existencia' => 'required|integer|min:0',
            'reservado' => 'required|integer|min:0',
            'prestado' => 'required|integer|min:0',
        ]);

        $existencia = Existencia::findOrFail($id);
        $existencia->update($request->all());
        return response()->json($existencia);
    }

    // Eliminar una existencia
    public function destroy($id)
    {
        $existencia = Existencia::findOrFail($id);
        $existencia->delete();
        return response()->json(null, 204);
    }
}
