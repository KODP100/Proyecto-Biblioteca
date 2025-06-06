<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    // GET /api/autores
    public function index()
    {
        return Autor::all();
    }

    // POST /api/autores
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'nacionalidad' => 'required|string|max:255',
        ]);

        $autor = Autor::create($request->all());
        return response()->json($autor, 201);
    }

    // GET /api/autores/{id}
    public function show($id)
    {
        return Autor::findOrFail($id);
    }

    // PUT/PATCH /api/autores/{id}
    public function update(Request $request, $id)
    {
        $autor = Autor::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'apellido' => 'sometimes|required|string|max:255',
            'nacionalidad' => 'sometimes|required|string|max:255',
        ]);

        $autor->update($request->all());
        return response()->json($autor);
    }

    // DELETE /api/autores/{id}
    public function destroy($id)
    {
        Autor::destroy($id);
        return response()->json(null, 204);
    }
}
