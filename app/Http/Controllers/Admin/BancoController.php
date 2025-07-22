<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banco;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    public function index()
    {
        $bancos = Banco::all();
        return view('bancos.index', compact('bancos'));
    }

    public function create()
    {
        return view('bancos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'banco' => 'required|string|max:150',
        ]);

        Banco::create($request->all());

        return redirect()->route('bancos.index')->with('success', 'Banco creado correctamente.');
    }

    public function edit($id)
    {
        $banco = Banco::findOrFail($id);
        return view('bancos.edit', compact('banco'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'banco' => 'required|string|max:150',
        ]);

        $banco = Banco::findOrFail($id);
        $banco->update($request->all());

        return redirect()->route('bancos.index')->with('success', 'Banco actualizado correctamente.');
    }

    public function destroy($id)
    {
        $banco = Banco::findOrFail($id);
        $banco->delete();

        return redirect()->route('bancos.index')->with('success', 'Banco eliminado correctamente.');
    }
}