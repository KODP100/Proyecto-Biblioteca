<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Multa;

class MultaController extends Controller
{
    // Listar todas las multas
    public function index()
    {
        return response()->json(Multa::all());
    }

    // Crear una nueva multa
    public function store(Request $request)
    {
        $request->validate([
            'rango_dias' => 'required|integer',
            'monto' => 'required|numeric',
        ]);

        $multa = Multa::create($request->all());
        return response()->json($multa, 201);
    }

    // Mostrar una multa específica
    public function show($id)
    {
        $multa = Multa::findOrFail($id);
        return response()->json($multa);
    }

    // Actualizar una multa
    public function update(Request $request, $id)
    {
        $request->validate([
            'rango_dias' => 'required|integer',
            'monto' => 'required|numeric',
        ]);

        $multa = Multa::findOrFail($id);
        $multa->update($request->all());
        return response()->json($multa);
    }

    // Eliminar una multa
    public function destroy($id)
    {
        $multa = Multa::findOrFail($id);
        $multa->delete();
        return response()->json(null, 204);
    }
}
