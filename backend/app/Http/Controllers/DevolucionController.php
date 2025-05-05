<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Devolucion;

class DevolucionController extends Controller
{
    // Listar todas las devoluciones
    public function index()
    {
        $devoluciones = Devolucion::with('prestamo')->get();
        return response()->json($devoluciones);
    }

    // Crear una nueva devolucion
    public function store(Request $request)
    {
        $request->validate([
            'id_prestamo' => 'required|exists:prestamos,id_prestamo',
            'fecha_devolucion' => 'required|date',
        ]);

        $devolucion = Devolucion::create($request->all());
        return response()->json($devolucion, 201);
    }

    // Mostrar una devolucion especifica
    public function show($id)
    {
        $devolucion = Devolucion::with('prestamo')->findOrFail($id);
        return response()->json($devolucion);
    }

    // Actualizar una devolucion
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_prestamo' => 'required|exists:prestamos,id_prestamo',
            'fecha_devolucion' => 'required|date',
        ]);

        $devolucion = Devolucion::findOrFail($id);
        $devolucion->update($request->all());
        return response()->json($devolucion);
    }

    // Eliminar una devolucion
    public function destroy($id)
    {
        $devolucion = Devolucion::findOrFail($id);
        $devolucion->delete();
        return response()->json(null, 204);
    }
}
