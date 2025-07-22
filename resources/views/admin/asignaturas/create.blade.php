@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Crear Asignatura</h1>

    <form action="{{ route('asignaturas.store') }}" method="POST">
        @csrf
        @include('admin.asignaturas.form', ['asignatura' => null])
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('asignaturas.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2({
            placeholder: "seleciona un profesor...",
            allowClear: true,
            width: '100%'
        });
    </script>
@endpush
