<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Proceso;
use Illuminate\Http\Request;

class ProcesoController extends Controller 
{
    /**
     * Mostrar todos los procesos
     */
    public function index()
    {
        $procesos = Proceso::all();
        return view('admin.procesos.index', compact('procesos'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create()
    {
        return view('admin.procesos.create');
    }

    /**
     * Guardar un nuevo proceso
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        Proceso::create($request->only(['nombre', 'estado']));

        return redirect()->route('procesos.index')
            ->with('success', 'Proceso creado correctamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Proceso $proceso)
    {
        return view('admin.procesos.edit', compact('proceso'));
    }

    /**
     * Actualizar un proceso existente
     */
    public function update(Request $request, Proceso $proceso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'estado' => 'required|boolean',
        ]);

        $proceso->update($request->only(['nombre', 'estado']));

        return redirect()->route('procesos.index')
            ->with('success', 'Proceso actualizado correctamente.');
    }

    /**
     * Eliminar un proceso
     */
    public function destroy(Proceso $proceso)
    {
        $proceso->delete();

        return redirect()->route('procesos.index')
            ->with('success', 'Proceso eliminado correctamente.');
    }
}
