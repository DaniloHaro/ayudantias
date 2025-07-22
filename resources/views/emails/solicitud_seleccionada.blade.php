
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Solicitud Seleccionada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f1f1f1; margin: 0; padding: 0;">

    <!-- Contenedor principal centrado -->
    <table align="center" width="600" cellpadding="0" cellspacing="0" style="margin: 0 auto; background-color: #ffffff;">

        <!-- Encabezado con fondo y logo -->
        <tr>
            <td style="background-image: url('https://facso.cl/imagenes/banner_fondo.jpg'); background-size: cover; background-repeat: no-repeat; padding: 10px 15px;">
                <img src="https://facso.cl/app_facultad/facultad/app/panel/imagenes/logo_sidebar.png" width="160" alt="Logo Facultad" style="display: block;">
            </td>
        </tr>

        <!-- Contenido principal -->
        <tr>
            <td style="padding: 20px;">
                <h2 style="text-align: center; color: #333333; margin-top: 0;">Solicitud Seleccionada</h2>
                
                <p style="font-size: 15px; line-height: 1.6; color: #333333;">
                    Estimado/a estudiante:
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #333333;">
                    Tu solicitud para la asignatura <strong>{{ $nombreAsignatura }}</strong> ha sido <strong>seleccionada</strong>.
                </p>

                <p style="font-size: 15px; line-height: 1.6; color: #333333;">
                    Revisa el estado de tu solicitud y las acciones a realizar ingresando a la plataforma:
                </p>

                <div style="text-align: center; margin: 25px 0;">
                    <a href="https://ayudantias.facso.cl" style="background-color: #014A97; color: #ffffff; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                        Ir a la Plataforma
                    </a>
                </div>

                <p style="font-size: 15px; line-height: 1.6; color: #333333;">Saludos cordiales,</p>
                <p style="margin: 0;"><strong>Escuela de Pregrado</strong></p>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background-color: #014A97; color: #ffffff; text-align: center; padding: 10px 15px; font-size: 11px;">
                Facultad de Ciencias Sociales - Universidad de Chile
            </td>
        </tr>

    </table>
</body>
</html>
