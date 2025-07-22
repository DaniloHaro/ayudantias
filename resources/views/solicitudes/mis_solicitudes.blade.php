
@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container">
        <h2 class="mb-4">Mis Solicitudes</h2>
        <hr>
        @if ($etapa)
            <div class="alert alert-warning">
                <strong>Etapa actual:</strong> {{ $etapa->etapa_proceso }} <br>
                <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($etapa->fecha_inicio)->format('d/m/Y H:i') }} <br>
                <strong>Fin:</strong> {{ \Carbon\Carbon::parse($etapa->fecha_fin)->format('d/m/Y H:i') }}
            </div>
        @endif
        <table id="tabla-mis-solicitudes" class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                   
                    <th>Asignatura</th>
                    <th>Docente</th>
                    <th>Sección</th>
                    <th>Carrera</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($solicitudes as $solicitud)
                    <tr>
                        <td style="width:30%;">{{ $solicitud->asignatura_nombre ?? '—' }}</td>
                        <td style="width:20%;">{!!$solicitud->docente ?? '—' !!}</td>
                        <td style="width:5%;" >{{ $solicitud->seccion ?? '—' }}</td>
                        <td style="width:30%;">{{ $solicitud->carrera_nombre ?? '—'}}</td>             
                        {{-- {{ $solicitud->carrera->nombre ?? '—' }}        --}}
                        {{-- <td><center>{{ $solicitud->asignatura->cupos ?? '—' }}</center></td> --}}
                        <td style="width:15%;">{{\Carbon\Carbon::parse($solicitud->fecha_creacion)->format('d-m-Y H:i') }}</td>      
                        <td>{{ $solicitud->estado_solicitud ?? '—'}}</td>
                        <td>
                            @if ($solicitud->estado_solicitud == 'Seleccionado/a' && $mostrarBoton)                            
                                <button type="button" class="btn btn-sm btn-secondary btn-accion-solicitud" data-id="{{ $solicitud->id_solicitud }}">
                                    <span class="fa-solid fa-question"></span>
                                </button>
                            @endif
                            <a href="{{ route('solicitudes.show', $solicitud->id_solicitud) }}" class="btn btn-sm btn-warning"><i class="fa-solid fa-bars"></i></a>                       
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        const tabla = $('#tabla-mis-solicitudes').DataTable({
            order: [[4, 'asc']],
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
         @if(session('sweet_success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('sweet_success') }}',
            });
        @endif
        @if(session('sweet_warning'))
            Swal.fire({
                icon: 'warning',
                title: '{{ session('sweet_warning') }}',
                //timer: 3000
            });
        @endif
        @if(session('sweet_error'))
            Swal.fire({
                icon: 'error',
                title: 'Éxito',
                text: '{{ session('sweet_error') }}',
            });
        @endif
        $('.btn-accion-solicitud').on('click', function () {
            const idSolicitud = $(this).data('id');

            Swal.fire({
                title: '¿Qué deseas hacer con esta solicitud?',
                text: "Puedes aceptarla o rechazarla.",
                icon: 'question',
                showCancelButton: true,
                showDenyButton: true,
                confirmButtonText: 'Aceptar',
                denyButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                denyButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarAccion(idSolicitud, 'aceptar');
                } else if (result.isDenied) {
                    enviarAccion(idSolicitud, 'rechazar');
                }
            });

            function enviarAccion(id, accion) {
                const form = $('<form>', {
                    method: 'POST',
                    action: accion === 'aceptar' ? '{{ route("solicitudes.aceptar") }}' : '{{ route("solicitudes.rechazar") }}'
                });

                const token = '{{ csrf_token() }}';
                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: token
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'id_solicitud',
                    value: id
                }));

                $('body').append(form);
                form.submit();
            }
        });
    });
</script>
@endsection