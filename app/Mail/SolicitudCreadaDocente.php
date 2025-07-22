<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;


class SolicitudCreadaDocente extends Mailable
{
    use Queueable, SerializesModels;

    public $solicitud;

    public function __construct($solicitud)
    {
        $this->solicitud = $solicitud;
    } 

    public function build()
    {
        Carbon::setLocale('es');
        $fecha = Carbon::parse($this->solicitud->created_at)->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i:s');

        //' . $this->solicitud->id_solicitud . 
        $asunto = 'Nueva Solicitud Ayudantías creada el ' . $fecha;

        return $this->subject($asunto)
                    ->view('emails.solicitud_creada_docente');
    }
}
