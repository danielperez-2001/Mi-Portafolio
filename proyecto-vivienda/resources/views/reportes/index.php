<input type="hidden" id="txtTipo" value="REP">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Reportes e Informes Financieros</h1>
        <p class="text-muted small mb-0">Consolidado anual de cobranza, gastos por categoría y balance por moneda</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary btn-sm" id="btnImprimirReporte">
            Imprimir Informe
        </button>
        <button type="button" class="btn btn-brand-primary btn-sm" id="btnExportarDemo">
            Exportar (Demo)
        </button>
    </div>
</div>

<!-- Resumen por Moneda -->
<div class="row g-3 mb-4">
    <!-- Balance GTQ -->
    <div class="col-12 col-md-6">
        <div class="content-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title-main">Balance Operativo en Quetzales (GTQ)</h2>
                <span class="badge badge-gtq fs-6">GTQ</span>
            </div>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Total Ingresos Recaudados (Rentas + Mora):</span>
                    <strong class="text-primary"><?= money($resumen['finanzas']['recaudado_gtq'], 'GTQ') ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Total Gastos y Mantenimiento:</span>
                    <strong class="text-danger">- <?= money($resumen['finanzas']['gastos_gtq'], 'GTQ') ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 bg-light p-2 rounded mt-2">
                    <span class="fw-bold">Flujo Neto Operativo (GTQ):</span>
                    <strong class="text-success fs-6"><?= money($resumen['finanzas']['balance_gtq'], 'GTQ') ?></strong>
                </li>
            </ul>
        </div>
    </div>

    <!-- Balance USD -->
    <div class="col-12 col-md-6">
        <div class="content-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="card-title-main">Balance Operativo en Dólares (USD)</h2>
                <span class="badge badge-usd fs-6">USD</span>
            </div>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Total Ingresos Recaudados (Suites y PH):</span>
                    <strong class="text-success"><?= money($resumen['finanzas']['recaudado_usd'], 'USD') ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span>Total Gastos en Dólares (Mantenimiento Especial):</span>
                    <strong class="text-danger">- <?= money($resumen['finanzas']['gastos_usd'], 'USD') ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0 bg-light p-2 rounded mt-2">
                    <span class="fw-bold">Flujo Neto Operativo (USD):</span>
                    <strong class="text-success fs-6"><?= money($resumen['finanzas']['balance_usd'], 'USD') ?></strong>
                </li>
            </ul>
            <div class="form-text small mt-2">
                * Las monedas se mantienen estrictamente independientes sin conversión ficticia para evitar descalce cambiario.
            </div>
        </div>
    </div>
</div>

<!-- Desglose de Gastos por Categoría -->
<div class="content-card">
    <h2 class="card-title-main mb-3">Distribución de Gastos por Categoría</h2>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Categoría de Gasto</th>
                    <th>Egresos en Quetzales (GTQ)</th>
                    <th>Egresos en Dólares (USD)</th>
                    <th>Estado de Revisión</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($resumen['gastos_por_categoria'] as $cat => $montos): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= e($cat) ?></td>
                        <td><?= money($montos['GTQ'], 'GTQ') ?></td>
                        <td><?= money($montos['USD'], 'USD') ?></td>
                        <td><span class="badge bg-success-subtle text-success">Consolidado Demo</span></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
