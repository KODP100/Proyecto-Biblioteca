<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Autor;

class AutorController extends Controller
{
    // Listar todos los autores
    public function index()
    {
        // Cargamos autores con su relación de libros y editorial
        $autores = Autor::with('libros', 'editorial')->get();
        return response()->json($autores);
    }

    // Crear un nuevo autor
    public function store(Request $request)
    {
        // Validamos los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
            'editorial_id' => 'required|exists:editoriales,id', // Corregido: 'exists:editoriales,id'
        ]);

        // Creamos el nuevo autor con los datos recibidos
        $autor = Autor::create($request->all());
        return response()->json($autor, 201); // Retornamos el autor creado
    }

    // Mostrar un autor especifico
    public function show($id)
    {
        // Obtenemos un autor por su ID, junto con sus libros y la editorial asociada
        $autor = Autor::with('libros', 'editorial')->findOrFail($id);
        return response()->json($autor);
    }

    // Actualizar un autor
    public function update(Request $request, $id)
    {
        // Validamos los datos del formulario para la actualización
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
            'editorial_id' => 'required|exists:editoriales,id', // Corregido: 'exists:editoriales,id'
        ]);

        // Encontramos el autor por su ID y actualizamos sus datos
        $autor = Autor::findOrFail($id);
        $autor->update($request->all());
        return response()->json($autor);
    }

    // Eliminar un autor
    public function destroy($id)
    {
        // Encontramos el autor por su ID y lo eliminamos
        $autor = Autor::findOrFail($id);
        $autor->delete();
        return response()->json(null, 204); // Retornamos una respuesta vacía 204
    }
}
