<input type="hidden" id="txtTipo" value="CON">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Contratos de Alquiler</h1>
        <p class="text-muted small mb-0">Gestión de vigencias, depósitos en custodia, plazos y renovaciones</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoContrato">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nuevo Contrato (Demo)</span>
        </button>
    </div>
</div>

<!-- Búsqueda AJAX -->
<div class="content-card p-3 mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Código Contrato:</span>
                <input type="text" class="form-control" id="txtBuscarContrato" placeholder="Ej. CON-2026-001">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnBuscarContrato" data-url="<?= url('/contratos/buscar') ?>">
                Consultar Contrato
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarContratos">
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Contratos -->
<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom" id="tablaContratos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Unidad</th>
                    <th>Inquilino</th>
                    <th>Fecha Inicio</th>
                    <th>Vencimiento</th>
                    <th>Plazo</th>
                    <th>Renta Mensual</th>
                    <th>Depósito Garantía</th>
                    <th>Renovación</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contratos as $con): ?>
                    <tr>
                        <td class="fw-bold text-dark"><code><?= e($con['codigo']) ?></code></td>
                        <td>
                            <a href="<?= url('/apartamentos/' . $con['apartamento_codigo']) ?>" class="badge bg-primary-subtle text-primary text-decoration-none">
                                <?= e($con['apartamento_codigo']) ?>
                            </a>
                        </td>
                        <td><?= e($con['inquilino_nombre']) ?></td>
                        <td><?= format_date($con['fecha_inicio']) ?></td>
                        <td><?= format_date($con['fecha_fin']) ?></td>
                        <td><?= (int) $con['plazo_meses'] ?> meses</td>
                        <td class="fw-bold"><?= money($con['monto_alquiler'], $con['moneda']) ?></td>
                        <td>
                            <div><?= money($con['deposito_garantia'], $con['moneda']) ?></div>
                            <span class="badge bg-light text-muted border" style="font-size: 0.68rem;"><?= e($con['estado_deposito']) ?></span>
                        </td>
                        <td class="small text-muted"><?= e($con['tipo_renovacion']) ?></td>
                        <td><?= status_badge($con['estado']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Formulario Nuevo Contrato Demo -->
<div class="modal fade" id="modalContrato" tabindex="-1" aria-labelledby="modalContratoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalContratoTitulo">Registrar Contrato de Alquiler (Demo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formContratoDemo" data-url="<?= url('/contratos/validar-demostracion') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 small mb-3">
                        <strong>Modo Demostración:</strong> El contrato será validado en el servidor pero ningún registro persistirá en base de datos.
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label for="selectAptoContrato" class="form-label small fw-semibold">Apartamento / Dorm</label>
                            <select class="form-select form-select-sm" id="selectAptoContrato" name="apartamento_id">
                                <?php foreach ($apartamentos as $a): ?>
                                    <option value="<?= (int) $a['id'] ?>"><?= e($a['codigo']) ?> - <?= e($a['tipo']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label for="selectInqContrato" class="form-label small fw-semibold">Inquilino</label>
                            <select class="form-select form-select-sm" id="selectInqContrato" name="inquilino_id">
                                <?php foreach ($inquilinos as $i): ?>
                                    <option value="<?= (int) $i['id'] ?>"><?= e($i['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label for="txtFechaInicioContrato" class="form-label small fw-semibold">Fecha de Inicio</label>
                            <input type="date" class="form-control form-control-sm" id="txtFechaInicioContrato" name="fecha_inicio" value="2026-01-01">
                        </div>
                        <div class="col-6">
                            <label for="txtFechaFinContrato" class="form-label small fw-semibold">Fecha de Vencimiento</label>
                            <input type="date" class="form-control form-control-sm" id="txtFechaFinContrato" name="fecha_fin" value="2026-12-31">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-4">
                            <label for="selectMonedaContrato" class="form-label small fw-semibold">Moneda</label>
                            <select class="form-select form-select-sm" id="selectMonedaContrato" name="moneda">
                                <option value="GTQ">GTQ (Q)</option>
                                <option value="USD">USD ($)</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label for="txtRentaContrato" class="form-label small fw-semibold">Renta Mensual</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="txtRentaContrato" name="monto_alquiler" placeholder="0.00">
                        </div>
                        <div class="col-4">
                            <label for="txtGarantiaContrato" class="form-label small fw-semibold">Depósito Garantía</label>
                            <input type="number" step="0.01" class="form-control form-control-sm" id="txtGarantiaContrato" name="deposito_garantia" placeholder="0.00">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="txtRenovacionContrato" class="form-label small fw-semibold">Tipo de Renovación</label>
                        <input type="text" class="form-control form-control-sm" id="txtRenovacionContrato" name="tipo_renovacion" value="Anual automática con preaviso 30 días">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm">Validar Contrato Demo</button>
                </div>
            </form>
        </div>
    </div>
</div>
