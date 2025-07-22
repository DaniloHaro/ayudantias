@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle de Asignatura</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $asignatura->nombre }}</p>
            <p><strong>Carrera:</strong> {{ $asignatura->carrera->nombre ?? '—' }}</p>
            <p><strong>Profesor:</strong> {{ $asignatura->usuario->nombres ?? '—' }}</p>
            <p><strong>Sección:</strong> {{ $asignatura->seccion }}</p>
            <p><strong>Bloque 1:</strong> {{ $asignatura->bloque_1 }}</p>
            <p><strong>Bloque 2:</strong> {{ $asignatura->bloque_2 }}</p>
            <p><strong>Bloque 3:</strong> {{ $asignatura->bloque_3 }}</p>
            <p><strong>Cupos:</strong> {{ $asignatura->cupos }}</p>
            <p><strong>Estado:</strong>
                @if ($asignatura->estado == 1)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </p>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('asignaturas.index') }}" class="btn btn-secondary">Volver</a>
        <a href="{{ route('asignaturas.edit', $asignatura->id_asigantura) }}" class="btn btn-warning">Editar</a>
    </div>
</div>
@endsection
