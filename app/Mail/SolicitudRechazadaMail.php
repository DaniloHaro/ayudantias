<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;


class SolicitudRechazadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreUsuario;
    public $nombreAsignatura;

    public function __construct($nombreUsuario, $nombreAsignatura)
    {
        $this->nombreUsuario = $nombreUsuario;
        $this->nombreAsignatura = $nombreAsignatura;
    }

    public function build()
    {
        Carbon::setLocale('es');
        $fecha = Carbon::now()->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i:s');

        $asunto = 'Tu solicitud no ha sido seleccionada  ' . $fecha;

        return $this->subject($asunto)
                    ->view('emails.solicitud_rechazada');
    }
}

