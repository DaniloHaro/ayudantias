@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if(session()->has('usuario_real_id'))
        <div class="alert alert-info">
            <strong>Modo simulación:</strong> Estás actuando como <strong>{{ auth()->user()->nombres }}</strong>.
        </div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (empty($datos_usuario->email) || empty($datos_usuario->telefono) && auth()->user()->perfil == 'Estudiante')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var perfilModal = new bootstrap.Modal(document.getElementById('perfilModal'));
                perfilModal.show();
            });
        </script>
    @endif 
    <h2 class="mb-4">Resumen General</h2>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user fa-fw text-primary me-2"></i>Perfil</h5>
                    <p class="card-text">{{ auth()->user()->perfil ?? 'N/D' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-id-card fa-fw text-success me-2"></i>RUT</h5>
                    <p class="card-text">{{ formatearRut(auth()->user()->rut) ?? 'N/D' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-user-tag fa-fw text-info me-2"></i>Nombre Completo</h5>
                    <p class="card-text">
                        @if (auth()->user()->nombre_social != '')
                            {{ auth()->user()->nombre_social ?? 'N/D' }}<br>
                        @else
                            {{ auth()->user()->nombres ?? 'N/D' }}<br>
                        @endif
                        {{ auth()->user()->paterno ?? '' }} {{ auth()->user()->materno ?? '' }}
                    </p>
                </div>
            </div>
        </div>
        <div>
            @if(auth()->user()->perfil === 'Admin' || auth()->user()->perfil === 'Estudiante')
                <div class="container text-center my-4">
                    <h4 class="mb-4">Acciones disponibles</h4>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('crear-solicitud') }}" class="btn btn-primary btn-lg shadow-sm">
                            <i class="fas fa-plus-circle me-2"></i> Nueva Solicitud
                        </a>
                        <a href="{{ route('mis-solicitudes') }}" class="btn btn-success btn-lg shadow-sm">
                            <i class="fas fa-list-alt me-2"></i> Ver Mis Solicitudes
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
