@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Detalle del Permiso</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Información del Usuario</h5>
            <p><strong>RUT:</strong> {{ $permiso->rut }}</p>
            <p><strong>Nombre:</strong> 
                @if ($permiso->usuario)
                    {{ $permiso->usuario->nombres }} {{ $permiso->usuario->paterno }} {{ $permiso->usuario->materno }}
                @else
                    —
                @endif
            </p>
            <p><strong>Carrera:</strong> {{ $permiso->carrera->nombre ?? '—' }}</p>

            <hr>

            <h5 class="card-title">Detalles del Permiso</h5>
            <p><strong>Tipo:</strong> {{ $permiso->tipo }}</p>
            <p><strong>Estado:</strong> 
                @if ($permiso->estado == '1')
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('permisos.index') }}" class="btn btn-secondary">Volver al listado</a>
        <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="btn btn-warning">Editar</a>
        <form action="{{ route('permisos.destroy', $permiso->id_permiso) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este permiso?');">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Eliminar</button>
        </form>
    </div>
</div>
@endsection
