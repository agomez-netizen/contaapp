<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BloquearDonacionesMensuales extends Command
{
    protected $signature = 'bloquear:donaciones-mensuales';
    protected $description = 'Bloquea automáticamente donaciones del mes actual al final del mes si no fueron bloqueadas.';

    public function handle()
    {
        $today = Carbon::today();

        // Solo corre el último día del mes
        if (!$today->isLastOfMonth()) {
            $this->info('Hoy no es el último día del mes. No se hace nada.');
            return 0;
        }

        $month = $today->month;
        $year  = $today->year;

        // Bloquea todas las donaciones del mes actual que estén desbloqueadas
        $updated = DB::table('donaciones')
            ->whereYear('fecha_despachada', $year)
            ->whereMonth('fecha_despachada', $month)
            ->where('bloqueado', 0)
            ->update(['bloqueado' => 1]);

        $this->info("Bloqueadas automáticamente: {$updated}");
        return 0;
    }
}
