<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EtapaProceso;
use App\Models\Proceso;
use Illuminate\Http\Request;

class EtapaProcesoController extends Controller
{
    /**
     * Mostrar listado de etapas de un proceso
     */
    public function index(Proceso $proceso)
    {
        $etapas = $proceso->etapas()->get();

        return view('admin.etapas.index', compact('proceso', 'etapas'));
    }

    /**
     * Formulario de creación
     */
    public function create(Proceso $proceso)
    {
        return view('admin.etapas.create', compact('proceso'));
    }

    /**
     * Guardar una nueva etapa
     */
    public function store(Request $request, Proceso $proceso)
    {
        $request->validate([
            'etapa_proceso' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $proceso->etapas()->create($request->only([
            'etapa_proceso',
            'fecha_inicio',
            'fecha_fin',
            'descripcion',
            'estado'
        ]));

        return redirect()->route('procesos.etapas.index', $proceso)
            ->with('success', 'Etapa creada correctamente.');
    }

    /**
     * Formulario de edición
     */
    public function edit(Proceso $proceso, EtapaProceso $etapa)
    {
        return view('admin.etapas.edit', compact('proceso', 'etapa'));
    }

    /**
     * Actualizar una etapa
     */
    public function update(Request $request, Proceso $proceso, EtapaProceso $etapa)
    {
        $request->validate([
            'etapa_proceso' => 'required|string|max:255',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'descripcion' => 'nullable|string',
            'estado' => 'required|boolean',
        ]);

        $etapa->update($request->only([
            'etapa_proceso',
            'fecha_inicio',
            'fecha_fin',
            'descripcion',
            'estado'
        ]));

        return redirect()->route('procesos.etapas.index', $proceso)
            ->with('success', 'Etapa actualizada correctamente.');
    }

    /**
     * Eliminar una etapa
     */
    public function destroy(Proceso $proceso, EtapaProceso $etapa)
    {
        $etapa->delete();

        return redirect()->route('procesos.etapas.index', $proceso)
            ->with('success', 'Etapa eliminada correctamente.');
    }
}
