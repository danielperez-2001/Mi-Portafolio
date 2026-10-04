<input type="hidden" id="txtTipo" value="INQ_EDC">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Estado de cuenta por inquilino</h1>
        <p class="text-muted small mb-0">Consulta detallada de movimientos, cargos periódicos, abonos de renta, mora y saldos</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/inquilinos') ?>" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Volver al Directorio</span>
        </a>
    </div>
</div>

<!-- Selector Principal de Inquilino (según confirmación de diseño) -->
<div class="content-card p-3 mb-4">
    <div class="row align-items-center g-3">
        <div class="col-12 col-md-7 d-flex flex-wrap align-items-center gap-2">
            <label for="selectInquilinoEstadoCuenta" class="fw-bold text-dark mb-0 fs-6">Inquilino:</label>
            <div style="min-width: 280px; max-width: 420px; flex-grow: 1;">
                <select class="form-select border-dark shadow-sm" id="selectInquilinoEstadoCuenta" data-url="<?= url('/inquilinos/estado-cuenta/consultar') ?>">
                    <option value="">— Elige un inquilino —</option>
                    <?php foreach ($inquilinos as $inq): ?>
                        <option value="<?= (int) $inq['id'] ?>" <?= (isset($inquilinoSeleccionado) && $inquilinoSeleccionado['id'] == $inq['id']) ? 'selected' : '' ?>>
                            <?= e($inq['nombre']) ?> (<?= e($inq['apartamento_codigo']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-5 text-md-end">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnImprimirEstadoCuenta">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Imprimir / Exportar Comprobante</span>
            </button>
        </div>
    </div>
</div>

<!-- Contenedor Dinámico del Estado de Cuenta -->
<div id="contenedorEstadoCuenta">
    <?php if ($estadoCuenta): ?>
        <?php
            $inq = $estadoCuenta['inquilino'];
            $apto = $estadoCuenta['apartamento'];
            $con = $estadoCuenta['contrato'];
            $res = $estadoCuenta['resumen'];
            $movs = $estadoCuenta['movimientos'];
            $moneda = $res['moneda'] ?? 'GTQ';
        ?>
        <!-- Ficha Resumen del Inquilino, Contrato y Fiador -->
        <div class="content-card mb-4">
            <div class="row g-4">
                <div class="col-12 col-md-4 border-end-md">
                    <span class="badge bg-primary-subtle text-primary mb-2 fw-semibold">Datos del Inquilino</span>
                    <h4 class="fw-bold text-dark mb-1"><?= e($inq['nombre']) ?></h4>
                    <div class="small text-muted mb-1"><strong>Documento:</strong> <?= e($inq['documento']) ?></div>
                    <div class="small text-muted mb-1"><strong>Teléfono:</strong> <?= e($inq['telefono']) ?></div>
                    <div class="small text-muted"><strong>Correo:</strong> <?= e($inq['email']) ?></div>
                </div>
                <div class="col-12 col-md-4 border-end-md">
                    <span class="badge bg-secondary-subtle text-secondary mb-2 fw-semibold">Unidad y Contrato</span>
                    <h5 class="fw-bold text-dark mb-1"><?= e($inq['apartamento_codigo']) ?> - <?= e($apto['tipo'] ?? 'Unidad Habitacional') ?></h5>
                    <div class="small text-muted mb-1"><strong>Renta Mensual:</strong> <?= money($res['renta_mensual'], $moneda) ?></div>
                    <div class="small text-muted mb-1"><strong>Vencimiento:</strong> Día <?= e($inq['dia_vencimiento'] ?? 5) ?> de cada mes</div>
                    <div class="small text-muted"><strong>Vigencia:</strong> <?= format_date($con['fecha_inicio'] ?? '2026-01-01') ?> al <?= format_date($con['fecha_fin'] ?? '2026-12-31') ?></div>
                </div>
                <div class="col-12 col-md-4">
                    <span class="badge bg-info-subtle text-info mb-2 fw-semibold">Garantía y Fiador</span>
                    <div class="fw-bold text-dark mb-1"><?= e($inq['fiador_nombre'] ?? 'Sin fiador registrado') ?></div>
                    <div class="small text-muted mb-1"><strong>Teléfono Fiador:</strong> <?= e($inq['fiador_telefono'] ?? '-') ?></div>
                    <div class="small text-muted mb-1"><strong>DPI Fiador:</strong> <?= e($inq['fiador_dpi'] ?? '-') ?></div>
                    <div class="small text-muted">
                        <strong>Depósito en Garantía:</strong> <?= money($res['deposito_garantia'], $moneda) ?>
                        <span class="badge bg-light text-dark border ms-1">Custodiado (Devuelto: <?= e($res['deposito_devuelto']) ?>)</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Saldo Financiero -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi kpi-gtq">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Total Facturado (Cargos)</div>
                    <div class="kpi-value text-primary"><?= money($res['total_cargos'], $moneda) ?></div>
                    <div class="kpi-subtext">Rentas periódicas acumuladas</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi kpi-usd">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Total Pagos Recibidos</div>
                    <div class="kpi-value text-success"><?= money($res['total_pagos'], $moneda) ?></div>
                    <div class="kpi-subtext">Boletas y transferencias validadas</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi <?= ($res['saldo_pendiente'] > 0) ? 'kpi-gastos' : 'kpi-ocupacion' ?>">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Saldo Pendiente Actual</div>
                    <div class="kpi-value <?= ($res['saldo_pendiente'] > 0) ? 'text-danger' : 'text-success' ?>">
                        <?= money($res['saldo_pendiente'], $moneda) ?>
                    </div>
                    <div class="kpi-subtext">
                        <span class="badge bg-<?= $res['estado_cuenta_clase'] ?>-subtle text-<?= $res['estado_cuenta_clase'] ?>">
                            <?= e($res['estado_cuenta']) ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Depósito de Garantía</div>
                    <div class="kpi-value text-purple"><?= money($res['deposito_garantia'], $moneda) ?></div>
                    <div class="kpi-subtext">Fondo separado de rentas</div>
                </div>
            </div>
        </div>

        <!-- Tabla de Movimientos Contables -->
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="card-title-main">Historial Cronológico de Movimientos</h2>
                    <p class="card-subtitle-main">Detalle de cargos por renta, abonos, mora desglosada y saldo acumulado</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Concepto / Detalle</th>
                            <th class="text-end">Débito (+)</th>
                            <th class="text-end">Crédito (-)</th>
                            <th class="text-end">Saldo Acumulado</th>
                            <th>Boleta / Comprobante</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movs as $m): ?>
                            <tr>
                                <td><?= format_date($m['fecha']) ?></td>
                                <td>
                                    <?php if ($m['tipo'] === 'Cargo'): ?>
                                        <span class="badge bg-danger-subtle text-danger">Cargo</span>
                                    <?php elseif ($m['tipo'] === 'Abono'): ?>
                                        <span class="badge bg-success-subtle text-success">Abono</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary"><?= e($m['tipo']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="fw-semibold text-dark"><?= e($m['concepto']) ?></td>
                                <td class="text-end fw-bold <?= ((float)$m['debito'] > 0) ? 'text-dark' : 'text-muted' ?>">
                                    <?= ((float)$m['debito'] > 0) ? money($m['debito'], $moneda) : '-' ?>
                                </td>
                                <td class="text-end fw-bold <?= ((float)$m['credito'] > 0) ? 'text-success' : 'text-muted' ?>">
                                    <?= ((float)$m['credito'] > 0) ? money($m['credito'], $moneda) : '-' ?>
                                </td>
                                <td class="text-end fw-bold <?= ((float)$m['saldo'] > 0) ? 'text-danger' : 'text-dark' ?>">
                                    <?= money($m['saldo'], $moneda) ?>
                                </td>
                                <td><code><?= e($m['referencia']) ?></code></td>
                                <td><?= status_badge($m['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <!-- Estado Inicial / Vacío -->
        <div class="content-card text-center py-5">
            <div class="mb-3 text-muted">
                <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h5 class="fw-bold text-dark mb-2">Seleccione un inquilino</h5>
            <p class="text-muted small mx-auto" style="max-width: 480px;">
                Elija un inquilino del selector desplegable superior para consultar su estado de cuenta en tiempo real, desglose de cargos por período, abonos recibidos, comprobantes bancarios y balance.
            </p>
        </div>
    <?php endif; ?>
</div>
