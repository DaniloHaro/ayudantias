@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle de Usuario</h1>

    <div class="mb-3"><strong>RUT:</strong> {{ $usuario->rut }}</div>
    <div class="mb-3"><strong>Nombre Completo:</strong> {{ $usuario->nombres }} {{ $usuario->paterno }} {{ $usuario->materno }}</div>
    <div class="mb-3"><strong>Email:</strong> {{ $usuario->email }}</div>
    <div class="mb-3"><strong>Sexo Registral:</strong> {{ $usuario->sexo_registral }}</div>
    <div class="mb-3"><strong>Género:</strong> {{ $usuario->genero }}</div>
    <div class="mb-3"><strong>Cuenta/Pasaporte:</strong> {{ $usuario->cuenta_pasaporte }}</div>
    <div class="mb-3"><strong>Estado:</strong> {{ $usuario->estado ? 'Activo' : 'Inactivo' }}</div>

    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>
</div>
@endsection
