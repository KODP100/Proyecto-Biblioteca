<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        return Empleado::with(['departamento', 'municipio', 'distrito', 'user'])->get();
    }

    public function store(Request $request)
    {
        $empleado = Empleado::create($request->all());
        return response()->json($empleado, 201);
    }

    public function show($id)
    {
        return Empleado::with(['departamento', 'municipio', 'distrito', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->update($request->all());
        return response()->json($empleado);
    }

    public function destroy($id)
    {
        Empleado::destroy($id);
        return response()->json(null, 204);
    }
}
