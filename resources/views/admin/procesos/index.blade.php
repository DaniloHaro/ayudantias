@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Procesos</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('procesos.create') }}" class="btn btn-primary mb-3">Nuevo Proceso</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
               
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($procesos as $proceso)
                <tr>
                    <td>{{ $proceso->nombre }}</td>                    
                    <td>
                        @if($proceso->estado)
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('procesos.edit', $proceso) }}" class="btn btn-warning btn-sm">Editar</a>

                        <a href="{{ route('procesos.etapas.index', $proceso) }}" class="btn btn-info btn-sm">
                            Etapas
                        </a>

                        <form action="{{ route('procesos.destroy', $proceso) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Estás seguro de eliminar este proceso?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay procesos registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
