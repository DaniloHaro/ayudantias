@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Listado de Usuarios</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary mb-3">Crear Usuario</a>

    <table id="tabla-usuarios" class="table table-bordered table-striped">
        <thead>
            <tr class="table-dark">
                <th>RUT</th>
                <th>Nombre Completo</th>
                <th>Email</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
                <tr>
                    <td>{{ formatearRut($usuario->rut) }}</td>
                    <td>{{ $usuario->nombres }} {{ $usuario->paterno }} {{ $usuario->materno }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>{{ $usuario->estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href="{{ route('usuarios.show', $usuario->rut) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('usuarios.edit', $usuario->rut) }}" class="btn btn-warning btn-sm">Editar</a>
                        {{-- <form action="{{ route('usuarios.destroy', $usuario->rut) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">Eliminar</button>
                        </form> --}}
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
        $('#tabla-usuarios').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
            }
        });
    });
</script>
@endsection
