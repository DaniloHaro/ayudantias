
@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif
    <h1 class="mb-4">Listado de Solicitudes Aprobadas/Rechazadas</h1>
    <div class="mb-3">
        <label for="filtro-asignatura" class="form-label">Filtrar por Asignatura:</label>
        <select id="filtro-asignatura" class="form-select">
            <option value="">Todas</option>
            @php
                $asignaturasUnicas = $solicitudes->pluck('nombre_asignatura')->unique()->sort();
            @endphp
            @foreach($asignaturasUnicas as $nombre)
                <option value="{{ $nombre }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>
    <table id="tabla-solicitudes-academico" class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                {{-- <th>Rut</th> --}}
                <th>Estudiante</th>
                <th>Notas</th>
                <th>Carta<br>Motivación</th>
                <th>Asignatura</th>
                <th>Cupos</th>
                <th>Fecha <br>solicitud</th>
                <th>Estado</th>
                {{-- <th>Seleccionados</th> --}}
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitudes as $solicitud)
                
                <tr>
                    {{-- <td style="width:10%;">{{formatearRut($solicitud->pers_dig)}}</td> --}}
                    <td style="width:15%;">{!!$solicitud->nombre_estudiante!!}<br>
                        <small>{{formatearRut($solicitud->pers_dig)}}</small><br>
                        <small>{{$solicitud->email}}</small>
                    </td>
                    <td>
                        <center>
                            <a href="https://ucampus.uchile.cl/m/facso_bia/notas?rut={{$solicitud->pers_dig}}" target="_blank">
                                <i class="fa-solid fa-clipboard fa-xl"></i>
                            </a>
                        </center>
                    </td>
                    <td>
                        <center>
                            <a href="{{ asset($solicitud->archivo_path) }}" target="_blank">
                                <i class="fa-solid fa-file-pdf fa-xl" style="color: #db0000;"></i>
                            </a>
                        </center>
                    </td>
                    <td style="width:30%;">{{ $solicitud->nombre_asignatura ?? '—' }}
                        <br><small> sección {{$solicitud->seccion}}</small>
                        <br><small>  {{$solicitud->bloque_1}}</small>
                        <br><small>  {{$solicitud->bloque_2}}</small>
                        <br><small>  {{$solicitud->bloque_3}}</small>
                    </td>
                    <td><center>{{ $solicitud->cupos ?? '—' }}</center></td>
                    <td style="width:10%;">
                        {{\Carbon\Carbon::parse($solicitud->fecha_dig)->format('d-m-Y')}}<br>
                        {{\Carbon\Carbon::parse($solicitud->fecha_dig)->format('H:i')}}
                    </td>   
                    <td>{{ $solicitud->etapa ?? '—' }}</td>   
                    {{-- <td>
                        <center>
                              <input type="checkbox" class="seleccion-solicitud" 
                                value="{{ $solicitud->id_solicitud }}" 
                                {{ $solicitud->etapa != 'Solicitud Creada' ? 'disabled' : '' }}>
                        </center>
                    </td> --}}
                    <td>
                        <a href="{{ route('solicitudes.show', $solicitud->id_solicitud) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-bars"></i> Detalles</a>                       
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <button type="button" id="btn-confirmar" class="btn btn-primary mt-3">
        <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
         <span id="btn-text">Seleccionar Solicitudes</span>
    </button>
    <form id="form-seleccionados" method="POST" action="{{ route('solicitudes.seleccionar') }}">
        @csrf
        <input type="hidden" name="ids_seleccionados" id="ids_seleccionados">
    </form> --}}

</div>
@endsection
@section('scripts')
<script>
$(document).ready(function () {
    const tabla = $('#tabla-solicitudes-academico').DataTable({
        order: [[4, 'asc']],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        }
    });

    // Filtro por Asignatura (columna 2)
    $('#filtro-asignatura').on('change', function () {
        const valor = $(this).val();
        tabla.column(2).search(valor).draw();
    });

    // Botón externo con lógica de envío
    $('#btn-confirmar').on('click', function () {
        let seleccionados = [];
        $('.seleccion-solicitud:checked').each(function () {
            seleccionados.push($(this).val());
        });
        if (seleccionados.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe seleccionar al menos una solicitud.'
            });
            return;
        }
        Swal.fire({
            title: '¿Confirmar selección?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, seleccionar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#ids_seleccionados').val(seleccionados.join(','));
                $('#btn-confirmar').prop('disabled', true);
                $('#spinner').removeClass('d-none');
                $('#btn-text').text('Procesando...');
                $('#form-seleccionados').submit();
            }
        });
    });
});
</script>
@endsection
