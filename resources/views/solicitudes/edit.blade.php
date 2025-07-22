
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Editar Solicitud</h1>

    <form action="{{ route('solicitudes.update', $solicitud->id_solicitud) }}" method="POST">
        @csrf
        @method('PUT')
        @include('solicitudes._form', ['solicitud' => $solicitud])
        <button type="submit" class="btn btn-warning">Actualizar</button>
        {{-- <a href="{{ route('solicitudes.index') }}" class="btn btn-secondary">Cancelar</a> --}}
    </form>
</div>
@endsection
