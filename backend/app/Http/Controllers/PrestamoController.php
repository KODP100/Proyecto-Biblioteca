<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Existencia;

class PrestamoController extends Controller
{
     // Listar todos los prestamos
     public function index()
     {
         $prestamos = Prestamo::with('libro')->get();
         return response()->json($prestamos);
     }
 
     // Crear un nuevo prestamo
     public function store(Request $request)
     {
         $request->validate([
             'id_libro' => 'required|exists:libros,id_libro',
             'numero_ejemplares' => 'required|integer|min:1',
         ]);
 
         // Verificar disponibilidad del libro
         $libro = Libro::findOrFail($request->id_libro);
         $existencias = $libro->existencias()->where('existencia', '>', 0)->first();
 
         if (!$existencias || $existencias->existencia < $request->numero_ejemplares) {
             return response()->json(['error' => 'No hay suficientes ejemplares disponibles.'], 400);
         }
 
         // Registrar el prestamo
         $prestamo = Prestamo::create([
             'id_libro' => $request->id_libro,
             'numero_ejemplares' => $request->numero_ejemplares,
             'fecha_prestamo' => now(),
             'fecha_devolucion_esperada' => now()->addDays(5),
         ]);
 
         // Actualizar existencias
         $existencias->decrement('existencia', $request->numero_ejemplares);
         $existencias->increment('prestado', $request->numero_ejemplares);
 
         return response()->json($prestamo, 201);
     }
 
     // Mostrar un prestamo especifico
     public function show($id)
     {
         $prestamo = Prestamo::with('libro')->findOrFail($id);
         return response()->json($prestamo);
     }
 
     // Actualizar un prestamo
     public function update(Request $request, $id)
     {
         $request->validate([
             'id_libro' => 'required|exists:libros,id_libro',
             'numero_ejemplares' => 'required|integer|min:1',
         ]);
 
         $prestamo = Prestamo::findOrFail($id);
         $prestamo->update($request->all());
         return response()->json($prestamo);
     }
 
     // Eliminar un prestamo
     public function destroy($id)
     {
         $prestamo = Prestamo::findOrFail($id);
         $prestamo->delete();
         return response()->json(null, 204);
     }
}
