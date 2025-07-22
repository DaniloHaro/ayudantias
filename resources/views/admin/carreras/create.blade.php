@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nueva Carrera</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.carreras.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="id_ucampus" class="form-label">ID Ucampus</label>
            <input type="text" name="id_ucampus" class="form-control" value="{{ old('id_ucampus') }}">
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select" required>
                <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('admin.carreras.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
