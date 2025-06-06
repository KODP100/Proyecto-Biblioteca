<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        return Prestamo::with(['libro', 'multa'])->paginate(10);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_libro' => 'required|exists:libros,id',
            'numero_ejemplares' => 'required|integer|min:1',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion_esperada' => 'required|date|after_or_equal:fecha_prestamo',
            'id_multa' => 'nullable|exists:multas,id',
            'monto_multa' => 'nullable|numeric',
        ]);

        return Prestamo::create($request->all());
    }

    public function update(Request $request, Prestamo $prestamo)
    {
        $request->validate([
            'id_libro' => 'required|exists:libros,id',
            'numero_ejemplares' => 'required|integer|min:1',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion_esperada' => 'required|date|after_or_equal:fecha_prestamo',
            'id_multa' => 'nullable|exists:multas,id',
            'monto_multa' => 'nullable|numeric',
        ]);

        $prestamo->update($request->all());
        return response()->json(['message' => 'Actualizado correctamente']);
    }

    public function destroy(Prestamo $prestamo)
    {
        $prestamo->delete();
        return response()->json(['message' => 'Eliminado']);
    }
}
