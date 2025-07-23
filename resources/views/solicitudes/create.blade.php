@extends('layouts.app')
@section('content')
<div class="container">
    @if(!($etapa->fecha_inicio && $etapa->fecha_fin && now()->between($etapa->fecha_inicio, $etapa->fecha_fin)))
        <div class="card border-danger mb-4">
            <div class="card-header bg-danger text-white text-center">
                <h4 class="mb-0"><i class="fa fa-info-circle"></i> Proceso de Postulación Cerrado</h4>
            </div>
            <div class="card-body text-center">
                <p class="mb-3">El proceso de postulación actualmente se encuentra <strong>cerrado</strong>.</p>
                <p><strong>Inicio:</strong> {{ \Carbon\Carbon::parse($etapa->fecha_inicio)->translatedFormat('l d \d\e F \a \l\a\s H:i') }} Hrs.</p>
                <p><strong>Fin:</strong> {{ \Carbon\Carbon::parse($etapa->fecha_fin)->translatedFormat('l d \d\e F \a \l\a\s H:i') }}Hrs.</p>
            </div>
        </div>
    @else 
        @if ($llamadas_api > 5000)
            <div class="card border-warning mb-4">
                <div class="card-header bg-warning text-white text-center">
                    <h4 class="mb-0"><i class="fa fa-info-circle"></i><b> Atención!</b> Nos encontramos con Intermitencias</h4>
                </div>
                <div class="card-body text-center">
                    <p class="mb-3">La conexión con Ucampus está inestable, <strong>intentalo más tarde"</strong>.</p>
                </div>
            </div>
        @else
            @if ($sct_datos < 120)
                <div class="card border-danger mb-4">
                    <div class="card-header bg-danger text-white text-center">
                        <h4 class="mb-0"><i class="fa fa-info-circle"></i><b> Atención!</b> Falta cantidad de créditos aprobados</h4>
                    </div>
                    <div class="card-body text-center">
                        <p class="mb-3">Para realizar el proceso de postulación debes tener mínimo 120 créditos aprobados y solo tienes <strong>{{ $sct_datos }} créditos</strong>.</p>
                    </div>
                </div>
            @else
                <h2>Crear Solicitud</h2>
                <hr>  
                {{-- Mensajes de error globales --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>¡Ups! Hay errores en el formulario</strong>
                        </div>
                    @endif

                    <form id="form_formulario" method="POST" action="{{ route('solicitudes.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="card border-secondary">
                            <h5 class="card-header bg-secondary text-white p-2 bg-opacity-70">
                                <i class="fa-solid fa-file-invoice"></i> Beca Colaboración Académica
                            </h5>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <b>Carrera de origen</b>
                                        <select id="cbx_mi_carrera" name="id_carrera_estudiante" 
                                                class="form-select @error('id_carrera_estudiante') is-invalid @enderror"
                                                {{-- {{ $permiso ? 'disabled' : '' }} --}}  
                                                >
                                            <option value="">Seleccione una carrera</option>
                                            @foreach ($carreras as $carrera)
                                                <option value="{{ $carrera->id_carrera }}"
                                                    @if (old('id_carrera_estudiante') == $carrera->id_carrera || ($permiso && $permiso->id_carrera == $carrera->id_carrera))
                                                        selected
                                                    @endif>
                                                    {{ $carrera->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- @error('id_carrera_estudiante')
                                            <div class="invalid-feedback">Debes seleccionar una carrera.</div>
                                        @enderror --}}
                                    </div>
                                    <div class="col-md-6">
                                        <b>Carrera a la que postula</b>
                                        <select id="cbx_carrera" name="carrera_postula_id" class="form-select">
                                            <option value="">Seleccione una Carrera</option>
                                            @foreach ($carreras as $carrera)
                                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <b>Asignatura</b>
                                        <select id="cbx_asignatura" name="asignatura_id" class="select2 form-select @error('asignatura_id') is-invalid @enderror">
                                            <option value="">Seleccione una asignatura</option>
                                        </select>
                                        @error('asignatura_id')
                                            <div class="invalid-feedback">Debes seleccionar una asignatura.</div>
                                        @enderror
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div id="form_postulacion_3" class="col-md-12">
                                        <b>Datos Asignatura</b>
                                        <div class="card">
                                            <div class="card-body" id="info_asignatura">
                                                <!-- Se rellena con JS -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="mb-3">
                                        <b>Subir Carta de Motivación (PDF):</b>
                                        <input type="file" class="form-control @error('archivo_path') is-invalid @enderror" id="archivo_path" name="archivo_path" accept="application/pdf">
                                        @error('archivo_path')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <br>
                        <div id="datos_bancarios">
                            <div class="card border-secondary">
                                <h5 class="card-header bg-secondary text-white p-2 bg-opacity-70">
                                    <i class="fa-solid fa-building-columns"></i> Datos Bancarios
                                </h5>
                                <div class="card-body">
                                    <p class="fst-italic">
                                        <code>Estos datos bancarios pueden ser modificados antes de enviar la solicitud.</code>
                                    </p>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <b>Institución Financiera</b>
                                        <select id="cbx_banco_form" name="banco_id" class="form-select @error('banco_id') is-invalid @enderror">
                                                <option value="">Seleccione un banco</option>
                                                @foreach ($bancos as $banco)
                                                    <option value="{{ $banco->id }}" 
                                                        @if(old('banco_id') == $banco->id || (isset($datosBanco) && $datosBanco->tipoCuenta->banco->id == $banco->id)) 
                                                            selected 
                                                        @endif>
                                                        {{ $banco->banco }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('banco_id')
                                                <div class="invalid-feedback">Debes seleccionar un banco.</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <br>
                                    <div id="datos_segun_banco">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>Tipo Cuentas</b>
                                                <select id="cbx_tipo_cuenta_form" name="tipo_cuenta_id" class="form-select @error('tipo_cuenta_id') is-invalid @enderror">
                                                    @if (isset($datosBanco))
                                                        <option value="{{ $datosBanco->tipoCuenta->id }}" selected>{{ $datosBanco->tipoCuenta->tipo_cuenta }}</option>
                                                    @else
                                                        <option value="">Seleccione un tipo de cuenta</option>
                                                    @endif
                                                </select>
                                                @error('tipo_cuenta_id')
                                                    <div class="invalid-feedback">Debes seleccionar un tipo de cuenta</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <br>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <b>Nº Cuenta</b>
                                            <input type="text" class="form-control @error('numero_cuenta') is-invalid @enderror" id="txt_num_cuenta_form" name="numero_cuenta"
                                                        value="{{ old('numero_cuenta', $datosBanco->num_cuenta ?? '') }}" autocomplete="off">
                                                @error('numero_cuenta')
                                                    <div class="invalid-feedback">Debes ingresar un número de cuenta.</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <br><hr>
                                    {{-- <button type="submit" class="btn btn-primary">
                                        <i class="fa-solid fa-paper-plane"></i> Enviar Solicitud
                                    </button> --}}
                                    <button type="button" id="btnConfirmarEnvio" class="btn btn-primary">
                                        <i class="fa-solid fa-paper-plane"></i> Enviar Solicitud
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
            @endif
        @endif
     @endif
    </div>
@if (session('mostrar_modal_perfil'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = new bootstrap.Modal(document.getElementById('perfilModal'));
            modal.show();
        });
    </script>
@endif
@endsection

@push('scripts')
    <script>
        $('.select2').select2({
            placeholder: "seleciona una asignatura...",
            allowClear: true,
            width: '100%'
        });

        const tipoCuentaId = "{{ old('tipo_cuenta_id', $datosBanco->tipo_cuenta_id ?? '') }}";
        const bancoId = "{{ old('banco_id', $datosBanco->banco_id ?? '') }}";

        if (bancoId) {
            $('#cbx_banco_form').val(bancoId).trigger('change');
            // Espera a que se carguen los tipos de cuenta antes de seleccionar
            setTimeout(() => {
                $('#cbx_tipo_cuenta_form').val(tipoCuentaId);
            }, 500);
        }
        $('#cbx_carrera').on('change', function () {
            var carreraId = $(this).val();
            $('#cbx_asignatura').empty().append('<option value="">Cargando...</option>');
            if (carreraId) {
                //alert(carreraId);
                $.ajax({
                    url: '/admin/asignaturas-por-carrera/' + carreraId,
                    type: 'GET',
                    success: function (data) {
                        $('#cbx_asignatura').empty().append('<option value="">Seleccione una asignatura</option>');
                        $.each(data, function (index, asignatura) {
                            $('#cbx_asignatura').append(
                                '<option value="' + asignatura.id_asigantura + '">' +
                                asignatura.nombre + ' ( Sección ' + asignatura.se + ' - Prof. ' + asignatura.docente + ')'+
                                '</option>'
                            );
                        });
                    },
                    error: function (xhr) {
                        // console.log(xhr.status);
                        // console.log(xhr.responseText);
                        // alert('Error al obtener asignaturas.');
                        $('#cbx_asignatura').empty().append('<option value="">Error</option>');
                    }
                });
            } else {
                $('#cbx_asignatura').empty().append('<option value="">Seleccione una asignatura</option>');
            }
        });
        $('#cbx_asignatura').on('change', function() {
            const asignaturaId = $(this).val();
            if (asignaturaId) {
                $.ajax({
                    url: `/admin/asignaturas-info/${asignaturaId}`,
                    method: 'GET',
                    success: function(data) {
                        $('#info_asignatura').html(`
                            <p><strong>Nombre:</strong> ${data.nombre}</p>
                            <p><strong>Sección:</strong> ${data.seccion}</p>
                            <p><strong>Nombre Docente:</strong> ${data.nombre_completo}</p>
                            ${data.descripcion ? `<p><strong>Descripción:</strong> ${data.descripcion}</p>` : ''}
                            <p><strong>Bloque 1:</strong> ${data.bloque_1}</p>
                            ${data.bloque_2 ? `<p><strong>Bloque 2:</strong> ${data.bloque_2}</p>` : ''}
                            ${data.bloque_3 ? `<p><strong>Bloque 3:</strong> ${data.bloque_3}</p>` : ''}
                            <p><strong>Cupos:</strong> ${data.cupos}</p>
                        `);

                    },
                    error: function(xhr) {
                        console.log(xhr.status);
                        console.log(xhr.responseText);
                        $('#info_asignatura').html(`<p class="text-danger">No se pudo cargar la información de la asignatura.</p>`);
                    }
                });
            } else {
                $('#info_asignatura').empty();
            }
        });

        $('#cbx_banco_form').on('change', function(){
            const bancoId = $(this).val();
            if (!bancoId) {
                $('#cbx_tipo_cuenta_form').html('<option value="">Seleccione un tipo de cuenta</option>');
                return;
            }
            $.ajax({
                url: `/admin/tipos-cuenta/${bancoId}`,
                type: 'GET',
                success: function(data) {
                    let opciones = '<option value="">Seleccione un tipo de cuenta</option>';
                    data.forEach(function(tipo) {
                        opciones += `<option value="${tipo.id}">${tipo.tipo_cuenta}</option>`;
                    });
                    $('#cbx_tipo_cuenta_form').html(opciones);
                },
                error: function(xhr) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    alert('Error al obtener tipos de cuenta.');
                }
            });
        });

        
        document.getElementById('btnConfirmarEnvio').addEventListener('click', function () {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Deseas enviar esta solicitud? No podrás modificarla después.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0a58ca',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid  fa-check"></i> Sí, enviar',
                cancelButtonText: '<i class="fa-solid fa-xmark"></i> Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#btnConfirmarEnvio').prop('disabled', true);
                    $('#btnConfirmarEnvio').text('Procesando...');
                    document.getElementById('form_formulario').submit(); 
                }
            });
        });

        @if(session('sweet_error'))
            Swal.fire({
                icon: 'error',
                title: 'No es posible crear la Postulación',
                html: '{!! session('sweet_error') !!}',
            });
        @endif

    </script>
@endpush

