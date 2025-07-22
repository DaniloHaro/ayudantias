@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Listado de Carreras</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- <a href="{{ route('carreras.create') }}" class="btn btn-primary mb-3">Nueva Carrera</a> --}}

    <table id="tabla-carreras" class="table table-bordered">
        <thead>
            <tr class="table-dark">
                <th>ID</th>
                <th>Ucampus</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($carreras as $carrera)
                <tr>
                    <td>{{ $carrera->id_carrera }}</td>
                    <td>{{ $carrera->id_ucampus }}</td>
                    <td>{{ $carrera->nombre }}</td>
                    <td>{{ $carrera->estado ? 'Activo' : 'Inactivo' }}</td>
                    <td>
                        <a href="{{ route('carreras.show', $carrera->id_carrera) }}" class="btn btn-info btn-sm">Ver</a>
                        <a href="{{ route('carreras.edit', $carrera->id_carrera) }}" class="btn btn-warning btn-sm">Editar</a>
                        {{-- <form action="{{ route('carreras.destroy', $carrera->id_carrera) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar carrera?')">Eliminar</button>
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
        $('#tabla-carreras').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'                
            }
        });
    });
</script>
@endsection
