<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCA - FACSO </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .sidebar-collapsed {
            width: 70px !important;
        }

        .sidebar-collapsed .sidebar-text {
            display: none;
        }

        #main-content {
            transition: margin-left 0.3s;
            margin-left: 250px;
        }

        .with-sidebar-collapsed {
            margin-left: 70px !important;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    @include('layouts.header')
    <div class="d-flex pt-5">
        @include('layouts.sidebar')
        <main id="main-content" class="flex-grow-1 d-flex flex-column" style="margin-left: 250px;">
            <div class="flex-grow-1 overflow-auto p-4">
                @yield('content')
            </div>
            @include('layouts.modal')
            @include('layouts.footer')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    {{-- <script>
       $( document ).ready(function() {
            cargarTipoCuenta();
            $('#usuario-select').select2({
                placeholder: "Simular usuario...",
                allowClear: true,
                width: '100%'
            });
        });
        function cargarTipoCuenta(){
            debugger;
            var bancoId = $('#cbx_banco_form_edit').val();
            if (!bancoId) {
                $('#cbx_tipo_cuenta_form_edit').html('<option value="">Seleccione un tipo de cuenta</option>');
                return;
            }
            $.ajax({
                url: `/admin/tipos-cuenta/${bancoId}`,
                type: 'GET',
                success: function(data) {
                    //$('#cbx_tipo_cuenta_form_edit').html('');
                    let opciones = '<option value="">Seleccione un tipo de cuenta</option>';
                    data.forEach(function(tipo) {
                        const selected = (tipo.id == tipoCuentaActualId) ? 'selected' : '';
                        opciones += `<option value="${tipo.id}" ${selected}>${tipo.tipo_cuenta}</option>`;
                    });
                    $('#cbx_tipo_cuenta_form_edit').html(opciones);
                
                },
                error: function(xhr) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    alert('Error al obtener tipos de cuenta.');
                }
            });

        }
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const toggleSidebarFromNavbar = document.getElementById('toggleSidebarFromNavbar');

        toggleSidebarFromNavbar.addEventListener('click', () => {
            sidebar.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('sidebar-expanded');
            mainContent.classList.toggle('with-sidebar-collapsed');
        });

        $('#perfilModal').on('show.bs.modal', function () {
            $.get('/admin/perfil/datos', function(data) {
                $('#txt_rut_usuario').val(data.rut);
                $('#txt_nombres_usuario').val(data.nombres);
                $('#txt_paterno_usuario').val(data.paterno);
                $('#txt_materno_usuario').val(data.materno);
                $('#cbx_sexo_registral_usuario_form').val(data.sexo_registral);
                $('#txt_email_usuario').val(data.email);
                $('#txt_telefono').val(data.telefono);

                const datosBanco = data.datos_banco?.[0]; // último registro
                if (datosBanco) {
                    $('#txt_banco').val(datosBanco.tipo_cuenta?.banco?.banco);
                    $('#txt_tipo_cuenta').val(datosBanco.tipo_cuenta?.tipo_cuenta);
                    $('#txt_num_cuenta').val(datosBanco.num_cuenta);
                }
            });
        });
    </script> --}}
    <script>
    // Cargar tipos de cuenta al cambiar el banco
    $('#cbx_banco_form_edit').on('change', function () {
        cargarTipoCuenta();
    });

    // Función para cargar tipos de cuenta según banco seleccionado
    function cargarTipoCuenta(){
        const bancoId = $('#cbx_banco_form_edit').val();
        if (!bancoId) {
            $('#cbx_tipo_cuenta_form_edit').html('<option value="">Seleccione un tipo de cuenta</option>');
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
                $('#cbx_tipo_cuenta_form_edit').html(opciones);
            },
            error: function(xhr) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                alert('Error al obtener tipos de cuenta.');
            }
        });
    }

    // Cargar los datos del usuario y bancarios al abrir la modal
    $('#perfilModal').on('show.bs.modal', function () {
        $.get('/admin/perfil/datos', function(data) {
            $('#txt_rut_usuario').val(data.rut);
            $('#txt_nombres_usuario').val(data.nombres);
            $('#txt_paterno_usuario').val(data.paterno);
            $('#txt_materno_usuario').val(data.materno);
            $('#cbx_sexo_registral_usuario_form').val(data.sexo_registral);
            $('#txt_email_usuario').val(data.email);
            $('#txt_telefono').val(data.telefono);

            const datosBanco = data.datos_banco?.[0]; // último registro
            if (datosBanco) {
                // Asignar banco
                $('#cbx_banco_form_edit').val(datosBanco.tipo_cuenta?.banco_id).change();

                // Cargar tipo de cuenta con retardo para esperar AJAX
                const tipoCuentaId = datosBanco.tipo_cuenta_id;
                setTimeout(() => {
                    $('#cbx_tipo_cuenta_form_edit').val(tipoCuentaId);
                }, 500);

                $('#txt_num_cuenta').val(datosBanco.num_cuenta);
            } else {
                // Limpiar campos si no hay datos bancarios
                $('#cbx_banco_form_edit').val('');
                $('#cbx_tipo_cuenta_form_edit').html('<option value="">Seleccione un tipo de cuenta</option>');
                $('#txt_num_cuenta').val('');
            }
        });
    });

    // Inicializar select2 si lo estás usando en otro campo
    $(document).ready(function() {
        $('#usuario-select').select2({
            placeholder: "Simular usuario...",
            allowClear: true,
            width: '100%'
        });
    });

    // Sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleSidebarFromNavbar = document.getElementById('toggleSidebarFromNavbar');

    toggleSidebarFromNavbar?.addEventListener('click', () => {
        sidebar.classList.toggle('sidebar-collapsed');
        sidebar.classList.toggle('sidebar-expanded');
        mainContent.classList.toggle('with-sidebar-collapsed');
    });
</script>

    @yield('scripts')
    @stack('scripts')

</body>
</html>
