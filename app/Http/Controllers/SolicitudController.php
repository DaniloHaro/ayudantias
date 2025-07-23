<?php

namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\Asignatura;
use App\Models\Permiso;
use App\Models\Carrera;
use App\Models\DatoBanco;
use App\Models\TipoCuenta;
use App\Models\Usuario;
use App\Models\Banco;
use App\Models\HistorialSolicitud;
use App\Models\Proceso;
use App\Mail\SolicitudCreadaDocente;
use App\Mail\SolicitudSeleccionadaMail;
use App\Mail\SolicitudRechazadaMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class SolicitudController extends Controller
{
    public function asignaturasPorCarrera($id_carrera)
    {
        $asignaturas = DB::table('asignatura')
                        ->join('usuarios', 'usuarios.rut', '=', 'asignatura.rut')
                        ->where('asignatura.id_carrera', $id_carrera)
                        ->where('asignatura.estado', 1)
                        ->select(
                            'asignatura.id_asigantura',
                            'asignatura.nombre',
                            'asignatura.seccion as se',
                            DB::raw("CONCAT(usuarios.nombres, ' ', usuarios.paterno, ' ',usuarios.materno) as docente")
                        )
                        ->get();
        return response()->json($asignaturas);
        // $asignaturas = Asignatura::where('asignatura.id_carrera', $id_carrera)
        //             ->where('asignatura.estado', 1)
        //             ->join('usuarios', 'usuarios.rut', '=', 'asignatura.rut') // ajusta nombre si es diferente
        //             ->select(
        //                 'asignatura.id_asigantura',
        //                 'asignatura.nombre',
        //                 'asignatura.seccion as se',
        //                 'usuarios.nombre as docente'
        //             )
        //             ->get();
    }

    private function obtenerTotalSCTDesdeUcampus($rut)
    {
        $user = '1b83813aa238536d317a70b72a854629';
        $pass = '7c5328036b7eb4bc';
        // $user = 'e82061941f3bbe9fa8d9558dd4c5dea2';
        // $pass = '5d2cf21964cbdca5';

        $baseUrl = 'https://ucampus.uchile.cl/api/0/facso_mufasa/';

        // Llamadas API
        $cursos = Http::withBasicAuth($user, $pass)
            ->get($baseUrl . 'cursos_inscritos', [
                'rut' => $rut,
                'periodo' => 'todos'
            ])->json();

        $homologados = Http::withBasicAuth($user, $pass)
            ->get($baseUrl . 'cursos_homologados', [
                'rut' => $rut,
                'periodo' => 'todos'
            ])->json();

        $persona = Http::withBasicAuth($user, $pass)
            ->get($baseUrl . 'personas', [
                'rut' => $rut
            ])->json();

        $suma = 0;

        $llamadas = 0;
        $llamadas = ($cursos['llamadas']);
        
        foreach ($cursos as $curso) {
            if (($curso['estado_final'] ?? '') === 'Aprobado') {
                $suma += floatval($curso['ud'] ?? 0);
            }
        }

        // Cursos homologados
        foreach ($homologados as $homologado) {
            $suma += floatval($homologado['ud'] ?? 0);
        }

        return [
            'total' => $suma,
            'estado' => $persona['estado'] ?? 'Desconocido',
            'llamadas' => $llamadas
        ];
    }
    private function obtenerMatriculaAlumno($rut)
    {
        $response = Http::withBasicAuth(
            '1b83813aa238536d317a70b72a854629',
            '7c5328036b7eb4bc'
        )->get('https://ucampus.uchile.cl/api/0/facso_mufasa/carreras_alumnos', [
            'rut' => $rut
        ]);

        if ($response->successful()) {
            $datos = $response->json();

            foreach ($datos as $carrera) {
                if ($carrera['estado_texto'] === 'Regular') {
                    return $carrera['matricula'] ?? null;
                }
            }
            return null;
        }
        \Log::error('Error al obtener matrícula desde Ucampus', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return null;
    }

    private function obtenerNombreCompletoDesdeUcampus($rut)
    {
        $response = Http::withBasicAuth(
            '1b83813aa238536d317a70b72a854629',
            '7c5328036b7eb4bc'
        )->get('https://ucampus.uchile.cl/api/0/facso_mufasa/personas', [
            'rut' => $rut
        ]);

        if ($response->successful()) {
            $datos = $response->json();
            //$persona = $datos[0];
            return trim(
                $datos['nombre1'] . ' ' .
                ($datos['nombre2'] ?? '') . '<br>' .
                $datos['apellido1'] . ' ' .
                ($datos['apellido2'] ?? '')
            );
        }
        //return $persona; //dd($persona);//return response()->json($datos);
        return 'Nombre no disponible.';
    }

    public function solicitudesCoordinador(){
        $subconsulta = DB::table('historial_solicitud as hs1')
                        ->select(DB::raw('MAX(hs1.id_historial)'))
                        ->join('solicitud as s', 's.id_solicitud', '=', 'hs1.id_solicitud')
                        ->join('asignatura as a', 'a.id_asigantura', '=', 's.id_asigantura')
                        /*->where('a.rut', $usuario->rut)*/
                        ->where('hs1.estado', 1)
                        ->where('s.estado',1)
                        ->groupBy('hs1.id_solicitud');

        $solicitudes = HistorialSolicitud::join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
            ->join('asignatura', 'asignatura.id_asigantura', '=', 'solicitud.id_asigantura')
            ->join('usuarios','usuarios.rut','=','solicitud.pers_dig')
            ->select(
                    'asignatura.nombre as nombre_asignatura', 
                    'solicitud.pers_dig',
                    'solicitud.fecha_dig',
                    'solicitud.archivo_path',
                    'historial_solicitud.id_solicitud',
                    'solicitud.id_asigantura',
                    'historial_solicitud.etapa',
                    'usuarios.email',
                    'usuarios.nombres',
                    'usuarios.paterno',
                    'usuarios.materno',
                    'asignatura.cupos',
                    'asignatura.rut',
                    'asignatura.seccion',
                    'asignatura.bloque_1',
                    'asignatura.bloque_2',
                    'asignatura.bloque_3'
                )
            ->whereIn('historial_solicitud.id_historial', $subconsulta)
            ->whereIn('historial_solicitud.estado_solicitud',['Pendiente','Seleccionado/a'])
            ->orderBy('solicitud.id_solicitud', 'desc')
            ->get();
        
          $conteo_solicitudes = DB::table('historial_solicitud')
                    ->whereIn('id_historial', $subconsulta)
                    ->whereIn('estado_solicitud', ['Pendiente', 'Seleccionado/a'])
                    ->join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
                    ->select('solicitud.id_asigantura', DB::raw('COUNT(*) as total_solicitudes'))
                    ->groupBy('solicitud.id_asigantura')
                    ->pluck('total_solicitudes', 'id_asigantura');

        foreach ($solicitudes as $solicitud) {
            //$solicitud->nombre_estudiante = $this->obtenerNombreCompletoDesdeUcampus($solicitud->pers_dig);
            $solicitud->nombre_estudiante = trim("{$solicitud->nombres} {$solicitud->paterno} {$solicitud->materno}");
            $solicitud->total_solicitudes = $conteo_solicitudes[$solicitud->id_asigantura] ?? 0;
        }
        //dd($solicitudes->toArray());
        //return response()->json($solicitudes);
        return view('solicitudes.solicitudes-coordinador', compact('solicitudes'));

        
    }

    public function solicitudesDocente(){ 
        $usuario = Auth::user();
        $subconsulta = DB::table('historial_solicitud as hs1')
                        ->select(DB::raw('MAX(hs1.id_historial)'))
                        ->join('solicitud as s', 's.id_solicitud', '=', 'hs1.id_solicitud')
                        ->join('asignatura as a', 'a.id_asigantura', '=', 's.id_asigantura')
                        ->where('a.rut', $usuario->rut)
                        ->where('hs1.estado', 1)
                        ->where('s.estado',1)
                        ->groupBy('hs1.id_solicitud');

        $solicitudes = HistorialSolicitud::join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
            ->join('asignatura', 'asignatura.id_asigantura', '=', 'solicitud.id_asigantura')
            ->join('usuarios','usuarios.rut','=','solicitud.pers_dig')
            ->select('asignatura.nombre as nombre_asignatura', 
                    'solicitud.pers_dig',
                    'solicitud.fecha_dig',
                    'usuarios.nombres',
                    'usuarios.paterno',
                    'usuarios.materno',
                    'solicitud.archivo_path',
                    'historial_solicitud.id_solicitud',
                    'solicitud.id_asigantura',
                    'historial_solicitud.etapa',
                    'usuarios.email',
                    'asignatura.cupos','asignatura.rut',
                    'asignatura.seccion','asignatura.bloque_1','asignatura.bloque_2','asignatura.bloque_3')
            ->whereIn('historial_solicitud.id_historial', $subconsulta)
            ->whereIn('historial_solicitud.estado_solicitud',['Pendiente','Seleccionado/a'])
            ->orderBy('solicitud.id_solicitud', 'desc')
            ->get();
        
        $conteo_solicitudes = DB::table('historial_solicitud')
        ->whereIn('id_historial', $subconsulta)
        ->whereIn('estado_solicitud', ['Pendiente', 'Seleccionado/a'])
        ->join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
        ->select('solicitud.id_asigantura', DB::raw('COUNT(*) as total_solicitudes'))
        ->groupBy('solicitud.id_asigantura')
        ->pluck('total_solicitudes', 'id_asigantura');


        //dd($conteo_solicitudes->toSql(), $conteo_solicitudes->getBindings());
        foreach ($solicitudes as $solicitud) {
            //$solicitud->nombre_estudiante = $this->obtenerNombreCompletoDesdeUcampus($solicitud->pers_dig);
            $solicitud->nombre_estudiante = trim("{$solicitud->nombres} {$solicitud->paterno} {$solicitud->materno}");
            $solicitud->total_solicitudes = $conteo_solicitudes[$solicitud->id_asigantura] ?? 0;
        }
        //dd($solicitud->total_solicitudes)->all();
        //dd($solicitudes->toArray());
        //return response()->json($solicitudes);
        return view('solicitudes.solicitudes', compact('solicitudes'));

    }


    public function solicitudesDocenteAceptaRechaza(){
        $usuario = Auth::user();
        $subconsulta = DB::table('historial_solicitud as hs1')
                        ->select(DB::raw('MAX(hs1.id_historial)'))
                        ->join('solicitud as s', 's.id_solicitud', '=', 'hs1.id_solicitud')
                        ->join('asignatura as a', 'a.id_asigantura', '=', 's.id_asigantura')
                        ->where('a.rut', $usuario->rut)
                        ->where('hs1.estado', 1)
                        ->where('s.estado',1)
                        ->groupBy('hs1.id_solicitud');

        $solicitudes = HistorialSolicitud::join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
            ->join('asignatura', 'asignatura.id_asigantura', '=', 'solicitud.id_asigantura')
            ->join('usuarios','usuarios.rut','=','solicitud.pers_dig')
            ->select('asignatura.nombre as nombre_asignatura', 
                    'solicitud.pers_dig',
                    'solicitud.fecha_dig',
                    'usuarios.nombres',
                    'usuarios.paterno',
                    'usuarios.materno',
                    'solicitud.archivo_path',
                    'historial_solicitud.id_solicitud',
                    'historial_solicitud.etapa',
                    'usuarios.email',
                    'asignatura.cupos','asignatura.rut',
                    'asignatura.seccion','asignatura.bloque_1','asignatura.bloque_2','asignatura.bloque_3')
            ->whereIn('historial_solicitud.id_historial', $subconsulta)
            ->whereIn('historial_solicitud.estado_solicitud',['Aceptada','Rechazada'])
            ->orderBy('solicitud.id_solicitud', 'desc')
            ->get();
        
        //dd($solicitudes);
        foreach ($solicitudes as $solicitud) {
            //$solicitud->nombre_estudiante = $this->obtenerNombreCompletoDesdeUcampus($solicitud->pers_dig);
            $solicitud->nombre_estudiante = trim("{$solicitud->nombres} {$solicitud->paterno} {$solicitud->materno}");
        }
        //dd($solicitudes->toArray());
        //return response()->json($solicitudes);
        return view('solicitudes.aceptadas-rechazadas', compact('solicitudes'));
    }

    public function seleccionar(Request $request)
    {
        $ids = explode(',', $request->ids_seleccionados);
        $usuario = Auth::user();

        $asignaturasSolicitadas = [];

        // Paso 1: Agrupar solicitudes por asignatura
        foreach ($ids as $id) {
            $solicitud = Solicitud::join('asignatura', 'asignatura.id_asigantura', '=', 'solicitud.id_asigantura')
                ->select('solicitud.id_solicitud', 'asignatura.id_asigantura', 'asignatura.nombre', 'asignatura.cupos')
                ->where('solicitud.id_solicitud', $id)
                ->first();

            if (!$solicitud) {
                continue;
            }

            $id_asignatura = $solicitud->id_asigantura;

            if (!isset($asignaturasSolicitadas[$id_asignatura])) {
                $asignaturasSolicitadas[$id_asignatura] = [
                    'nombre' => $solicitud->nombre,
                    'cupos' => $solicitud->cupos,
                    'ids' => [],
                ];
            }
            $asignaturasSolicitadas[$id_asignatura]['ids'][] = $solicitud->id_solicitud;
        }

        $excedidas = [];
        // Paso 2: Validar cupos por asignatura
        foreach ($asignaturasSolicitadas as $id_asignatura => $datos) {
            $seleccionadosActuales = HistorialSolicitud::join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
                ->where('solicitud.id_asigantura', $id_asignatura)
                ->where('historial_solicitud.estado_solicitud', 'Seleccionado/a')
                ->where('historial_solicitud.estado',1)
                ->count();

            $solicitudesNuevas = count($datos['ids']);
            $cuposDisponibles = $datos['cupos'] - $seleccionadosActuales;

            if ($solicitudesNuevas > $cuposDisponibles) {
                $excedidas[] = $datos['nombre'];
            }
        }
        //dd($seleccionadosActuales);
        // Paso 3: Si hay alguna excedida, no guardamos nada
        if (!empty($excedidas)) {
             return redirect()->back()
                ->with('sweet_error', 'Se excedieron los cupos en: ' . implode(', ', $excedidas));
           // return back()->with('warning', 'No se guardaron las selecciones. Se excedieron los cupos en: ' . implode(', ', $excedidas));

        }

        // Paso 4: Guardar historial si todo es válido
        foreach ($asignaturasSolicitadas as $datos) {
            foreach ($datos['ids'] as $idSolicitud) {
                HistorialSolicitud::create([
                    'id_solicitud' => $idSolicitud,
                    'etapa' => 'Seleccionado/a por académico/a',
                    'estado_solicitud' => 'Seleccionado/a',
                    'pers_dig' => $usuario->rut,
                    'fecha' => now(),
                ]);
            }
        }
        return redirect()->back()
            ->with('sweet_success', 'Solicitudes seleccionadas correctamente');
        //return back()->with('success', 'Solicitudes seleccionadas correctamente.');
    }

    public function seleccionarDocente(Request $request)
    {
        $idSolicitud = $request->ids_seleccionados; // solo viene un ID
        $usuario = Auth::user();

        // Obtener solicitud con asignatura y usuario
        $id = $request->input('id_solicitud');
        $solicitud = Solicitud::with('asignatura', 'usuario')->find($id);
        
        
        if (!$solicitud) {
            return redirect()->back()->with('sweet_error', 'La solicitud no existe.');
        }

        // Verificar cupos disponibles
        $cuposDisponibles = $solicitud->asignatura->cupos;

        $seleccionadosActuales = HistorialSolicitud::join('solicitud', 'solicitud.id_solicitud', '=', 'historial_solicitud.id_solicitud')
            ->where('solicitud.id_asigantura', $solicitud->id_asigantura)
            ->where('historial_solicitud.estado_solicitud', 'Seleccionado/a')
            ->where('historial_solicitud.estado', 1)
            ->count();

        if ($seleccionadosActuales >= $cuposDisponibles) {
            return redirect()->back()
                ->with('sweet_error', 'Se excedieron los cupos en: ' . $solicitud->asignatura->nombre);
        }

        // Guardar historial
        HistorialSolicitud::create([
            'id_solicitud' => $solicitud->id_solicitud,
            'etapa' => 'Seleccionado/a por académico/a',
            'estado_solicitud' => 'Seleccionado/a',
            'pers_dig' => $usuario->rut,
            'fecha' => now(),
        ]);

        // Enviar correo
        if ($solicitud->usuario && $solicitud->usuario->email) {
            $nombreUsuario = $solicitud->usuario->nombre;
            $nombreAsignatura = $solicitud->asignatura->nombre;
            // Si en algún momento tienes un detalle de solicitud, puedes agregar la URL
            // $urlDetalle = route('solicitud.detalle', ['id' => $solicitud->id_solicitud]);

            // Mail::to($solicitud->usuario->email)->send(
            //     new SolicitudSeleccionadaMail($nombreUsuario, $nombreAsignatura)
            // );
            //dd($solicitud->usuario->email);
            try {
                Mail::to($solicitud->usuario->email)->send(
                    new SolicitudSeleccionadaMail($nombreUsuario, $nombreAsignatura )
                );
            } catch (\Exception $e) {
                \Log::error("Error al enviar correo: " . $e->getMessage());
                return redirect()->back()->with('sweet_error', 'Hubo un problema al enviar el correo.');
            }
        }

        return redirect()->back()->with('sweet_success', 'Solicitud seleccionada correctamente');
    }


    public function rechazarDocente(Request $request)
    {
        $idSolicitud = $request->id_solicitud;
        $usuario = Auth::user();

        // Guarda el historial
        HistorialSolicitud::create([
            'id_solicitud' => $idSolicitud,
            'etapa' => 'No seleccionado/a por académico/a',
            'estado_solicitud' => 'Rechazada',
            'pers_dig' => $usuario->rut,
            'fecha' => now(),
        ]);

        // Buscar datos para el mail
        $solicitud = Solicitud::with('usuario', 'asignatura')->find($idSolicitud);

        if ($solicitud && $solicitud->usuario && $solicitud->usuario->email) {
            $nombreUsuario = $solicitud->usuario->nombre;
            $nombreAsignatura = $solicitud->asignatura->nombre;

            Mail::to($solicitud->usuario->email)->send(
                new SolicitudRechazadaMail($nombreUsuario, $nombreAsignatura)
            );
            
        }

        return redirect()->back()->with('sweet_success', 'Solicitud rechazada correctamente');
    }


    public function mis_solicitudes(){

        $procesoActivo = DB::table('proceso')
                        ->where('estado', 1)
                        ->orderByDesc('id')
                        ->first();
        $etapa = null;
        $mostrarBoton = false;

        if ($procesoActivo) {
            $etapa = DB::table('etapa_proceso')
                ->where('proceso_id', $procesoActivo->id)
                ->where('tipo', '=', '3') // puedes ajustar si quieres exacto
                ->where('estado', 1)
                ->first();

            if ($etapa) {
                $ahora = now();
                $mostrarBoton = ($ahora >= $etapa->fecha_inicio && $ahora <= $etapa->fecha_fin);
            }
        }

        $usuario = Auth::user();
        $subconsulta = DB::table('historial_solicitud as hs1')
            ->select(DB::raw('MAX(hs1.id_historial)'))
            ->join('solicitud as s', 's.id_solicitud', '=', 'hs1.id_solicitud')
            ->where('s.pers_dig', $usuario->rut)
            ->where('s.estado','1')
            ->groupBy('hs1.id_solicitud');

        $solicitudes = HistorialSolicitud::select(
                                'historial_solicitud.*',
                                'asignatura.nombre as asignatura_nombre',
                                'asignatura.seccion',
                                'carreras.nombre as carrera_nombre',
                                'solicitud.fecha_dig as fecha_creacion',
                                DB::raw('concat(usuarios.nombres,"<br>",usuarios.paterno," ",usuarios.materno) as docente')
                            )
            ->join('solicitud','solicitud.id_solicitud','=','historial_solicitud.id_solicitud')
            ->join('asignatura','asignatura.id_asigantura','=','solicitud.id_asigantura')
            ->join('carreras','carreras.id_carrera','=','asignatura.id_carrera')
            ->join('usuarios','usuarios.rut','=','asignatura.rut')
            ->whereIn('historial_solicitud.id_historial', $subconsulta)
            ->get();

        //dd($solicitudes->toArray());
        return view('solicitudes.mis_solicitudes', compact('solicitudes','mostrarBoton','etapa'));
    }

    public function aceptar(Request $request)
    {
        $usuario = Auth::user();

        // Contar solicitudes aceptadas por este estudiante
        $cantidadAceptadas = HistorialSolicitud::where('pers_dig', $usuario->rut)
            ->where('estado_solicitud', 'Aceptada')
            ->where('estado', 1)
            ->count();

        if ($cantidadAceptadas >= 2) {
            return redirect()->back()->with('sweet_warning', 'Solo puedes aceptar un máximo de 2 solicitudes.');
        }

        // Crear nuevo historial
        HistorialSolicitud::create([
            'id_solicitud' => $request->id_solicitud,
            'etapa' => 'Aceptada por estudiante',
            'estado_solicitud' => 'Aceptada',
            'pers_dig' => $usuario->rut,
            'fecha_dig' => now(),
            'estado' => 1
        ]);

        return redirect()->back()->with('sweet_success', 'Solicitud aceptada correctamente.');
    }


    public function rechazar(Request $request)
    {
        $usuario = Auth::user();
        HistorialSolicitud::create([
            'id_solicitud' => $request->id_solicitud,
            'etapa' => 'Rechazada por estudiante',
            'estado_solicitud' => 'Rechazada',
            'pers_dig' => $usuario->rut,
            'fecha_dig' => now(),
            'estado' => 1
        ]);

        return redirect()->back()
                ->with('sweet_error', 'Solicitud rechazada.');
        //return redirect()->back()->with('warning', 'Solicitud rechazada.');
    }
 

    public function create()
    { 
        // Validar proceso activo y etapa tipo 1
        $hoy = now();
        $proceso = Proceso::where('estado', 1)
            ->orderBy('id', 'desc')
            ->first();

        if (!$proceso) {
            return redirect()->back()
                ->with('sweet_error', 'No hay procesos activos habilitados en este momento.');
        }

        $etapa = $proceso->etapas()
            ->where('tipo', 1)
            ->where('estado',1)
            // ->whereDate('fecha_inicio', '<=', $hoy)
            // ->whereDate('fecha_fin', '>=', $hoy)
            ->first();
        
        //dd($etapa);
        $usuario = Auth::user();
        $rut = $usuario->rut;
        //dd($rut);
        $datos = $this->obtenerTotalSCTDesdeUcampus($rut);

        //dd($datos);
        $llamadas_api = $datos['llamadas'];
        $sct_datos = $datos['total'];
        if($rut == '18670144'){
            $sct_datos = 120;
        }
        

        $asignaturas = Asignatura::all();
        $carreras = Carrera::all();
        $bancos = Banco::all();
        //$datosBanco = DatoBanco::all();
        $datosBanco = DatoBanco::with('tipoCuenta.banco')
                    ->where('rut', $rut)
                    ->orderByDesc('id')
                    ->first(); // trae el más reciente
        $tipos_cuenta = TipoCuenta::all();
        $permiso = Permiso::with('carrera')->where('rut', $rut)->where('estado', '1')->first();;

        return view('solicitudes.create', compact('asignaturas', 'carreras', 'datosBanco', 'bancos','tipos_cuenta', 'rut','permiso','etapa','sct_datos','llamadas_api'));
    }

    
    public function store(Request $request)
    {
        $usuario = Auth::user();
        $rut = $usuario->rut;

        // Validar que el usuario tenga email y teléfono
        if (empty($usuario->email) || empty($usuario->telefono)) {
            return redirect()->back()
                ->with('mostrar_modal_perfil', true)
                ->with('sweet_error', 'Debes completar tu perfil con correo y teléfono antes de continuar.');
        }

        // Validar SCT
        $datos = $this->obtenerTotalSCTDesdeUcampus($rut);
        if ($rut != '18670144') {
            if ($datos['total'] < 120) {
                return redirect()->back()
                    ->withInput()
                    ->with('sweet_error', 'No puedes crear la solicitud. Debes tener al menos 120 SCT y estar activo.');
            }
        }

        // Validar proceso activo y etapa tipo 1
        $hoy = now();
        $proceso = Proceso::where('estado', 1)
            ->orderBy('id', 'desc')
            ->first();

        if (!$proceso) {
            return redirect()->back()
                ->with('sweet_error', 'No hay procesos activos habilitados en este momento.');
        }

        $etapa = $proceso->etapas()
            ->where('tipo', 1)
            ->where('estado',1)
            ->whereDate('fecha_inicio', '<=', $hoy)
            ->whereDate('fecha_fin', '>=', $hoy)
            ->first();

        if (!$etapa) {
            $etapaProxima = $proceso->etapas()
            ->where('tipo', 1)
            ->where('estado', 1)
            ->first();
            
            $mensajeFechaVencida = '<div class="alert alert-danger" role="alert">';
            $mensajeFechaVencida .= 'El periodo para postular es desde <b>'
                        . \Carbon\Carbon::parse($etapaProxima->fecha_inicio)->format('d-m-Y H:i')
                        . '</b> hasta <b>'
                        . \Carbon\Carbon::parse($etapaProxima->fecha_fin)->format('d-m-Y H:i')
                        . '</b>.';
            $mensajeFechaVencida .= '</div>';

            return redirect()->back()->with('sweet_error', $mensajeFechaVencida);
        }

        // Validación de campos
        $request->validate([
            'n_matricula' => 'nullable|string|max:150',
            'asignatura_id' => 'required|exists:asignatura,id_asigantura',
            'id_carrera_estudiante' => 'required|exists:carreras,id_carrera',
            'archivo_path' => 'nullable|file|mimes:pdf|max:20480', // 20 MB
            'banco_id' => 'required|exists:banco,id',
            'tipo_cuenta_id' => 'required|exists:tipo_cuenta,id',
            'numero_cuenta' => 'required|numeric',
        ], [
            'archivo_path.max' => 'El archivo no puede ser mayor a 20MB.',
            'archivo_path.mimes' => 'Solo se permite subir archivos PDF.'
        ]);

        // Validar solicitudes duplicadas
        // $yaExiste = Solicitud::where('id_asigantura', $request->asignatura_id)
        //     ->where('pers_dig', $rut)
        //     ->where('estado', 1)
        //     ->exists();

        // if ($yaExiste) {
        //     return redirect()->back()
        //         ->withInput()
        //         ->with('sweet_error', 'Ya tienes una solicitud activa para esta asignatura.');
        // }
        $yaExiste = DB::table('solicitud as s')
            ->join('historial_solicitud as hs', 's.id_solicitud', '=', 'hs.id_solicitud')
            ->where('s.id_asigantura', $request->asignatura_id)
            ->where('s.pers_dig', $rut)
            ->where('s.estado', 1)
            ->whereIn('hs.id_historial', function ($sub) {
                $sub->select(DB::raw('MAX(id_historial)'))
                    ->from('historial_solicitud')
                    ->groupBy('id_solicitud');
            })
            ->where('hs.estado_solicitud', '!=', 'Rechazada')
            ->exists();

        if ($yaExiste) {
            return redirect()->back()
                ->withInput()
                ->with('sweet_error', 'Ya tienes una solicitud activa para esta asignatura.');
        }

        // Reutilizar datos bancarios
        $datoBanco = DatoBanco::where('rut', $rut)
            ->where('tipo_cuenta_id', $request->tipo_cuenta_id)
            ->where('num_cuenta', $request->numero_cuenta)
            ->first();

        if (!$datoBanco) {
            $datoBanco = DatoBanco::create([
                'banco_id' => $request->banco_id,
                'tipo_cuenta_id' => $request->tipo_cuenta_id,
                'rut' => $rut,
                'num_cuenta' => $request->numero_cuenta,
            ]);
        }

        $n_matricula = $this->obtenerMatriculaAlumno($rut);

        // Manejo de archivo
        $archivoPath = null;
        if ($request->hasFile('archivo_path')) {
            try {
                $archivo = $request->file('archivo_path');
                $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
                $ruta = $archivo->storeAs('cartas', $nombreArchivo, 'public');
                $archivoPath = 'storage/' . $ruta;
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('sweet_error', 'Ocurrió un error al subir el archivo PDF, intenta nuevamente.');
            }
        }

        // Crear la solicitud incluyendo el proceso_id
        $solicitud = Solicitud::create([
            'id_carrera_estudiante' => $request->id_carrera_estudiante,
            'id_asigantura' => $request->asignatura_id,
            'datos_banco_id' => $datoBanco->id,
            'archivo_path' => $archivoPath,
            'n_matricula' => $n_matricula ?? '',
            'pers_dig' => $rut,
            'fecha_dig' => now(),
            'estado' => 1,
            'id_proceso' => $proceso->id, // agregado
        ]);

        // Historial
        HistorialSolicitud::create([
            'id_solicitud' => $solicitud->id_solicitud,
            //'etapa' => $etapa->etapa_proceso,
            'etapa' => 'Solicitud Creada',
            'estado_solicitud' => 'Pendiente',
            'pers_dig' => $rut,
            'fecha_dig' => now(),
            'estado' => 1,
        ]);

        $mailDocente = DB::table('asignatura')
                        ->select('usuarios.email')
                        ->join('usuarios', 'usuarios.rut', '=', 'asignatura.rut')
                        ->where('asignatura.id_asigantura', '=', $request->asignatura_id)
                        ->first();
        //dd($MailDocente->email);
        if ($mailDocente) {
            Mail::to($mailDocente)->send(new SolicitudCreadaDocente($solicitud));
        }
        // try {
        //     Mail::to($mailDocente)->send(new SolicitudCreadaDocente($solicitud));
        // } catch (\Exception $e) {
        //     dd('Error al enviar correo: ' . $e->getMessage()); // te mostrará el error
        // }


        return redirect()->route('mis-solicitudes')
            ->with('sweet_success', 'Solicitud creada correctamente.');
    }


    
    public function show($id)
    {
        
        $solicitud = Solicitud::with([
            'asignatura.carrera',
            'carreraEstudiante',
            'usuario',
            /*'datosBanco.tipoCuenta.banco',*/
            'historial.responsable',
            'historial' => function ($query) {
                $query->orderByDesc('fecha_dig');
            }
        ])->findOrFail($id);

        //dd($solicitud->asignatura->usuario->nombres);
        $rut = $solicitud->pers_dig;
        $datoBanco = DatoBanco::where('rut', $rut)
                ->where('estado','=','1')
                ->orderByDesc('id')
                ->first();

        // Etapa actual (último registro del historial)
        $etapaActual = $solicitud->historial->first();

        return view('solicitudes.show', compact('solicitud', 'etapaActual','datoBanco'));
    }

    // public function edit(Solicitud $solicitud)
    // {
    //     $asignaturas = Asignatura::all();
    //     $carreras = Carrera::all();
    //     $datosBanco = DatosBanco::all();
    //     return view('solicitudes.edit', compact('solicitud', 'asignaturas', 'carreras', 'datosBanco'));
    // }

    public function update(Request $request, Solicitud $solicitud)
    {
        $request->validate([
            'n_matricula' => 'nullable|string|max:150',
            'id_asigantura' => 'required|exists:asignatura,id_asigantura',
            'id_carrera_estudiante' => 'required|exists:carreras,id_carrera',
            'datos_banco_id' => 'required|exists:datos_banco,id',
            'pers_dig' => 'required|string|max:45',
            'fecha_dig' => 'required|date',
            'estado' => 'nullable|in:0,1',
        ]);

        $solicitud->update($request->all());

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud actualizada correctamente.');
    }

    public function destroy(Solicitud $solicitud)
    {
        $solicitud->delete();

        return redirect()->route('solicitudes.index')->with('success', 'Solicitud eliminada correctamente.');
    }
}
