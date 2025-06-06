<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    // Listar todos los libros
    public function index()
    {
        return Libro::with(['editorial', 'categoria', 'estadoLibro', 'autor'])->paginate(10);
    }

    // Mostrar un solo libro
    public function show($id)
    {
        $libro = Libro::with(['editorial', 'categoria', 'estadoLibro', 'autor'])->findOrFail($id);
        return response()->json($libro);
    }

    // Crear un nuevo libro
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'anio_publicacion' => 'required|digits:4|integer',
            'num_paginas' => 'required|integer|min:1',
            'id_editorial' => 'required|exists:editoriales,id',
            'id_categoria' => 'required|exists:categorias,id',
            'id_estado_libro' => 'required|exists:estado_libros,id',
            'id_autor' => 'required|exists:autores,id',
            'existencias' => 'required|integer|min:0',
        ]);

        $libro = Libro::create($validated);
        return response()->json($libro, 201);
    }

    // Actualizar libro
    public function update(Request $request, $id)
    {
        $libro = Libro::findOrFail($id);

        $validated = $request->validate([
            'titulo' => 'sometimes|required|string|max:255',
            'anio_publicacion' => 'sometimes|required|digits:4|integer',
            'num_paginas' => 'sometimes|required|integer|min:1',
            'id_editorial' => 'sometimes|required|exists:editoriales,id',
            'id_categoria' => 'sometimes|required|exists:categorias,id',
            'id_estado_libro' => 'sometimes|required|exists:estado_libros,id',
            'id_autor' => 'sometimes|required|exists:autores,id',
            'existencias' => 'required|integer|min:0',
        ]);

        $libro->update($validated);
        return response()->json($libro);
    }

    // Eliminar libro
    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return response()->json(['message' => 'Libro eliminado correctamente']);
    }
}
