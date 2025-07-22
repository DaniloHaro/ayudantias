<!-- Modal Perfil -->
<div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="perfilModalLabel">Mi Perfil</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
        @php
            $usuario = Auth::user();
            $rut = $usuario->rut;
        @endphp
       <form action="{{ route('perfil.actualizar') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div id="contenedor">
                <div id="msj_modal"></div>
                {{-- <form id="formPerfilUsuario"> --}}
                    <div class="row">
                    <div class="col-md-6">
                        <label><b>Rut</b></label>
                        <input class="form-control" disabled type="text" id="txt_rut_usuario" name="rut">
                    </div>
                    <div class="col-md-6">
                        <label><b>Nombres</b></label>
                        <input class="form-control" disabled id="txt_nombres_usuario" name="nombres">
                    </div>
                    </div><br>

                    <div class="row">
                    <div class="col-md-6">
                        <label><b>Paterno</b></label>
                        <input class="form-control" disabled id="txt_paterno_usuario" name="paterno">
                    </div>
                    <div class="col-md-6">
                        <label><b>Materno</b></label>
                        <input class="form-control" disabled id="txt_materno_usuario" name="materno">
                    </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            <label><b>Sexo Registral</b></label>
                            <select class="form-control" id="cbx_sexo_registral_usuario_form" name="sexo_registral">
                            <option value="">Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label><b>Email</b></label>
                            <input class="form-control" id="txt_email_usuario" name="email">
                        </div>
                    </div><br>
                    <div class="row">
                        <div class="col-md-6">
                            <label><b>N° Teléfonico</b></label>
                            <input class="form-control" id="txt_telefono" name="telefono">
                        </div>
                    </div><hr>

                    <h5><i class="fa-solid fa-building-columns"></i> Datos Bancarios</h5>
                    <div class="row">
                    <div class="col-md-6">
                        <label><b>Banco</b></label>                    
                        <select id="cbx_banco_form_edit" name="banco_id_edit" onchange="cargarTipoCuenta();" class="form-select @error('banco_id') is-invalid @enderror">
                            @foreach ($bancosPerfil as $banco)
                                <option value="{{ $banco->id }}"
                                    @if(
                                        isset($datosBancoPerfil) && 
                                        $datosBancoPerfil->tipoCuenta &&
                                        $datosBancoPerfil->tipoCuenta->banco_id == $banco->id
                                    ) selected @endif>
                                    {{ $banco->banco }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label><b>Tipo Cuenta</b></label>
                        <select id="cbx_tipo_cuenta_form_edit" name="tipo_cuenta_id" class="form-select @error('tipo_cuenta_id') is-invalid @enderror">
                            <option value="">Seleccione un tipo de cuenta</option>
                        </select>

                        @error('tipo_cuenta_id')
                            <div class="invalid-feedback">Debes seleccionar un tipo de cuenta</div>
                        @enderror
                    </div>
                    </div><br>
                    <div class="row">
                    <div class="col-md-12">
                        <label><b>N° Cuenta</b></label>
                        <input class="form-control" id="txt_num_cuenta" name="num_cuenta">
                    </div>
                    </div>
                {{-- </form> --}}
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button class="btn btn-success" onclick="guardarPerfil()">Guardar Cambios</button> --}}
                <button type="submit" class="btn btn-primary" id="btnGuardarPerfil"><i class="fa fa-save"></i> Guardar Cambios</button>
                {{-- <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button> --}}
            </div>
        </form>
    </div>
  </div>
</div>
{{-- @push('scripts') --}}
<script>
     const tipoCuentaActualId = {{ $datosBancoPerfil->tipo_cuenta_id ?? 'null' }};
    
    //$('#cbx_banco_form').on('change', function(){
       
            // const bancoId = $(this).val();
            // if (!bancoId) {
            //     $('#cbx_tipo_cuenta_form').html('<option value="">Seleccione un tipo de cuenta</option>');
            //     return;
            // }
            // $.ajax({
            //     url: `/admin/tipos-cuenta/${bancoId}`,
            //     type: 'GET',
            //     success: function(data) {
            //         let opciones = '<option value="">Seleccione un tipo de cuenta</option>';
            //         data.forEach(function(tipo) {
            //             opciones += `<option value="${tipo.id}">${tipo.tipo_cuenta}</option>`;
            //         });
            //         $('#cbx_tipo_cuenta_form').html(opciones);
            //     },
            //     error: function(xhr) {
            //         console.log(xhr.status);
            //         console.log(xhr.responseText);
            //         alert('Error al obtener tipos de cuenta.');
            //     }
            // });
    //});
</script>
{{-- @endpush --}}
