@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Usuario</h1>

    <form action="{{ route('usuarios.update', $usuario->rut) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.usuarios.form', ['usuario' => $usuario])
    </form>
</div>
@endsection
