<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permiso;
use App\Models\Usuario;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; 

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::with(['usuario', 'carrera'])->get();
        return view('admin.permisos.index', compact('permisos'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $carreras = Carrera::all();
        return view('admin.permisos.create', compact('usuarios', 'carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rut' => [
                'required',
                'exists:usuarios,rut',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = Permiso::where('rut', $value)
                        ->where('tipo', $request->tipo)
                        ->where('estado', '1') // Solo bloquea si ya existe con estado activo
                        ->exists();

                    if ($exists) {
                        $fail('Este RUT ya tiene un permiso activo de este tipo.');
                    }
                },
            ],
            'id_carrera' => 'required|exists:carreras,id_carrera',
            'tipo' => 'required|string|max:150',
            'estado' => 'nullable|string|max:45',
        ]);

        Permiso::create($request->all());
        return redirect()->route('permisos.index')->with('success', 'Permiso creado correctamente.');
    }

    public function show($id)
    {
        $permiso = Permiso::with(['usuario', 'carrera'])->findOrFail($id);
        return view('admin.permisos.show', compact('permiso'));
    }

    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        $usuarios = Usuario::all();
        $carreras = Carrera::all();
        return view('admin.permisos.edit', compact('permiso', 'usuarios', 'carreras'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rut' => 'required|exists:usuarios,rut',
            'id_carrera' => 'required|exists:carreras,id_carrera',
            'tipo' => 'required|string|max:150',
            'estado' => 'nullable|string|max:45',
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->update($request->all());

        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado.');
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('permisos.index')->with('success', 'Permiso eliminado.');
    }

    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'rut', 'rut');
    }

}
