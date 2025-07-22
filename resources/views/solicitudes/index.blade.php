
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Solicitudes</h1>

    <a href="{{ route('solicitudes.create') }}" class="btn btn-primary mb-3">Crear Nueva Solicitud</a>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Matrícula</th>
                <th>Asignatura</th>
                <th>Carrera Estudiante</th>
                <th>Banco</th>
                <th>Persona Digna</th>
                <th>Fecha de Dig</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->n_matricula ?? '—' }}</td>
                    <td>{{ $solicitud->asignatura->nombre ?? '—' }}</td>
                    <td>{{ $solicitud->carrera->nombre ?? '—' }}</td>
                    <td>{{ $solicitud->datosBanco->banco->nombre ?? '—' }}</td>
                    <td>{{ $solicitud->pers_dig }}</td>
                    <td>{{ $solicitud->fecha_dig }}</td>
                    <td>
                        @if ($solicitud->estado == 1)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('solicitudes.show', $solicitud->id_solicitud) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('solicitudes.edit', $solicitud->id_solicitud) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('solicitudes.destroy', $solicitud->id_solicitud) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta solicitud?');">
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