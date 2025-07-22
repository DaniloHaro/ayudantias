<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asignatura;
use App\Models\Carrera;
use App\Models\Usuario;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function info($id)
    {
        $asignatura = Asignatura::with('usuario')->find($id);
        if (!$asignatura) {
            return response()->json(['error' => 'Asignatura no encontrada'], 404);
        }

        return response()->json([
            'nombre' => $asignatura->nombre,
            'codigo' => $asignatura->codigo,
            'descripcion' => $asignatura->descripcion,
            'rut' => $asignatura->rut,
            'seccion' => $asignatura->seccion,
            'bloque_1' => $asignatura->bloque_1,
            'bloque_2' => $asignatura->bloque_2,
            'bloque_3' => $asignatura->bloque_3,
            'cupos' => $asignatura->cupos,
            'nombre_completo' => $asignatura->usuario 
                ? $asignatura->usuario->nombres . ' ' . $asignatura->usuario->paterno . ' ' . $asignatura->usuario->materno
                : 'No disponible',
        ]);
    }

    ////////////////////////////////////////////////////////////////////////////
    public function index()
    {
        $asignaturas = Asignatura::with(['carrera', 'usuario'])->get();
        return view('admin.asignaturas.index', compact('asignaturas'));
    }

    public function create()
    {
        $carreras = Carrera::all(); 
        $usuarios = Usuario::all(); // o podrías filtrar por rol
        return view('admin.asignaturas.create', compact('carreras', 'usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'id_carrera' => 'required|exists:carreras,id_carrera',
            'rut' => 'required|exists:usuarios,rut',
            'seccion' => 'nullable|string|max:150',
            'bloque_1' => 'nullable|string|max:150',
            'bloque_2' => 'nullable|string|max:150',
            'bloque_3' => 'nullable|string|max:150',
            'cupos' => 'nullable|integer|min:0',
            'estado' => 'nullable|in:0,1',
        ]);

        Asignatura::create($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura creada correctamente.');
    }

    public function show(Asignatura $asignatura)
    {
        $asignatura->load(['carrera', 'usuario']);
        return view('admin.asignaturas.show', compact('asignatura'));
    }

    public function edit(Asignatura $asignatura)
    {
        $carreras = Carrera::all();
        $usuarios = Usuario::all();
        return view('admin.asignaturas.edit', compact('asignatura', 'carreras', 'usuarios'));
    }

    public function update(Request $request, Asignatura $asignatura)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'id_carrera' => 'required|exists:carreras,id_carrera',
            'rut' => 'required|exists:usuarios,rut',
            'seccion' => 'nullable|string|max:150',
            'bloque_1' => 'nullable|string|max:150',
            'bloque_2' => 'nullable|string|max:150',
            'bloque_3' => 'nullable|string|max:150',
            'cupos' => 'nullable|integer|min:0',
            'estado' => 'nullable|in:0,1',
        ]);

        $asignatura->update($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    public function destroy(Asignatura $asignatura)
    {
        $asignatura->delete();

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada correctamente.');
    }
}
