@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Asignatura</h1>

    <form action="{{ route('asignaturas.update', $asignatura->id_asigantura) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.asignaturas.form', ['asignatura' => $asignatura])
        <button type="submit" class="btn btn-warning">Actualizar</button>
        <a href="{{ route('asignaturas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
