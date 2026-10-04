<input type="hidden" id="txtTipo" value="PAN">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Panel de Control</h1>
        <p class="text-muted small mb-0">Resumen operativo general de dormitorios y apartamentos - La Estanza (Año 2026)</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/control-anual') ?>" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
            <span>Ir a Control Anual</span>
        </a>
        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnRefrescarPanel">
            Actualizar
        </button>
    </div>
</div>

<!-- Tarjetas de Métricas Principales (KPIs) -->
<div class="row g-3 mb-4">
    <!-- Recaudación GTQ -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-kpi kpi-gtq">
            <div class="kpi-accent-bar"></div>
            <div class="kpi-label">Ingresos Recaudados (GTQ)</div>
            <div class="kpi-value text-primary"><?= money($resumen['finanzas']['recaudado_gtq'], 'GTQ') ?></div>
            <div class="kpi-subtext">
                <span class="badge bg-primary-subtle text-primary">+<?= money($resumen['finanzas']['mora_gtq'], 'GTQ') ?> mora</span>
                <span>cobrado en 2026</span>
            </div>
        </div>
    </div>

    <!-- Recaudación USD -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-kpi kpi-usd">
            <div class="kpi-accent-bar"></div>
            <div class="kpi-label">Ingresos Recaudados (USD)</div>
            <div class="kpi-value text-success"><?= money($resumen['finanzas']['recaudado_usd'], 'USD') ?></div>
            <div class="kpi-subtext">
                <span class="badge bg-success-subtle text-success">Suites / PH</span>
                <span>no convertido a GTQ</span>
            </div>
        </div>
    </div>

    <!-- Ocupación General -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-kpi kpi-ocupacion">
            <div class="kpi-accent-bar"></div>
            <div class="kpi-label">Tasa de Ocupación</div>
            <div class="kpi-value text-purple"><?= $resumen['apartamentos']['tasa_ocupacion'] ?>%</div>
            <div class="kpi-subtext">
                <span><strong><?= $resumen['apartamentos']['ocupados'] ?></strong> de <?= $resumen['apartamentos']['total'] ?> unidades ocupadas</span>
            </div>
            <div class="progress-kpi">
                <div class="progress-kpi-bar" style="width: <?= $resumen['apartamentos']['tasa_ocupacion'] ?>%;"></div>
            </div>
        </div>
    </div>

    <!-- Gastos Acumulados -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card-kpi kpi-gastos">
            <div class="kpi-accent-bar"></div>
            <div class="kpi-label">Gastos Registrados</div>
            <div class="kpi-value text-danger"><?= money($resumen['finanzas']['gastos_gtq'], 'GTQ') ?></div>
            <div class="kpi-subtext">
                <span class="text-danger small">+ <?= money($resumen['finanzas']['gastos_usd'], 'USD') ?></span>
                <span>en mantenimiento</span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Tabla de Últimos Pagos Registrados -->
    <div class="col-12 col-lg-7">
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="card-title-main">Últimos Pagos Recibidos</h2>
                    <p class="card-subtitle-main">Recibos y boletas de depósito validadas</p>
                </div>
                <a href="<?= url('/pagos') ?>" class="btn btn-outline-primary btn-sm">Ver todos</a>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Unidad / Inquilino</th>
                            <th>Período</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Referencia</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosPagos as $p): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?= e($p['apartamento_codigo']) ?></div>
                                    <div class="text-muted small"><?= e($p['inquilino_nombre']) ?></div>
                                </td>
                                <td>Mes <?= (int) $p['mes_periodo'] ?> / <?= (int) $p['anio'] ?></td>
                                <td><?= format_date($p['fecha_pago']) ?></td>
                                <td class="fw-bold"><?= money($p['monto'], $p['moneda']) ?></td>
                                <td><code><?= e($p['referencia']) ?></code></td>
                                <td><?= status_badge($p['estado']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Gastos Recientes y Estado de Inmuebles -->
    <div class="col-12 col-lg-5">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <div>
                    <h2 class="card-title-main">Gastos Operativos Recientes</h2>
                    <p class="card-subtitle-main">Mantenimiento, compras y servicios</p>
                </div>
                <a href="<?= url('/gastos') ?>" class="btn btn-outline-primary btn-sm">Ver todos</a>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Categoría</th>
                            <th>Propiedad / Unidad</th>
                            <th>Fecha</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosGastos as $g): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary"><?= e($g['categoria']) ?></span>
                                    <div class="small text-muted mt-1"><?= e($g['descripcion']) ?></div>
                                </td>
                                <td><?= e($g['apartamento_codigo']) ?></td>
                                <td><?= format_date($g['fecha']) ?></td>
                                <td class="fw-bold text-danger"><?= money($g['monto'], $g['moneda']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Widget Confirmado: Contratos que vencen (<= 30 dias) -->
        <div class="content-card mb-4 p-0 overflow-hidden border">
            <div class="p-3 bg-white border-bottom">
                <h3 class="h6 fw-bold text-dark mb-0">Contratos que vencen (&le; 30 días)</h3>
            </div>
            <div class="table-responsive">
                <table class="table mb-0 widget-contratos-vencen">
                    <thead class="bg-navy text-white" style="background-color: #1e3a5f; color: #ffffff;">
                        <tr>
                            <th class="py-2 px-3 fw-semibold text-white">Apto.</th>
                            <th class="py-2 px-3 fw-semibold text-white">Inquilino</th>
                            <th class="py-2 px-3 fw-semibold text-white">Vence</th>
                        </tr>
                    </thead>
                    <tbody style="background-color: #fff8ee;">
                        <?php foreach ($contratosPorVencer as $cpv): ?>
                            <tr style="border-bottom: 1px solid #f0e6d6;">
                                <td class="py-2 px-3 fw-semibold text-dark"><?= e($cpv['apartamento_codigo']) ?></td>
                                <td class="py-2 px-3 text-dark"><?= e($cpv['inquilino_nombre']) ?></td>
                                <td class="py-2 px-3 text-dark"><?= e($cpv['fecha_vence']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tarjeta de Contratos Activos -->
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="card-title-main">Resumen de Contratos</h2>
                    <p class="card-subtitle-main">Vigencia y renovaciones pendientes</p>
                </div>
                <a href="<?= url('/contratos') ?>" class="btn btn-outline-primary btn-sm">Gestionar</a>
            </div>
            <ul class="list-group list-group-flush small">
                <?php foreach (array_slice($contratos, 0, 4) as $con): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                        <div>
                            <div class="fw-bold text-dark"><?= e($con['codigo']) ?> - <?= e($con['apartamento_codigo']) ?></div>
                            <div class="text-muted"><?= e($con['inquilino_nombre']) ?> (Hasta: <?= format_date($con['fecha_fin']) ?>)</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold"><?= money($con['monto_alquiler'], $con['moneda']) ?></div>
                            <?= status_badge($con['estado']) ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
