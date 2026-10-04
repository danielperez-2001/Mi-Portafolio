<?php

namespace App\Models;

class ReporteModel
{
    public function obtenerResumenGeneral(int $anio = 2026): array
    {
        $apartamentos = DataDemo::getApartamentos();
        $contratos = DataDemo::getContratos();
        $pagos = DataDemo::getPagos();
        $gastos = DataDemo::getGastos();

        $totalAptos = count($apartamentos);
        $ocupados = count(array_filter($apartamentos, fn($a) => $a['estado'] === 'ocupado'));
        $vacios = count(array_filter($apartamentos, fn($a) => $a['estado'] === 'vacio'));
        $tasaOcupacion = $totalAptos > 0 ? round(($ocupados / $totalAptos) * 100, 1) : 0;

        $recaudadoGTQ = 0.0;
        $recaudadoUSD = 0.0;
        $moraGTQ = 0.0;
        $moraUSD = 0.0;

        foreach ($pagos as $p) {
            if ((int) $p['anio'] === $anio) {
                if ($p['moneda'] === 'USD') {
                    $recaudadoUSD += (float) $p['monto'];
                    $moraUSD += (float) ($p['mora'] ?? 0);
                } else {
                    $recaudadoGTQ += (float) $p['monto'];
                    $moraGTQ += (float) ($p['mora'] ?? 0);
                }
            }
        }

        $gastosGTQ = 0.0;
        $gastosUSD = 0.0;
        $gastosPorCategoria = [];

        foreach ($gastos as $g) {
            if ($g['moneda'] === 'USD') {
                $gastosUSD += (float) $g['monto'];
            } else {
                $gastosGTQ += (float) $g['monto'];
            }

            $cat = $g['categoria'];
            if (!isset($gastosPorCategoria[$cat])) {
                $gastosPorCategoria[$cat] = ['GTQ' => 0.0, 'USD' => 0.0];
            }
            $gastosPorCategoria[$cat][$g['moneda']] += (float) $g['monto'];
        }

        return [
            'anio' => $anio,
            'apartamentos' => [
                'total' => $totalAptos,
                'ocupados' => $ocupados,
                'vacios' => $vacios,
                'tasa_ocupacion' => $tasaOcupacion
            ],
            'finanzas' => [
                'recaudado_gtq' => $recaudadoGTQ,
                'recaudado_usd' => $recaudadoUSD,
                'mora_gtq' => $moraGTQ,
                'mora_usd' => $moraUSD,
                'gastos_gtq' => $gastosGTQ,
                'gastos_usd' => $gastosUSD,
                'balance_gtq' => $recaudadoGTQ - $gastosGTQ,
                'balance_usd' => $recaudadoUSD - $gastosUSD
            ],
            'gastos_por_categoria' => $gastosPorCategoria,
            'contratos_activos' => count(array_filter($contratos, fn($c) => $c['estado'] === 'vigente'))
        ];
    }
}
