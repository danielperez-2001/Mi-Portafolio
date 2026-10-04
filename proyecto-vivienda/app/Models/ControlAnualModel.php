<?php

namespace App\Models;

class ControlAnualModel
{
    public function obtenerMatrizAnual(int $anio = 2026, array $filtros = []): array
    {
        $apartamentoModel = new ApartamentoModel();
        $apartamentos = $apartamentoModel->filtrar($filtros);
        $todosPagos = DataDemo::getPagos();
        $todosGastos = DataDemo::getGastos();

        $filas = [];
        $totalesMesGTQ = array_fill(1, 12, 0.0);
        $totalesMesUSD = array_fill(1, 12, 0.0);
        $gastosMesGTQ = array_fill(1, 12, 0.0);
        $gastosMesUSD = array_fill(1, 12, 0.0);

        // Sumar gastos por mes y moneda
        foreach ($todosGastos as $gasto) {
            $gastoAnio = (int) date('Y', strtotime($gasto['fecha']));
            if ($gastoAnio === $anio) {
                $mes = (int) date('n', strtotime($gasto['fecha']));
                if ($gasto['moneda'] === 'USD') {
                    $gastosMesUSD[$mes] += (float) $gasto['monto'];
                } else {
                    $gastosMesGTQ[$mes] += (float) $gasto['monto'];
                }
            }
        }

        foreach ($apartamentos as $apto) {
            $codigoApto = $apto['codigo'];
            $moneda = $apto['moneda'];
            $rentaEsperada = (float) $apto['alquiler'];

            $meses = [];

            for ($mes = 1; $mes <= 12; $mes++) {
                // Filtrar pagos para este apartamento, año y mes de período
                $pagosMes = array_values(array_filter($todosPagos, function ($p) use ($codigoApto, $anio, $mes) {
                    return strtoupper($p['apartamento_codigo']) === strtoupper($codigoApto)
                        && (int) $p['anio'] === $anio
                        && (int) $p['mes_periodo'] === $mes;
                }));

                // Determinar estado de la celda
                if ($apto['estado'] === 'vacio' && empty($pagosMes)) {
                    $celda = [
                        'estado' => 'vacio',
                        'estado_texto' => 'Vacío',
                        'monto_pagado' => 0.0,
                        'monto_esperado' => $rentaEsperada,
                        'mora' => 0.0,
                        'moneda' => $moneda,
                        'pagos' => [],
                        'referencias' => [],
                        'fechas' => []
                    ];
                } elseif (!empty($pagosMes)) {
                    $montoTotalPagado = 0.0;
                    $moraTotal = 0.0;
                    $referencias = [];
                    $fechas = [];

                    foreach ($pagosMes as $p) {
                        $montoTotalPagado += (float) $p['monto'];
                        $moraTotal += (float) ($p['mora'] ?? 0);
                        if (!empty($p['referencia'])) {
                            $referencias[] = $p['referencia'];
                        }
                        if (!empty($p['fecha_pago'])) {
                            $fechas[] = $p['fecha_pago'];
                        }
                    }

                    if ($montoTotalPagado >= $rentaEsperada) {
                        $estado = 'pagado';
                        $estadoTexto = 'Pagado';
                    } else {
                        $estado = 'parcial';
                        $estadoTexto = 'Parcial';
                    }

                    $celda = [
                        'estado' => $estado,
                        'estado_texto' => $estadoTexto,
                        'monto_pagado' => $montoTotalPagado,
                        'monto_esperado' => $rentaEsperada,
                        'mora' => $moraTotal,
                        'moneda' => $moneda,
                        'pagos' => $pagosMes,
                        'referencias' => $referencias,
                        'fechas' => $fechas
                    ];

                    // Acumular a totales por moneda
                    if ($moneda === 'USD') {
                        $totalesMesUSD[$mes] += $montoTotalPagado;
                    } else {
                        $totalesMesGTQ[$mes] += $montoTotalPagado;
                    }
                } else {
                    // Si el apartamento está ocupado pero no hay pago registrado en ese mes
                    $celda = [
                        'estado' => ($mes <= 3) ? 'pendiente' : 'sin_informacion',
                        'estado_texto' => ($mes <= 3) ? 'Pendiente' : 'Sin Datos',
                        'monto_pagado' => 0.0,
                        'monto_esperado' => $rentaEsperada,
                        'mora' => 0.0,
                        'moneda' => $moneda,
                        'pagos' => [],
                        'referencias' => [],
                        'fechas' => []
                    ];
                }

                $meses[$mes] = $celda;
            }

            $filas[] = [
                'apartamento' => $apto,
                'meses' => $meses
            ];
        }

        return [
            'anio' => $anio,
            'filtros' => $filtros,
            'filas' => $filas,
            'resumen' => [
                'ingresos_gtq' => $totalesMesGTQ,
                'ingresos_usd' => $totalesMesUSD,
                'gastos_gtq' => $gastosMesGTQ,
                'gastos_usd' => $gastosMesUSD,
                'total_ingresos_gtq' => array_sum($totalesMesGTQ),
                'total_ingresos_usd' => array_sum($totalesMesUSD),
                'total_gastos_gtq' => array_sum($gastosMesGTQ),
                'total_gastos_usd' => array_sum($gastosMesUSD),
            ]
        ];
    }
}
