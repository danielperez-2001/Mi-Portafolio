<input type="hidden" id="txtTipo" value="APT_DET">

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <a href="<?= url('/apartamentos') ?>" class="text-decoration-none small text-muted">&larr; Volver al listado</a>
        <h1 class="h3 fw-bold text-dark mb-0 mt-1"><?= e($apartamento['codigo']) ?> - <?= e($apartamento['tipo']) ?></h1>
    </div>
    <span class="badge <?= ($apartamento['moneda'] === 'USD') ? 'badge-usd' : 'badge-gtq' ?> fs-6">
        <?= e($apartamento['moneda']) ?>
    </span>
</div>

<div class="row g-4 mb-4">
    <!-- Información General -->
    <div class="col-12 col-md-6">
        <div class="content-card h-100">
            <h2 class="card-title-main mb-3">Ficha Técnica</h2>
            <ul class="list-group list-group-flush small">
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Propiedad:</span>
                    <strong><?= e($apartamento['propiedad_nombre']) ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Nivel / Ubicación:</span>
                    <strong><?= e($apartamento['nivel']) ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Estado Actual:</span>
                    <div><?= status_badge($apartamento['estado']) ?></div>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Renta Pactada:</span>
                    <strong class="text-primary fs-6"><?= money($apartamento['alquiler'], $apartamento['moneda']) ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Depósito en Custodia:</span>
                    <strong><?= money($apartamento['deposito'], $apartamento['moneda']) ?></strong>
                </li>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <span class="text-muted">Inquilino Actual:</span>
                    <strong><?= e($apartamento['inquilino_actual'] ?? 'Sin Inquilino') ?></strong>
                </li>
            </ul>
            <div class="mt-3 p-2 bg-light rounded small text-muted">
                <strong>Características:</strong> <?= e($apartamento['caracteristicas']) ?>
            </div>
        </div>
    </div>

    <!-- Contrato Vigente -->
    <div class="col-12 col-md-6">
        <div class="content-card h-100">
            <h2 class="card-title-main mb-3">Contrato Asociado</h2>
            <?php if (!empty($contratos)): ?>
                <?php $con = $contratos[0]; ?>
                <div class="p-3 bg-light rounded mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-dark"><?= e($con['codigo']) ?></span>
                        <?= status_badge($con['estado']) ?>
                    </div>
                    <div class="small text-muted mb-1"><strong>Inquilino:</strong> <?= e($con['inquilino_nombre']) ?></div>
                    <div class="small text-muted mb-1"><strong>Vigencia:</strong> <?= format_date($con['fecha_inicio']) ?> al <?= format_date($con['fecha_fin']) ?> (<?= (int)$con['plazo_meses'] ?> meses)</div>
                    <div class="small text-muted mb-1"><strong>Tipo de Renovación:</strong> <?= e($con['tipo_renovacion']) ?></div>
                    <div class="small text-muted"><strong>Depósito de Garantía:</strong> <?= money($con['deposito_garantia'], $con['moneda']) ?> (<?= e($con['estado_deposito']) ?>)</div>
                </div>
            <?php else: ?>
                <div class="alert alert-secondary text-center small mb-0">
                    No cuenta con contrato vigente registrado en este momento.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Historial de Pagos de la Unidad -->
<div class="content-card">
    <div class="content-card-header">
        <h2 class="card-title-main">Historial de Pagos Recibidos</h2>
        <a href="<?= url('/pagos') ?>" class="btn btn-outline-secondary btn-sm">Ir a Módulo de Pagos</a>
    </div>

    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Período</th>
                    <th>Fecha Recepción</th>
                    <th>Monto Recibido</th>
                    <th>Mora</th>
                    <th>Método</th>
                    <th>Referencia Bancaria</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pagos)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-3 text-muted">No se registran pagos recibidos para esta unidad en 2026.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pagos as $p): ?>
                        <tr>
                            <td>Mes <?= (int) $p['mes_periodo'] ?> / <?= (int) $p['anio'] ?></td>
                            <td><?= format_date($p['fecha_pago']) ?></td>
                            <td class="fw-bold"><?= money($p['monto'], $p['moneda']) ?></td>
                            <td><?= money($p['mora'], $p['moneda']) ?></td>
                            <td><?= e($p['metodo']) ?></td>
                            <td><code><?= e($p['referencia']) ?></code></td>
                            <td><?= status_badge($p['estado']) ?></td>
                            <td class="small text-muted"><?= e($p['observaciones'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
