<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Multa;

class MultaController extends Controller
{
    // Listar todas las multas
    public function index()
    {
        $multas = Multa::with('devolucion')->get();
        return response()->json($multas);
    }

    // Crear una nueva multa
    public function store(Request $request)
    {
        $request->validate([
            'rango_dias' => 'required|integer',
            'monto' => 'required|numeric',
            'id_devolucion' => 'required|exists:devoluciones,id_devolucion',
        ]);

        $multa = Multa::create($request->all());
        return response()->json($multa, 201);
    }

    // Mostrar una multa especifica
    public function show($id)
    {
        $multa = Multa::with('devolucion')->findOrFail($id);
        return response()->json($multa);
    }

    // Actualizar una multa
    public function update(Request $request, $id)
    {
        $request->validate([
            'rango_dias' => 'required|integer',
            'monto' => 'required|numeric',
            'id_devolucion' => 'required|exists:devoluciones,id_devolucion',
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
