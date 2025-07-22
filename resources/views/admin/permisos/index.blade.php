@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Permisos</h1>

    <table id="tabla-permisos" class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                {{-- <th>ID</th> --}}
                <th>RUT</th>
                <th>Nombre Usuario</th>
                <th>Carrera</th>                
                <th>Tipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permisos as $permiso)
                <tr>
                    {{-- <td>{{ $permiso->id_permiso }}</td> --}}
                    <td>{{ $permiso->rut }}</td>
                    <td>
                        @if ($permiso->usuario)
                            {{ $permiso->usuario->nombres }} {{ $permiso->usuario->paterno }} {{ $permiso->usuario->materno }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $permiso->carrera->nombre ?? '—' }}</td>
                    <td>{{ $permiso->tipo }}</td>
                    <td>
                        @if ($permiso->estado == '1')
                            <span class="badge bg-success">Activo</span>
                        @else
                            <span class="badge bg-secondary">Inactivo</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('permisos.show', $permiso->id_permiso) }}" class="btn btn-sm btn-info">Ver</a>
                        <a href="{{ route('permisos.edit', $permiso->id_permiso) }}" class="btn btn-sm btn-warning">Editar</a>
                        {{-- <form action="{{ route('permisos.destroy', $permiso->id_permiso) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este permiso?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Eliminar</button>
                        </form> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    <div class="mt-3">
        <a href="{{ route('permisos.create') }}" class="btn btn-primary">Crear Permiso</a>
    </div>
</div>
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
        $('#tabla-permisos').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
@endsection
