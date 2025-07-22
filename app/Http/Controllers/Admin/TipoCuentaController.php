<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\TipoCuenta;
use App\Models\Banco;
use Illuminate\Http\Request;

class TipoCuentaController extends Controller
{

    public function tiposPorBanco($id)
    {
        $tipos = TipoCuenta::where('banco_id', $id)->where('estado', 1)->get();
        return response()->json($tipos);
    }
    /////////////////////////////////////////////////////////////////////////////////
    public function index()
    {
        $tipos = TipoCuenta::with('banco')->get();
        return view('tipo_cuenta.index', compact('tipos'));
    }

    public function create()
    {
        $bancos = Banco::all();
        return view('tipo_cuenta.create', compact('bancos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_cuenta' => 'required|string|max:150',
            'banco_id' => 'required|exists:banco,id',
        ]);

        TipoCuenta::create($request->all());

        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta creado.');
    }

    public function edit($id)
    {
        $tipo = TipoCuenta::findOrFail($id);
        $bancos = Banco::all();
        return view('tipo_cuenta.edit', compact('tipo', 'bancos'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo_cuenta' => 'required|string|max:150',
            'banco_id' => 'required|exists:banco,id',
        ]);

        $tipo = TipoCuenta::findOrFail($id);
        $tipo->update($request->all());

        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta actualizado.');
    }

    public function destroy($id)
    {
        $tipo = TipoCuenta::findOrFail($id);
        $tipo->delete();

        return redirect()->route('tipo_cuenta.index')->with('success', 'Tipo de cuenta eliminado.');
    }
}