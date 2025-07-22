@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Detalle de la Solicitud #{{ $solicitud->id_solicitud }}</h2>

    {{-- DATOS ESTUDIANTE --}}
    <div class="card mb-3">
        <div class="card-header bg-primary text-white">Datos del Estudiante</div>
        <div class="card-body">
            <p><strong>RUT:</strong> {{ formatearRut($solicitud->usuario->rut) }}</p>
            <p><strong>Nombre:</strong> {{ $solicitud->usuario->nombres }} {{ $solicitud->usuario->paterno }} {{ $solicitud->usuario->materno }}</p>
            <p><strong>Email:</strong> {{ $solicitud->usuario->email ?? 'No registrado' }}</p>
        </div>
    </div>

    {{-- DATOS DE LA ASIGNATURA --}}
    <div class="card mb-3">
        <div class="card-header bg-secondary text-white">Asignatura Solicitada</div>
        <div class="card-body">
            <p><strong>Asignatura:</strong> {{ $solicitud->asignatura->nombre }}</p>
            <p><strong>Docente:</strong> 
                {{ $solicitud->asignatura->usuario->nombres }}
                {{ $solicitud->asignatura->usuario->paterno }}
                {{ $solicitud->asignatura->usuario->materno }}</p>
            <p><strong>Sección:</strong> {{ $solicitud->asignatura->seccion ?? 'N/A' }}</p>
            <p><strong>Carrera (Asignatura):</strong> {{ $solicitud->asignatura->carrera->nombre }}</p>
            <p><strong>Carrera (Estudiante):</strong> {{ $solicitud->carreraEstudiante->nombre }}</p>
        </div>
    </div>

    {{-- DATOS BANCARIOS --}}
    <div class="card mb-3">
        <div class="card-header bg-success text-white">Datos Bancarios</div>
        <div class="card-body">
            <p><strong>Banco:</strong> {{ $datoBanco->tipoCuenta->banco->banco }}</p>
            <p><strong>Tipo de Cuenta:</strong> {{ $datoBanco->tipoCuenta->tipo_cuenta }}</p>
            <p><strong>N° de Cuenta:</strong> {{ $datoBanco->num_cuenta }}</p>
        </div>
    </div>

    {{-- ESTADO ACTUAL --}}
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Estado Actual de la Solicitud</div>
        <div class="card-body">
            @if($etapaActual)
                <p><strong>Etapa:</strong> {{ $etapaActual->etapa }}</p>
                <p><strong>Estado:</strong> {{ $etapaActual->estado_solicitud }}</p>
                <p><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($etapaActual->fecha_dig)->format('d-m-Y H:i') }}</p>
            @else
                <p>No hay historial registrado.</p>
            @endif
        </div>
    </div>

    {{-- HISTORIAL COMPLETO --}}
    <div class="card">
        <div class="card-header bg-dark text-white">Historial de la Solicitud</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Etapa</th>
                        <th>Estado</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($solicitud->historial as $historial)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($historial->fecha_dig)->format('d-m-Y H:i') }}</td>
                            <td>{{ $historial->etapa }}</td>
                            <td>{{ $historial->estado_solicitud }}</td>
                            <td>
                                {{ $historial->responsable->nombres ?? '' }}
                                {{ $historial->responsable->paterno ?? '' }}
                                {{ $historial->responsable->materno ?? '' }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Sin historial</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    @if (auth()->user()->perfil == 'Docente')
        <a href="{{ route('solicitudes-docente') }}" class="btn btn-secondary">Volver</a>
    @elseif (auth()->user()->perfil == 'Estudiante' ||auth()->user()->perfil == 'Admin' )
        <a href="{{ route('mis-solicitudes') }}" class="btn btn-secondary">Volver</a>
    @else 
        
    @endif
@endsection


