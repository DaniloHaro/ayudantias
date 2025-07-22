<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class SimulacionController extends Controller
{
    public function simular(Request $request)
    {
        $user = Usuario::findOrFail($request->usuario_id);

        if (!session()->has('usuario_real_id')) {
            session(['usuario_real_id' => Auth::id()]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('status', 'Estás simulando a otro usuario');
    }

    public function volver()
    {
        $realUserId = session('usuario_real_id');

        if ($realUserId) {
            $realUser = Usuario::find($realUserId);
            Auth::login($realUser);
            session()->forget('usuario_real_id');
        }

        return redirect()->route('dashboard')->with('status', 'Has vuelto a tu sesión');
    }
}
