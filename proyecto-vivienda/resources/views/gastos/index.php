<input type="hidden" id="txtTipo" value="GAS">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Gastos, Mantenimiento y Compras</h1>
        <p class="text-muted small mb-0">Control de egresos operativos, reparaciones locativas, servicios y compras</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoGasto">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Registrar Gasto (Demo)</span>
        </button>
    </div>
</div>

<!-- Búsqueda AJAX -->
<div class="content-card p-3 mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Factura / Referencia:</span>
                <input type="text" class="form-control" id="txtBuscarGasto" placeholder="Ej. FAC-E-88192, REC-PINT...">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnBuscarGasto" data-url="<?= url('/gastos/buscar') ?>">
                Consultar Comprobante
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarGastos">
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Gastos -->
<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom" id="tablaGastos">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Categoría</th>
                    <th>Propiedad / Unidad</th>
                    <th>Descripción</th>
                    <th>Moneda</th>
                    <th>Monto</th>
                    <th>Factura / Comprobante</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($gastos as $g): ?>
                    <tr>
                        <td><?= format_date($g['fecha']) ?></td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary fw-semibold">
                                <?= e($g['categoria']) ?>
                            </span>
                        </td>
                        <td>
                            <div class="fw-semibold text-dark"><?= e($g['propiedad_ubicacion']) ?></div>
                            <div class="small text-muted">Unidad: <?= e($g['apartamento_codigo']) ?></div>
                        </td>
                        <td class="small" style="max-width: 280px;"><?= e($g['descripcion']) ?></td>
                        <td>
                            <span class="badge <?= ($g['moneda'] === 'USD') ? 'badge-usd' : 'badge-gtq' ?>">
                                <?= e($g['moneda']) ?>
                            </span>
                        </td>
                        <td class="fw-bold text-danger"><?= money($g['monto'], $g['moneda']) ?></td>
                        <td><code><?= e($g['referencia'] ?? '-') ?></code></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Formulario Registrar Gasto Demo (según confirmación img2.png) -->
<div class="modal fade" id="modalGasto" tabindex="-1" aria-labelledby="modalGastoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark fs-4" id="modalGastoTitulo">Registrar gasto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formGastoDemo" data-url="<?= url('/gastos/validar-demostracion') ?>">
                <?= csrf_field() ?>
                <div class="modal-body pt-2">
                    <div class="alert alert-warning py-2 small mb-3">
                        <strong>Modo Demostración:</strong> El gasto será validado pero no persistirá en base de datos.
                    </div>

                    <!-- Datos del gasto -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Datos del gasto</div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtFechaGasto" class="form-label small fw-semibold">Fecha*</label>
                                <input type="date" class="form-control" id="txtFechaGasto" name="fecha" value="<?= date('Y-m-d') ?>" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="selectAptoGasto" class="form-label small fw-semibold">Apartamento (opcional)</label>
                                <select class="form-select" id="selectAptoGasto" name="apartamento_codigo">
                                    <option value="General">— General / no aplica —</option>
                                    <?php foreach ($apartamentos as $a): ?>
                                        <option value="<?= e($a['codigo']) ?>"><?= e($a['codigo']) ?> - <?= e($a['tipo']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="selectCatGasto" class="form-label small fw-semibold">Categoría</label>
                                <select class="form-select" id="selectCatGasto" name="categoria">
                                    <option value="Mantenimiento" selected>Mantenimiento</option>
                                    <option value="Reparaciones">Reparaciones</option>
                                    <option value="Servicios">Servicios</option>
                                    <option value="Compras">Compras</option>
                                    <option value="Extras">Extras</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="txtDescGasto" class="form-label small fw-semibold">Descripción</label>
                                <input type="text" class="form-control" id="txtDescGasto" name="descripcion" placeholder="Detalle del gasto...">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtMontoGasto" class="form-label small fw-semibold">Monto (Q)*</label>
                                <input type="number" step="0.01" class="form-control" id="txtMontoGasto" name="monto" placeholder="0.00" required>
                                <input type="hidden" name="moneda" value="GTQ">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtRefGasto" class="form-label small fw-semibold">No. de factura</label>
                                <input type="text" class="form-control" id="txtRefGasto" name="referencia" placeholder="FAC-1234">
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
