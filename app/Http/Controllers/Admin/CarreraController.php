<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrera;
use Illuminate\Http\Request;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        return view('admin.carreras.index', compact('carreras'));
    }

    public function create()
    {
        return view('admin.carreras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ucampus' => 'nullable|string|max:45',
            'nombre' => 'required|string|max:150',
            'estado' => 'required|integer',
        ]);

        Carrera::create($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera creada correctamente.');
    }

    public function show($id)
    {
        $carrera = Carrera::findOrFail($id);
        return view('admin.carreras.show', compact('carrera'));
    }

    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);
        return view('admin.carreras.edit', compact('carrera'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_ucampus' => 'nullable|string|max:45',
            'nombre' => 'required|string|max:150',
            'estado' => 'required|integer',
        ]);

        $carrera = Carrera::findOrFail($id);
        $carrera->update($request->all());

        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada correctamente.');
    }

    public function destroy($id)
    {
        $carrera = Carrera::findOrFail($id);
        $carrera->delete();

        return redirect()->route('carreras.index')->with('success', 'Carrera eliminada.');
    }
}

