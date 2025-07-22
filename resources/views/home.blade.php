 
{{-- @if(session('mensaje'))
    <div class="alert alert-danger">
        {{ session('mensaje') }}
    </div>
@endif --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA - FACSO </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .sidebar-collapsed {
            width: 70px !important;
        }

        .sidebar-collapsed .sidebar-text {
            display: none;
        }

        #main-content {
            transition: margin-left 0.3s;
            /*margin-left: 250px;*/
        }

        .with-sidebar-collapsed {
            margin-left: 70px !important;
        }
    </style>
</head>
<body class="">
    <div class="container">
        <main id="main-content" class="">
            <div class="">
                <br>
                <br>
                <br>
                <h2>Becas de Colaboración Académica - FACSO</h2>
                <hr>
                <br>
                <center>
                    {{-- <a class="" href="https://developer.facso.cl/facultad/ayudantias_developer/api/cas/index.php">
                        <img src="images/login_uchile.jpg" style="border-radius: 10px; width:100%;max-width: 500px;">
                    </a> --}}
                    <a type="button" class="btn btn-lg btn-dark shadow-lg  mb-5  rounded" href="{{ url('/login') }}"><i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión</a>
                </center>
                <table class="table table bordered">
                    <thead class="table-dark">
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        @foreach($etapas_activas as $etapa)
                        @php
                            $hoy = \Carbon\Carbon::now();
                            $inicio = \Carbon\Carbon::parse($etapa->fecha_inicio);
                            $fin = \Carbon\Carbon::parse($etapa->fecha_fin);
                            $estado = ($hoy->between($inicio, $fin)) ? 'Abierto' : 'Cerrado';
                            $badgeColor = ($estado == 'Abierto') ? 'success' : 'danger';
                        @endphp
                            <tr>
                                <td>{{$etapa->etapa_proceso}}</td>
                                <td>{{Carbon\Carbon::parse($etapa->fecha_inicio)->format('d-m-Y H:i')}}Hrs hasta {{Carbon\Carbon::parse($etapa->fecha_fin)->format('d-m-Y H:i')}} Hrs</td>
                                <td>
                                    <span class="badge bg-{{ $badgeColor }}">
                                        {{ $estado }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <hr>
                La Escuela de Pregrado de la Facultad de Ciencias Sociales, convoca a sus estudiantes de Pregrado a participar 
                en la convocatoria del Programa de <b>Becas de Colaboración Académica en Docencia de Pregrado</b>, el cual tiene como objetivo
                <i>"Contribuir a la formación integral de las y los Estudiantes de Pregrado tanto a través de la ayuda que los becarios y 
                becarias realizan al proceso de aprendizaje en la docencia como de la complementación de su preparación académica y profesional en la Universidad".</i>
                <br><br>
                En concreto, las y los estudiantes que cuenten con <b>120 SCT cursados a la fecha</b>, podrán postular a través de esta 
                plataforma para optar como máximo a dos cupos en calidad de Estudiantes Becarios (ayudantes) en las carreras de 
                Antropología-Arqueología, Pedagogía en Educación Especial, Pedagogía en Educación Física, Pedagogía en Educación Parvularia,
                Psicología, Sociología y Trabajo Social.
                <br><br>
                @foreach($etapas_activas as $etapa)
                    @if ($etapa->tipo == '1')
                        @php
                            $fechaInicio = $etapa->fecha_inicio;
                            $fechaFin = $etapa->fecha_fin;
                        @endphp
                    @endif
                @endforeach
                El periodo de postulación se inicia el <b>{{ \Carbon\Carbon::parse($fechaInicio)->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i') }} horas</b>, permaneciendo abierto hasta el <b>{{ \Carbon\Carbon::parse($fechaFin)->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i') }}<b>.
                <br>
                <b>Luego se iniciará un segundo proceso de postulación</b> para cubrir los cupos que han quedado vacantes,
                a partir de las necesidades y requerimientos de los equipos docentes.
            </div>
            @include('layouts.footer')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- mds -->
