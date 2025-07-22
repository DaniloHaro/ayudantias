@extends('layouts.app')

@section('title', 'Editar Permiso')

@section('content')
<div class="container">
    <h2 class="mb-4">Editar Permiso</h2>

    <form action="{{ route('permisos.update', $permiso->id_permiso) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.permisos.form', ['permiso' => $permiso])
        <button type="submit" class="btn btn-primary">Actualizar Permiso</button>
        <a href="{{ route('permisos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
