<input type="hidden" id="txtTipo" value="CA">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Control Anual de Pagos y Ocupación</h1>
        <p class="text-muted small mb-0">Matriz mensual consolidada de rentas, pagos recibidos, mora y unidades vacías</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
        <!-- Selector dinámico de año -->
        <form method="GET" action="<?= url('/control-anual') ?>" class="d-flex align-items-center gap-2">
            <label for="selectAnioFiscal" class="small fw-bold text-muted text-nowrap">Año Fiscal:</label>
            <select name="anio" id="selectAnioFiscal" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="2025" <?= ($anio === 2025) ? 'selected' : '' ?>>2025</option>
                <option value="2026" <?= ($anio === 2026) ? 'selected' : '' ?>>2026 (Presupuesto Base)</option>
                <option value="2027" <?= ($anio === 2027) ? 'selected' : '' ?>>2027</option>
            </select>
        </form>
    </div>
</div>

<!-- Filtros de la Matriz (POST AJAX hacia /control-anual/filtrar) -->
<div class="content-card p-3 mb-3">
    <form id="formFiltrosControlAnual" data-url="<?= url('/control-anual/filtrar') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="anio" value="<?= (int) $anio ?>">

        <div class="row g-2 align-items-end">
            <div class="col-12 col-md-3">
                <label for="filtroPropiedadCA" class="form-label small fw-semibold mb-1">Propiedad / Módulo</label>
                <select name="propiedad_id" id="filtroPropiedadCA" class="form-select form-select-sm">
                    <option value="">-- Todas las propiedades --</option>
                    <?php foreach ($propiedades as $prop): ?>
                        <option value="<?= (int) $prop['id'] ?>"><?= e($prop['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-2">
                <label for="filtroMonedaCA" class="form-label small fw-semibold mb-1">Moneda</label>
                <select name="moneda" id="filtroMonedaCA" class="form-select form-select-sm">
                    <option value="">Todas (GTQ y USD)</option>
                    <option value="GTQ">Solo Quetzales (GTQ)</option>
                    <option value="USD">Solo Dólares (USD)</option>
                </select>
            </div>

            <div class="col-12 col-md-3">
                <label for="filtroBusquedaCA" class="form-label small fw-semibold mb-1">Apartamento o Inquilino</label>
                <input type="text" name="apartamento_codigo" id="filtroBusquedaCA" class="form-control form-control-sm" placeholder="Ej. DORM-101, Carlos...">
            </div>

            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-brand-primary btn-sm flex-grow-1" id="btnAplicarFiltrosCA">
                    Filtrar Matriz
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarFiltrosCA">
                    Limpiar
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Contenedor de la Matriz Anual con Scroll Horizontal y Sticky Columns -->
<div class="control-anual-wrapper">
    <!-- Leyenda de Estados de Celdas -->
    <div class="leyenda-estados">
        <span class="fw-bold text-dark me-2">Convención de Estados:</span>
        <span class="leyenda-badge"><span class="leyenda-circulo bg-success"></span> Pagado Completo</span>
        <span class="leyenda-badge"><span class="leyenda-circulo bg-warning"></span> Pago Parcial / Abono</span>
        <span class="leyenda-badge"><span class="leyenda-circulo bg-danger"></span> Pendiente / Saldo</span>
        <span class="leyenda-badge"><span class="leyenda-circulo bg-secondary"></span> Unidad Vacía</span>
        <span class="leyenda-badge"><span class="leyenda-circulo" style="background-color: #8b5cf6;"></span> Sin Información</span>
    </div>

    <div class="table-responsive-anual">
        <table class="tabla-matriz-anual" id="tablaControlAnual" data-url-detalle="<?= url('/control-anual/detalle-mes') ?>">
            <thead>
                <tr>
                    <th class="col-fija">Unidad / Renta Pactada</th>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <th class="text-center"><?= month_name($m) ?></th>
                    <?php endfor; ?>
                    <th class="text-end bg-light text-nowrap">Total Anual</th>
                </tr>
            </thead>
            <tbody class="cuerpo-filas">
                <?php foreach ($matriz['filas'] as $fila): ?>
                    <?php
                    $apto = $fila['apartamento'];
                    $meses = $fila['meses'];
                    $monedaSymbol = ($apto['moneda'] === 'USD') ? '$' : 'Q';
                    $totalApto = 0.0;
                    ?>
                    <tr>
                        <td class="col-fija">
                            <div class="d-flex align-items-center justify-content-between">
                                <a href="<?= url('/apartamentos/' . $apto['codigo']) ?>" class="fw-bold text-decoration-none text-dark">
                                    <?= e($apto['codigo']) ?>
                                </a>
                                <span class="badge <?= ($apto['moneda'] === 'USD') ? 'badge-usd' : 'badge-gtq' ?>">
                                    <?= e($apto['moneda']) ?>
                                </span>
                            </div>
                            <div class="small text-muted text-truncate" style="max-width: 210px;"><?= e($apto['tipo']) ?></div>
                            <div class="small text-muted mt-1">
                                Inq: <strong class="text-dark"><?= e($apto['inquilino_actual'] ?? 'Sin contrato') ?></strong>
                            </div>
                            <div class="small text-muted">
                                Renta: <strong><?= money($apto['alquiler'], $apto['moneda']) ?></strong>
                            </div>
                        </td>

                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <?php
                            $c = $meses[$m];
                            $claseEstado = 'celda-estado-' . $c['estado'];
                            $totalApto += (float) $c['monto_pagado'];
                            ?>
                            <td class="celda-mes">
                                <a href="javascript:void(0)" class="celda-item <?= $claseEstado ?> celda-mes-btn"
                                   data-apto="<?= e($apto['codigo']) ?>"
                                   data-mes="<?= $m ?>"
                                   data-anio="<?= (int) $anio ?>"
                                   data-estado="<?= e($c['estado']) ?>">
                                    <?php if ($c['estado'] === 'vacio'): ?>
                                        <span class="badge bg-light text-secondary border">Vacío</span>
                                    <?php elseif ($c['estado'] === 'sin_informacion'): ?>
                                        <span class="badge bg-light text-muted">-</span>
                                    <?php elseif ($c['estado'] === 'pendiente'): ?>
                                        <div class="text-danger fw-bold small">Pendiente</div>
                                        <div class="celda-meta text-danger"><?= money($c['monto_esperado'], $c['moneda']) ?></div>
                                    <?php else: ?>
                                        <div class="celda-monto"><?= money($c['monto_pagado'], $c['moneda']) ?></div>
                                        <?php if (!empty($c['referencias'])): ?>
                                            <span class="celda-ref"><?= e($c['referencias'][0]) ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($c['fechas'])): ?>
                                            <span class="celda-meta"><?= e(substr($c['fechas'][0], 5)) ?></span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </a>
                            </td>
                        <?php endfor; ?>

                        <td class="text-end fw-bold bg-light text-nowrap">
                            <?= money($totalApto, $apto['moneda']) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

            <!-- Filas de Resumen Financiero por Moneda (Separadas GTQ y USD) -->
            <tfoot>
                <!-- Total Ingresos GTQ -->
                <tr class="fila-total-gtq">
                    <td class="col-fija">
                        <strong>INGRESOS TOTALES (GTQ)</strong>
                        <div class="small text-muted fw-normal">Dorms y Aptos en Quetzales</div>
                    </td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td class="text-center total-ingresos-gtq-mes-<?= $m ?>">
                            <?= money($matriz['resumen']['ingresos_gtq'][$m], 'GTQ') ?>
                        </td>
                    <?php endfor; ?>
                    <td class="text-end total-ingresos-gtq-anual text-nowrap">
                        <?= money($matriz['resumen']['total_ingresos_gtq'], 'GTQ') ?>
                    </td>
                </tr>

                <!-- Total Ingresos USD -->
                <tr class="fila-total-usd">
                    <td class="col-fija">
                        <strong>INGRESOS TOTALES (USD)</strong>
                        <div class="small text-muted fw-normal">Suites y PH en Dólares</div>
                    </td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td class="text-center total-ingresos-usd-mes-<?= $m ?>">
                            <?= money($matriz['resumen']['ingresos_usd'][$m], 'USD') ?>
                        </td>
                    <?php endfor; ?>
                    <td class="text-end total-ingresos-usd-anual text-nowrap">
                        <?= money($matriz['resumen']['total_ingresos_usd'], 'USD') ?>
                    </td>
                </tr>

                <!-- Gastos Operativos GTQ -->
                <tr class="fila-gastos-gtq">
                    <td class="col-fija">
                        <strong>GASTOS OPERATIVOS (GTQ)</strong>
                        <div class="small text-muted fw-normal">Mantenimiento y Reparaciones</div>
                    </td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td class="text-center total-gastos-gtq-mes-<?= $m ?>">
                            <?= money($matriz['resumen']['gastos_gtq'][$m], 'GTQ') ?>
                        </td>
                    <?php endfor; ?>
                    <td class="text-end total-gastos-gtq-anual text-nowrap">
                        <?= money($matriz['resumen']['total_gastos_gtq'], 'GTQ') ?>
                    </td>
                </tr>

                <!-- Gastos Operativos USD -->
                <tr class="fila-gastos-usd">
                    <td class="col-fija">
                        <strong>GASTOS OPERATIVOS (USD)</strong>
                        <div class="small text-muted fw-normal">Repuestos especiales y HVAC</div>
                    </td>
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <td class="text-center total-gastos-usd-mes-<?= $m ?>">
                            <?= money($matriz['resumen']['gastos_usd'][$m], 'USD') ?>
                        </td>
                    <?php endfor; ?>
                    <td class="text-end total-gastos-usd-anual text-nowrap">
                        <?= money($matriz['resumen']['total_gastos_usd'], 'USD') ?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- Modal para Detalle de Pagos de un Mes -->
<div class="modal fade" id="modalDetalleMes" tabindex="-1" aria-labelledby="modalDetalleMesTitulo" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalDetalleMesTitulo">Detalle del Período</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalDetalleMesCuerpo">
                <!-- Se inyecta dinámicamente mediante modulos/control-anual.js -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
