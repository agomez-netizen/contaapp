<?php

namespace App\Console\Commands;

use App\Mail\AlertaConvocatoriaMail;
use App\Models\Convocatoria;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarAlertasConvocatorias extends Command
{
    protected $signature = 'convocatorias:alertas';

    protected $description = 'Envía alertas por correo 7 días antes del cierre de convocatorias';

    public function handle()
    {
        $convocatorias = Convocatoria::where('alerta_7_dias', 1)
            ->where('alerta_enviada', 0)
            ->whereDate('fecha_cierre', now()->addDays(7)->toDateString())
            ->whereNotNull('correo_alerta')
            ->get();

        foreach ($convocatorias as $convocatoria) {
            Mail::to($convocatoria->correo_alerta)
                ->send(new AlertaConvocatoriaMail($convocatoria));

            $convocatoria->update([
                'alerta_enviada' => 1,
                'fecha_alerta_enviada' => now()
            ]);
        }

        $this->info('Alertas enviadas: ' . $convocatorias->count());
    }
}
