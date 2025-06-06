<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    public function index()
    {
        return Devolucion::with('prestamo.libro')->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_prestamo' => 'required|exists:prestamos,id|unique:devoluciones,id_prestamo',
            'fecha_devolucion' => 'required|date',
            'estado_libro' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        return Devolucion::create($data);
    }

    public function update(Request $request, $id)
    {
        $devolucion = Devolucion::findOrFail($id);

        $data = $request->validate([
            'fecha_devolucion' => 'required|date',
            'estado_libro' => 'nullable|string|max:255',
            'observaciones' => 'nullable|string',
        ]);

        $devolucion->update($data);
        return $devolucion;
    }

    public function destroy($id)
    {
        Devolucion::destroy($id);
        return response()->noContent();
    }
}
