<input type="hidden" id="txtTipo" value="PAG">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Registro y Control de Pagos</h1>
        <p class="text-muted small mb-0">Gestión de rentas recibidas, depósitos bancarios, referencias, abonos y mora desglosada</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoPago">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Registrar Pago (Demo)</span>
        </button>
    </div>
</div>

<!-- Búsqueda AJAX por Referencia Bancaria -->
<div class="content-card p-3 mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Referencia Bancaria:</span>
                <input type="text" class="form-control" id="txtBuscarReferencia" placeholder="Ej. DEP-884910, TRF-102941...">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnBuscarReferencia" data-url="<?= url('/pagos/buscar') ?>">
                Consultar Comprobante
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarFiltroPagos">
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Pagos -->
<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom" id="tablaPagos">
            <thead>
                <tr>
                    <th>Unidad / Inquilino</th>
                    <th>Período Cubierto</th>
                    <th>Fecha Recepción</th>
                    <th>Monto Pagado</th>
                    <th>Mora Separada</th>
                    <th>Método de Pago</th>
                    <th>Referencia Bancaria</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pagos as $p): ?>
                    <tr>
                        <td>
                            <div class="fw-bold text-dark"><?= e($p['apartamento_codigo']) ?></div>
                            <div class="small text-muted"><?= e($p['inquilino_nombre']) ?></div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= month_name((int)$p['mes_periodo']) ?> / <?= (int)$p['anio'] ?>
                            </span>
                        </td>
                        <td><?= format_date($p['fecha_pago']) ?></td>
                        <td class="fw-bold"><?= money($p['monto'], $p['moneda']) ?></td>
                        <td>
                            <?php if ((float)$p['mora'] > 0): ?>
                                <span class="badge bg-danger-subtle text-danger"><?= money($p['mora'], $p['moneda']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= e($p['metodo']) ?></td>
                        <td><code><?= e($p['referencia']) ?></code></td>
                        <td><?= status_badge($p['estado']) ?></td>
                        <td class="small text-muted" style="max-width: 200px;"><?= e($p['observaciones'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Formulario Registrar Pago Demo (según confirmación img3.png) -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark fs-4" id="modalPagoTitulo">Registrar pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formPagoDemo" data-url="<?= url('/pagos/validar-demostracion') ?>">
                <?= csrf_field() ?>
                <div class="modal-body pt-2">
                    <div class="alert alert-warning py-2 small mb-3">
                        <strong>Modo Demostración:</strong> El pago se validará con las reglas de negocio pero ningún registro se insertará en base de datos.
                    </div>

                    <!-- Datos del pago -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Datos del pago</div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtFechaPago" class="form-label small fw-semibold">Fecha de pago*</label>
                                <input type="date" class="form-control" id="txtFechaPago" name="fecha_pago" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="selectAptoPago" class="form-label small fw-semibold">Apartamento</label>
                                <select class="form-select" id="selectAptoPago" name="apartamento_codigo">
                                    <option value="">— Sin asignar —</option>
                                    <?php foreach ($apartamentos as $a): ?>
                                        <option value="<?= e($a['codigo']) ?>" data-inquilino="<?= e($a['inquilino_actual'] ?? '') ?>" data-alquiler="<?= e($a['alquiler']) ?>">
                                            <?= e($a['codigo']) ?> - <?= e($a['tipo']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtMesAplicado" class="form-label small fw-semibold">Mes aplicado*</label>
                                <input type="text" class="form-control" id="txtMesAplicado" name="mes_aplicado" value="octubre de 2026" required>
                                <input type="hidden" id="selectMesPeriodo" name="mes_periodo" value="10">
                                <input type="hidden" id="txtAnioPago" name="anio" value="2026">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtInquilinoPago" class="form-label small fw-semibold">Inquilino</label>
                                <input type="text" class="form-control" id="txtInquilinoPago" name="inquilino_nombre" placeholder="Nombre del inquilino">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtMontoPago" class="form-label small fw-semibold">Monto (Q)*</label>
                                <input type="number" step="0.01" class="form-control" id="txtMontoPago" name="monto" placeholder="0.00" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtReferenciaPago" class="form-label small fw-semibold">No. de depósito / referencia</label>
                                <input type="text" class="form-control" id="txtReferenciaPago" name="referencia" placeholder="Boleta o referencia bancaria">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="txtMoraPago" class="form-label small fw-semibold">Mora (Q)</label>
                                <input type="number" step="0.01" class="form-control" id="txtMoraPago" name="mora" value="0">
                            </div>
                            <div class="col-12 col-md-8">
                                <label for="txtNotasPago" class="form-label small fw-semibold">Notas</label>
                                <input type="text" class="form-control" id="txtNotasPago" name="observaciones" placeholder="Notas adicionales del pago...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 justify-content-start gap-2">
                    <button type="submit" class="btn btn-navy px-4">Guardar</button>
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
