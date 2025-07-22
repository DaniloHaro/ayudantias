@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Carrera</h1>

    <form action="{{ route('carreras.update', $carrera->id_carrera) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="id_ucampus" class="form-label">ID Ucampus</label>
            <input type="text" name="id_ucampus" id="id_ucampus" class="form-control @error('id_ucampus') is-invalid @enderror"
                   value="{{ old('id_ucampus', $carrera->id_ucampus) }}">
            @error('id_ucampus')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                   value="{{ old('nombre', $carrera->nombre) }}">
            @error('nombre')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror">
                <option value="1" {{ old('estado', $carrera->estado) == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado', $carrera->estado) == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
            @error('estado')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary">Actualizar Carrera</button>
        </div>
    </form>
</div>
@endsection
