@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Proceso</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('procesos.update', $proceso) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $proceso->nombre) }}" required>
        </div>
        
        <div class="mb-3">
            <label>Estado</label>
            <select name="estado" class="form-control" required>
                <option value="1" {{ $proceso->estado == 1 ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ $proceso->estado == 0 ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
        <a href="{{ route('procesos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
