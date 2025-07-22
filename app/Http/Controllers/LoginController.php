<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Permiso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use phpCAS;

class LoginController extends Controller
{
    private function buscarCarrera($idCarrera): ?string
    {
        return match ($idCarrera) {
            // Antropología
            '2843', '2841', '2842', '100284', '102843' => '1',
            // Educación
            '10390', '11030304' => '2',
            // Psicología
            '10286', '286' => '3',
            // Sociología
            '10287', '287' => '4',
            // Trabajo Social
            '11030301', '11030302' => '5',
            default => null,
        };
    }

    private function obtenerCarrerasAlumno($rut)
    {
        $response = Http::withBasicAuth(
            '1b83813aa238536d317a70b72a854629',
            '7c5328036b7eb4bc'
        )->get('https://ucampus.uchile.cl/api/0/facso_mufasa/carreras_alumnos', [
            'rut' => $rut
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        Log::error('Error al obtener carreras desde Ucampus', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    public function index(Request $request)
    {
        if ($request->has('motivo')) {
            $mensajes = [
                'sin_permiso' => 'No posees permiso para ingresar al sistema!',
                'no_usuario'  => 'El usuario no está registrado en el sistema!',
            ];
            if (isset($mensajes[$request->motivo])) {
                session()->flash('mensaje', $mensajes[$request->motivo]);
            }
        }
        // VERIFICAR sesión de Laravel
        if (Auth::check()) {
            \Log::info("Usuario aún autenticado al entrar en /, redirigiendo al dashboard.");
            return redirect('admin/dashboard');
        }

        return view('home');
    }



    public function login(Request $request)
    {
        phpCAS::client(CAS_VERSION_2_0, 'sso2zoom.uchile.cl', 443, '');
        phpCAS::setNoCasServerValidation();
        phpCAS::forceAuthentication();

        // $username = phpCAS::getUser();
        // $datos = $this->obtenerDatosPersona($username);
        // //dd($datos);
        // $indiv_id = $datos["indiv_id"];
        // $rut = strpos($indiv_id, "P") === false ? substr(intval($indiv_id), 0, -1) : $indiv_id;
        $username = phpCAS::getUser();
        $datos = $this->obtenerDatosPersona($username);
        $indiv_id = $datos["indiv_id"];

        if (strpos($indiv_id, 'P') === false) {
            // Es un RUT: quitar el dígito verificador y eliminar ceros a la izquierda
            $rut = ltrim(substr($indiv_id, 0, -1), '0');
        } else {
            // Es un pasaporte, se mantiene completo
            $rut = $indiv_id;
        }

        $nombreCompleto = $datos["nombres"] . ' ' . $datos["paterno"] . ' ' . $datos["materno"];

        $usuario = Usuario::firstOrCreate(
            ['rut' => $rut],
            [
                'nombres' => $datos["nombres"],
                'paterno' => $datos["paterno"],
                'materno' => $datos["materno"],
                'nombre_social' => $datos['nombre_social'],
                'cuenta_pasaporte' => $username,
                'estado'  => 1,
                //ingresar correo de udatos
            ]
        );
        //dd($rut);
        if (!$this->asignarPermisoSiCorresponde($usuario, $rut)) {
            return redirect()->route('redirect.cas.logout', ['motivo' => $usuario->wasRecentlyCreated ? 'no_usuario' : 'sin_permiso']);
        }

        Auth::login($usuario);
        return redirect()->intended('admin/dashboard');
    }

    private function obtenerDatosPersona($username)
    {
        $certificate_location = 'C:\wamp64\cacert.pem'; // solo local

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://facso.cl/app_facultad/facultad/api/api.php?num=1&pass=' . $username,
            //CURLOPT_URL => 'https://facso.cl/app_facultad/facultad/api/api.php?num=1&pass=' . 'pia.lepe.t',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => $certificate_location,
            CURLOPT_SSL_VERIFYPEER => $certificate_location
        ]);

        $resultado = curl_exec($curl);
        $persona = json_decode(json_decode($resultado), true)["data"]["getRowsPersona"]["persona"][0];

        //dd($persona);
        return $persona;
    }

    private function asignarPermisoSiCorresponde($usuario, $rut): bool
    {
        $permisosActivos = Permiso::where('rut', $rut)->where('estado', 1)->exists();
        if ($permisosActivos) return true;

        $carreras = $this->obtenerCarrerasAlumno($rut);
        if (!$carreras) return false;

        foreach ($carreras as $carrera) {
            if ($carrera['estado_texto'] === 'Regular' && $carrera['tipo_titulo'] === '2') {
                $idCarrera = $this->buscarCarrera($carrera['id_carrera']);
                if (!$idCarrera) continue;

                $yaTienePermiso = Permiso::where('rut', $rut)
                    ->where('id_carrera', $idCarrera)
                    ->where('estado', 1)
                    ->exists();

                if (!$yaTienePermiso) {
                    Permiso::create([
                        'id_carrera' => $idCarrera,
                        'rut' => $rut,
                        'tipo' => 'Estudiante',
                        'estado' => 1,
                    ]);
                }
                return true;
            }
        }

        return false;
    }

    public function redirectCasLogout(Request $request, $motivo = null)
    {
        // Cierra sesión de Laravel
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Configura phpCAS
        phpCAS::client(CAS_VERSION_2_0, 'sso2zoom.uchile.cl', 443, '');
        phpCAS::setNoCasServerValidation();

        // Redirige a CAS con logout
        $returnUrl = url('/?logout=1&motivo=' . $motivo);

        // Solo si ya estaba autenticado con CAS, forzar el logout CAS
        if (phpCAS::isAuthenticated()) {
            phpCAS::logoutWithRedirectService($returnUrl);
        } else {
            return redirect($returnUrl);
        }
    }



    public function logout(Request $request)
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        $request->session()->flush();
        return redirect()->route('redirect.cas.logout');
    }

    public function dashboard()
    {
        $usuario = Auth::user();
        $datos_usuario = Usuario::findOrFail($usuario->rut);
        //dd($usuario)->all();
        return view('admin/dashboard', compact('datos_usuario')); 
    }
}
