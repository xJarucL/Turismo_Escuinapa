<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Evento;
use App\Models\EventoAnual;

class AlertaEvento extends Component
{
    public $eventos;
    public $eventosAnuales;

    public function __construct()
    {
        $now = Carbon::now();

        // Eventos normales
        $inicio = $now->copy()->addDays(1)->startOfDay();
        $fin = $now->copy()->addDays(14)->endOfDay();


        $this->eventos = Evento::whereBetween('fecha_hora', [$inicio, $fin])
                               ->where('estatus', 1)
                               ->get();

        // Eventos anuales
        $eventosAnuales = EventoAnual::where('estatus', 1)->get();

        $meses = [
            'enero' => 'january',
            'febrero' => 'february',
            'marzo' => 'march',
            'abril' => 'april',
            'mayo' => 'may',
            'junio' => 'june',
            'julio' => 'july',
            'agosto' => 'august',
            'septiembre' => 'september',
            'octubre' => 'october',
            'noviembre' => 'november',
            'diciembre' => 'december',
        ];

        $this->eventosAnuales = $eventosAnuales->filter(function ($evento) use ($now, $meses) {
            $referencia = strtolower(trim($evento->fecha_referencia));

            for ($i = 1; $i <= 14; $i++) {
                $fecha = $now->copy()->addDays($i);
                $formato1 = ltrim($fecha->format('d')) . '-' . ltrim($fecha->format('m'));
                $formato1Padded = $fecha->format('d-m');

                if ($referencia === $formato1 || $referencia === $formato1Padded) {
                    return true;
                }
            }

            foreach ($meses as $mesES => $mesEN) {
                if (Str::contains($referencia, $mesES)) {
                    $inicioMes = Carbon::parse("first day of $mesEN " . $now->year);

                    if ($inicioMes->between($now->copy()->addDays(1), $now->copy()->addDays(14))) {
                        return true;
                    }
                }
            }

            return false;
        });
    }

    public function render()
    {
        return view('components.alerta-evento');
    }
}
