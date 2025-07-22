@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Etapas del Proceso: {{ $proceso->nombre }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('procesos.etapas.create', $proceso) }}" class="btn btn-primary mb-3">Nueva Etapa</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Etapa</th>
                <th>Tipo</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($etapas as $etapa)
                <tr>
                    <td>{{ $etapa->etapa_proceso }}</td>
                    <td>{{ $etapa->tipo }}</td>
                    <td>{{ $etapa->fecha_inicio ?? '-' }}</td>
                    <td>{{ $etapa->fecha_fin ?? '-' }}</td>
                    <td>
                        @if($etapa->estado)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('procesos.etapas.edit', [$proceso, $etapa]) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('procesos.etapas.destroy', [$proceso, $etapa]) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de eliminar esta etapa?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay etapas definidas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('procesos.index') }}" class="btn btn-secondary mt-2">Volver a procesos</a>
</div>
@endsection
