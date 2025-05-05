<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Editorial;

class EditorialController extends Controller
{
    // Listar todas las editoriales
    public function index()
    {
        $editoriales = Editorial::all();
        return response()->json($editoriales);
    }

    // Crear una nueva editorial
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
        ]);

        $editorial = Editorial::create($request->all());
        return response()->json($editorial, 201);
    }

    // Mostrar una editorial especifica
    public function show($id)
    {
        $editorial = Editorial::findOrFail($id);
        return response()->json($editorial);
    }

    // Actualizar una editorial
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:15',
            'correo' => 'required|email|max:255',
        ]);

        $editorial = Editorial::findOrFail($id);
        $editorial->update($request->all());
        return response()->json($editorial);
    }

    // Eliminar una editorial
    public function destroy($id)
    {
        $editorial = Editorial::findOrFail($id);
        $editorial->delete();
        return response()->json(null, 204);
    }
}
