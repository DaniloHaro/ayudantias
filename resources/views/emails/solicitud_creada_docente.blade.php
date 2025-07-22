@php
use Carbon\Carbon;

Carbon::setLocale('es');
$fechaFormateada = Carbon::parse($solicitud->created_at)->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i');
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Solicitud</title>
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
                <h2 style="text-align: center; color: #333333; margin-top: 0;">Nueva Solicitud Recibida</h2>
                <p style="font-size: 15px; line-height: 1.5; color: #333333;">
                    Se ha creado una nueva solicitud de ayudantía en la asignatura: <strong>{{ $solicitud->asignatura->nombre }}</strong>.
                </p>
                <ul style="font-size: 15px; color: #333; padding-left: 20px;">
                    <li><strong>Estudiante:</strong> {{ $solicitud->estudiante->nombres.' '.$solicitud->estudiante->paterno.' '.$solicitud->estudiante->materno ?? 'Nombre no disponible' }}</li>
                    <li><strong>RUT:</strong> {{ $solicitud->estudiante->rut ?? '---' }}</li>
                    <li><strong>ID Solicitud:</strong> {{ $solicitud->id_solicitud }}</li>
                    <li><strong>Fecha:</strong> {{ $fechaFormateada }}</li>
                </ul>

                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ route('solicitudes-docente') }}" style="background-color: #014A97; color: #ffffff; padding: 12px 24px; border-radius: 5px; text-decoration: none; font-weight: bold;">
                        Ver Solicitudes
                    </a>
                </div>

                <p style="margin-top: 30px;">Saludos cordiales,</p>
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




