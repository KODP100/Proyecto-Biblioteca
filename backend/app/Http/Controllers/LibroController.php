<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;

class LibroController extends Controller
{
    // Listar todos los libros
    public function index()
    {
        $libros = Libro::with('categoria', 'editorial', 'autor', 'estadoLibro')->get();
        return response()->json($libros);
    }

    // Crear un nuevo libro
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_editorial' => 'required|exists:editoriales,id_editorial',
            'ejemplares_disponibles' => 'required|integer|min:0',
            'anio_publicacion' => 'required|integer',
            'id_autor' => 'required|exists:autores,id_autor',
            'id_estado_libro' => 'required|exists:estado_libros,id_estado_libro',
        ]);

        $libro = Libro::create($request->all());
        return response()->json($libro, 201);
    }

    // Mostrar un libro especifico
    public function show($id)
    {
        $libro = Libro::with('categoria', 'editorial', 'autor', 'estadoLibro')->findOrFail($id);
        return response()->json($libro);
    }

    // Actualizar un libro
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_editorial' => 'required|exists:editoriales,id_editorial',
            'ejemplares_disponibles' => 'required|integer|min:0',
            'anio_publicacion' => 'required|integer',
            'id_autor' => 'required|exists:autores,id_autor',
            'id_estado_libro' => 'required|exists:estado_libros,id_estado_libro',
        ]);

        $libro = Libro::findOrFail($id);
        $libro->update($request->all());
        return response()->json($libro);
    }

    // Eliminar un libro
    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();
        return response()->json(null, 204);
    }
}
