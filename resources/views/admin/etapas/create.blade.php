@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Nueva Etapa para el Proceso: {{ $proceso->nombre }}</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('procesos.etapas.store', $proceso) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nombre de la etapa</label>
            <input type="text" name="etapa_proceso" class="form-control" value="{{ old('etapa_proceso') }}" required>
        </div>
        <div class="mb-3">
            <label>Tipo</label>
            <select name="tipo" class="form-control" required>
                <option value="">Seleccione un tipo</option>
                <option value="1" {{ old('tipo') == 1 ? 'selected' : '' }}>Etapa 1</option>
                <option value="2" {{ old('tipo') == 2 ? 'selected' : '' }}>Etapa 2</option>
                <option value="3" {{ old('tipo') == 3 ? 'selected' : '' }}>Etapa 3</option>
                <option value="4" {{ old('tipo') == 4 ? 'selected' : '' }}>Etapa 4</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Fecha de inicio</label>
            <input type="datetime-local" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio') }}">
        </div>

        <div class="mb-3">
            <label>Fecha de fin</label>
            <input type="datetime-local" name="fecha_fin" class="form-control" value="{{ old('fecha_fin') }}">
        </div>

        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control">{{ old('descripcion') }}</textarea>
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="1" {{ old('estado') == '1' ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('procesos.etapas.index', $proceso) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
