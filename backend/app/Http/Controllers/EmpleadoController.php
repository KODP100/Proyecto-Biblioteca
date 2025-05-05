<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::with(['departamento', 'municipio'])->get();

        return response()->json($empleados);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer|min:0',
            'telefono' => 'nullable|string|max:20',
            'id_departamento' => 'required|exists:departamentos,id',
            'id_municipio' => 'required|exists:municipios,id',
            'alta_empleado' => 'nullable|date',
            'baja_empleado' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
        ]);

        $empleado = Empleado::create($request->all());

        return response()->json($empleado, 201);
    }

    public function show($id)
    {
        $empleado = Empleado::with(['departamento', 'municipio'])->findOrFail($id);
        return response()->json($empleado);
    }

    public function update(Request $request, $id)
    {
        $empleado = Empleado::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|integer|min:0',
            'telefono' => 'nullable|string|max:20',
            'id_departamento' => 'required|exists:departamentos,id',
            'id_municipio' => 'required|exists:municipios,id',
            'alta_empleado' => 'nullable|date',
            'baja_empleado' => 'nullable|date',
            'direccion' => 'nullable|string|max:255',
        ]);

        $empleado->update($request->all());

        return response()->json($empleado);
    }

    public function destroy($id)
    {
        $empleado = Empleado::findOrFail($id);
        $empleado->delete();

        return response()->json(['message' => 'Empleado eliminado correctamente']);
    }
}
