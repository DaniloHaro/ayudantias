@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Crear Usuario</h1>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @include('admin.usuarios.form')
    </form>
</div>
@endsection
@section('scripts')
<script>
    function limpiarRut(rut) {
        return rut.replace(/[^\dkK]/gi, '').toUpperCase();
    }

    function formatearRut(rut) {
        rut = limpiarRut(rut);
        if (rut.length < 2) return rut;

        let cuerpo = rut.slice(0, -1);
        let dv = rut.slice(-1);

        cuerpo = cuerpo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        return `${cuerpo}-${dv}`;
    }

    function validarRut(rut) {
        rut = limpiarRut(rut);
        if (rut.length < 2) return false;

        const cuerpo = rut.slice(0, -1);
        const dv = rut.slice(-1).toUpperCase();

        let suma = 0;
        let multiplo = 2;

        for (let i = cuerpo.length - 1; i >= 0; i--) {
            suma += parseInt(cuerpo.charAt(i)) * multiplo;
            multiplo = (multiplo === 7) ? 2 : multiplo + 1;
        }

        const dvEsperado = 11 - (suma % 11);
        const dvCalculado = dvEsperado === 11 ? '0' : dvEsperado === 10 ? 'K' : dvEsperado.toString();

        return dv === dvCalculado;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const rutInput = document.getElementById('rut');

        rutInput.addEventListener('input', () => {
            const raw = limpiarRut(rutInput.value);
            const formatted = formatearRut(raw);
            rutInput.value = formatted;
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            if (!validarRut(rutInput.value)) {
                e.preventDefault();
                alert('El RUT ingresado no es válido.');
                rutInput.classList.add('is-invalid');
                rutInput.focus();
            } else {
                rutInput.classList.remove('is-invalid');
            }
        });
    });
</script>

@endsection



