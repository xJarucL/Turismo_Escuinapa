<?php

use Carbon\Carbon;

if (!function_exists('traducirFechaVariable')) {
    function traducirFechaVariable($referencia) {
        $partes = explode('-', $referencia);
        if (count($partes) !== 3) return $referencia;

        $ordinalesMasculinos = ['1' => 'primer', '2' => 'segundo', '3' => 'tercer', '4' => 'cuarto', '5' => 'quinto'];

        $ordinalesFemeninos = ['1' => 'primera', '2' => 'segunda', '3' => 'tercera', '4' => 'cuarta', '5' => 'quinta'];

        $numero = $partes[0];
        $tipo = strtolower($partes[1]);
        $mes = ucfirst($partes[2]);

        if ($tipo === 'semana') {
            $orden = $ordinalesFemeninos[$numero] ?? $numero;
            return "La {$orden} semana de {$mes}";
        }

        $orden = $ordinalesMasculinos[$numero] ?? $numero;
        return "Cada {$orden} {$tipo} de {$mes}";
    }
}



if (!function_exists('corregirFechaVariable')) {
    function corregirFechaVariable(string $referencia): string {
        $dias = [
            'miercoles' => 'miércoles',
            'sabado' => 'sábado',
            'miércoles' => 'miércoles',
            'sábado' => 'sábado',
            'semana' => 'semana',
        ];

        $meses = [
            'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
            'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
        ];

        $partes = explode('-', strtolower($referencia));
        if (count($partes) !== 3) return $referencia;

        $n = $partes[0];
        $dia = $dias[$partes[1]] ?? $partes[1];
        $mes = in_array($partes[2], $meses) ? $partes[2] : $partes[2];

        return "{$n}-{$dia}-{$mes}";
    }
}

if (!function_exists('validarFormatoFechaReferencia')) {
    function validarFormatoFechaReferencia(string $tipoFecha, string $fecha): bool
    {
        $dias = 'lunes|martes|miércoles|miercoles|jueves|viernes|sábado|sabado|domingo';
        $meses = 'enero|febrero|marzo|abril|mayo|junio|julio|agosto|septiembre|octubre|noviembre|diciembre';

        if ($tipoFecha === 'variable') {
            // Solo acepta días de la semana, NO "semana"
            return preg_match("/^\d{1,2}-($dias)-($meses)$/i", $fecha);
        }

        if ($tipoFecha === 'indefinida') {
            // Acepta solo si es exactamente "semana"
            return preg_match("/^\d{1,2}-(semana)-($meses)$/i", $fecha);
        }

        if ($tipoFecha === 'fija') {
            return preg_match('/^\d{1,2}-\d{2}$/', $fecha);
        }

        return false;
    }
}


if (!function_exists('calcularFechaDesdeVariable')) {
    function calcularFechaDesdeVariable($referencia, $anio = null) {
    $anio = $anio ?? date('Y');
    $partes = explode('-', strtolower($referencia));

    if (count($partes) !== 3) return null;

    [$n, $diaSemana, $mesNombre] = $partes;

    $meses = [
        'enero' => 1, 'febrero' => 2, 'marzo' => 3, 'abril' => 4,
        'mayo' => 5, 'junio' => 6, 'julio' => 7, 'agosto' => 8,
        'septiembre' => 9, 'octubre' => 10, 'noviembre' => 11, 'diciembre' => 12
    ];

    $diasSemana = [
        'domingo' => 0, 'lunes' => 1, 'martes' => 2,
        'miércoles' => 3, 'miercoles' => 3, 'jueves' => 4,
        'viernes' => 5, 'sábado' => 6, 'sabado' => 6
    ];

    if (!is_numeric($n) || !isset($meses[$mesNombre]) || !isset($diasSemana[$diaSemana])) {
        return null; // Validación extra
    }

    $mes = $meses[$mesNombre];
    $diaSemanaNum = $diasSemana[$diaSemana];

        if (!$mes || $diaSemanaNum === null) return null;

        $contador = 0;
        for ($dia = 1; $dia <= 31; $dia++) {
            if (!checkdate($mes, $dia, $anio)) break;

            $fecha = strtotime("$anio-$mes-$dia");
            if ((int)date('w', $fecha) === $diaSemanaNum) {
                $contador++;
                if ((int)$n === $contador) {
                    // Devuelve en formato DD-MM
                    $diaFormateado = str_pad($dia, 2, '0', STR_PAD_LEFT);
                    $mesFormateado = str_pad($mes, 2, '0', STR_PAD_LEFT);
                    return "$diaFormateado-$mesFormateado";
                }
            }
        }

        return null;
    }
}


if (!function_exists('traducirFechaDiaMes')) {
    function traducirFechaDiaMes($referencia) {
        try {
            return Carbon::createFromFormat('d-m', $referencia)->translatedFormat('j \d\e F');
        } catch (\Exception $e) {
            return $referencia;
        }
    }
}

if (!function_exists('formatearHora')) {
    function formatearHora($hora) {
        try {

            return Carbon::createFromFormat('H:i:s', $hora)->format('h:i A');

        } catch (\Exception $e) {
            return $hora;
        }
    }
}

if (!function_exists('fechaReferenciaConAnioActual')) {
    function fechaReferenciaConAnioActual($fechaReferencia)
    {
        // Espera formato "DD-MM"
        if (preg_match('/^\d{2}-\d{2}$/', $fechaReferencia)) {
            $anioActual = date('Y');
            [$dia, $mes] = explode('-', $fechaReferencia);
            return Carbon::createFromDate($anioActual, $mes, $dia)->format('Y-m-d');
        }

        return $fechaReferencia;
    }
}

?>
