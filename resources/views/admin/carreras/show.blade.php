@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Carrera</h1>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $carrera->id_carrera }}</p>
            <p><strong>ID Ucampus:</strong> {{ $carrera->id_ucampus ?? '—' }}</p>
            <p><strong>Nombre:</strong> {{ $carrera->nombre }}</p>
            <p><strong>Estado:</strong> 
                @if ($carrera->estado)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </p>
        </div>
    </div>

    <a href="{{ route('carreras.index') }}" class="btn btn-outline-secondary mt-3">Volver</a>
</div>
@endsection
