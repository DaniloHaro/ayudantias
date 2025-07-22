@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Permiso</h2>

    <form action="{{ route('permisos.store') }}" method="POST">
        @include('admin.permisos.form', ['permiso' => null])

        <button type="submit" class="btn btn-primary">Guardar Permiso</button>
        <a href="{{ route('permisos.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
@push('scripts')
    <script>
        $('.select2').select2({
            placeholder: "seleciona un usuario...",
            allowClear: true,
            width: '100%'
        });
    </script>
@endpush
