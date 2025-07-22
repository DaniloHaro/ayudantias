<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\DatoBanco;

class UsuarioController extends Controller
{

    public function obtenerPerfil()
    {
        $usuario = Auth::user();
        $usuario->load([
            'datosBanco.tipoCuenta.banco' // Carga datos bancarios con relaciones
        ]);
        return response()->json($usuario);
    }

    public function actualizarPerfil(Request $request)
    {
        //dd($request->toArray());
        //dd($request->all());

        $usuario = Auth::user();

        $validated = $request->validate([
            /*'nombres' => 'required|string|max:255',
            'paterno' => 'required|string|max:255',
            'materno' => 'nullable|string|max:255',*/
            'sexo_registral' => 'nullable|string',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string',
            'banco_id' => 'nullable|exists:banco,id',
            'tipo_cuenta_id' => 'nullable|exists:tipo_cuenta,id',
            'num_cuenta' => 'nullable|string|max:150',
        ]);

        // Actualiza datos del usuario
        $usuario = Usuario::where('rut', $usuario->rut)->firstOrFail();
        
        //dd($usuario->all());
        $usuario->update([
            'sexo_registral' => $validated['sexo_registral'],
            'email' => $validated['email'],
            'telefono' => $validated['telefono'],
        ]);
        

        // Actualiza o crea los datos bancarios más recientes
        $datosBanco = DatoBanco::where('rut', $usuario->rut)->orderByDesc('id')->first();
        // if ($datosBanco) {
        //     $datosBanco->update([
        //         'tipo_cuenta_id' => $validated['tipo_cuenta_id'],
        //         'num_cuenta' => $validated['num_cuenta'],
        //     ]);
        // } else {
        //     DatoBanco::create([
        //         'rut' => $usuario->rut,
        //         'tipo_cuenta_id' => $validated['tipo_cuenta_id'],
        //         'num_cuenta' => $validated['num_cuenta'],
        //     ]);
        // }
        if ($validated['tipo_cuenta_id'] && $validated['num_cuenta']) {
            if ($datosBanco) {
                $datosBanco->update([
                    'tipo_cuenta_id' => $validated['tipo_cuenta_id'],
                    'num_cuenta' => $validated['num_cuenta'],
                ]);
            } else {
                DatoBanco::create([
                    'rut' => $usuario->rut,
                    'tipo_cuenta_id' => $validated['tipo_cuenta_id'],
                    'num_cuenta' => $validated['num_cuenta'],
                ]);
            }
        }

        return redirect()->route('dashboard')->with('success', 'Perfil actualizado correctamente');

    }

    /////////////////////////////////////////////////////////////////////////////
    public function index()
    {
        $usuarios = Usuario::all();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'rut' => 'required|string|unique:usuarios,rut|rut_valido',
            'nombres' => 'required|string|max:500',
            'paterno' => 'required|string|max:500',
            'materno' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:500',
            'sexo_registral' => 'nullable|string|max:150',
            'genero' => 'nullable|string|max:150',
            'cuenta_pasaporte' => 'nullable|string|max:150',
            'estado' => 'required|integer',
        ]);
        // Limpiar RUT y quitar DV
        $limpio = preg_replace('/[^0-9kK]/', '', $data['rut']); // Quita puntos y guion
        $data['rut'] = substr($limpio, 0, -1); // Elimina el último carácter (el DV)
        Usuario::create($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function show($rut)
    {
        $usuario = Usuario::findOrFail($rut);
        return view('admin.usuarios.show', compact('usuario'));
    }

    public function edit($rut)
    {
        $usuario = Usuario::findOrFail($rut);
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $rut)
    {
        $usuario = Usuario::findOrFail($rut);

        $data = $request->validate([
            'nombres' => 'required|string|max:500',
            'paterno' => 'required|string|max:500',
            'materno' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:500',
            'sexo_registral' => 'nullable|string|max:150',
            'genero' => 'nullable|string|max:150',
            'cuenta_pasaporte' => 'nullable|string|max:150',
            'estado' => 'required|integer',
        ]);

        $usuario->update($data);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($rut)
    {
        $usuario = Usuario::findOrFail($rut);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
