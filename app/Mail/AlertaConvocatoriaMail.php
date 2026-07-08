<?php

namespace App\Mail;

use App\Models\Convocatoria;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertaConvocatoriaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $convocatoria;

    public function __construct(Convocatoria $convocatoria)
    {
        $this->convocatoria = $convocatoria;
    }

    public function build()
    {
        return $this->subject('Alerta: convocatoria próxima a vencer')
            ->view('emails.alerta_convocatoria');
    }
}
