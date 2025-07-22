@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Asignaturas</h1>

    <a href="{{ route('asignaturas.create') }}" class="btn btn-primary mb-3">Crear Nueva Asignatura</a>

    <table id="tabla-asignaturas" class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Profesor</th>
                <th>Sección</th>
                <th>Cupos</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($asignaturas as $asignatura)
                <tr>
                    <td>{{ $asignatura->nombre }}</td>
                    <td>{{ $asignatura->carrera->nombre ?? '—' }}</td>
                    <td>
                        @if (isset($asignatura->usuario->nombres))
                            {{ $asignatura->usuario->nombres.' '.$asignatura->usuario->paterno.' '.$asignatura->usuario->materno ?? '—' }}
                        @else
                            Sin nombre - {{$asignatura->rut}}
                        @endif
                        
                    </td>
                    <td>{{ $asignatura->seccion }}</td>
                    <td>{{ $asignatura->cupos }}</td>
                    <td>
                        @if ($asignatura->estado == 1)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('asignaturas.show', $asignatura->id_asigantura) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('asignaturas.edit', $asignatura->id_asigantura) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('asignaturas.destroy', $asignatura->id_asigantura) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta asignatura?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form>
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
        $('#tabla-asignaturas').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'                
            }
        });
    });
</script>
@endsection
