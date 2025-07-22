<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SolicitudSeleccionadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nombreUsuario;
    public $nombreAsignatura;

    public function __construct($nombreUsuario, $nombreAsignatura )
    {
        $this->nombreUsuario = $nombreUsuario;
        $this->nombreAsignatura = $nombreAsignatura;
    }

    public function build()
    {
        Carbon::setLocale('es');
        $fecha = Carbon::now()->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i:s');
        $asunto = 'Tu solicitud para "' . $this->nombreAsignatura . '" ha sido seleccionada el ' . $fecha;

        return $this->subject($asunto)
                    ->view('emails.solicitud_seleccionada');
                    
    }
}
