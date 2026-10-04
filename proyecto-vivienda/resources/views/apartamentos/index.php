<input type="hidden" id="txtTipo" value="APT">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Apartamentos y Dorms</h1>
        <p class="text-muted small mb-0">Inventario de unidades habitacionales, características y estado de ocupación</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoApartamento">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nuevo Apartamento (Demo)</span>
        </button>
    </div>
</div>

<!-- Barra de Búsqueda AJAX -->
<div class="content-card p-3 mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Código:</span>
                <input type="text" class="form-control" id="txtBuscarCodigo" placeholder="Ej. DORM-101, APT-201...">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnBuscarApartamento" data-url="<?= url('/apartamentos/buscar') ?>">
                Consultar Código
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarBusqueda">
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Apartamentos -->
<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom" id="tablaApartamentos">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Propiedad / Edificio</th>
                    <th>Tipo</th>
                    <th>Nivel</th>
                    <th>Moneda</th>
                    <th>Renta Base</th>
                    <th>Depósito</th>
                    <th>Estado</th>
                    <th>Inquilino Actual</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($apartamentos as $apto): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= e($apto['codigo']) ?></td>
                        <td><?= e($apto['propiedad_nombre']) ?></td>
                        <td><?= e($apto['tipo']) ?></td>
                        <td><?= e($apto['nivel']) ?></td>
                        <td>
                            <span class="badge <?= ($apto['moneda'] === 'USD') ? 'badge-usd' : 'badge-gtq' ?>">
                                <?= e($apto['moneda']) ?>
                            </span>
                        </td>
                        <td class="fw-bold"><?= money($apto['alquiler'], $apto['moneda']) ?></td>
                        <td><?= money($apto['deposito'], $apto['moneda']) ?></td>
                        <td><?= status_badge($apto['estado']) ?></td>
                        <td><?= e($apto['inquilino_actual'] ?? 'Sin Inquilino') ?></td>
                        <td class="text-end">
                            <a href="<?= url('/apartamentos/' . $apto['codigo']) ?>" class="btn btn-outline-secondary btn-sm py-0 px-2" title="Ver Detalle">
                                Detalle
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Formulario Nuevo Apartamento con Inquilino, Fiador y Garantía (Demo) -->
<div class="modal fade" id="modalApartamento" tabindex="-1" aria-labelledby="modalApartamentoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-dark fs-4" id="modalApartamentoTitulo">Nuevo apartamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formApartamentoDemo" data-url="<?= url('/apartamentos/validar-demostracion') ?>">
                <?= csrf_field() ?>
                <div class="modal-body pt-2">
                    <div class="alert alert-warning py-2 small mb-3">
                        <strong>Modo Demostración:</strong> El formulario validará la estructura de apartamento, inquilino, fiador y depósito, pero ningún dato será guardado en base de datos.
                    </div>

                    <!-- 1. Datos del apartamento -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Datos del apartamento</div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtEdificioApto" class="form-label small fw-semibold">Edificio*</label>
                                <input type="text" class="form-control" id="txtEdificioApto" name="edificio" placeholder="Ej. Adamant, El Dorm, Torre Alta" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtNumeroApto" class="form-label small fw-semibold">Número*</label>
                                <input type="text" class="form-control" id="txtNumeroApto" name="numero" placeholder="Ej. 101, 204, 227" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtRentaMensual" class="form-label small fw-semibold">Renta mensual (Q)</label>
                                <input type="number" step="0.01" class="form-control" id="txtRentaMensual" name="renta_mensual" value="0">
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtDiaVencimiento" class="form-label small fw-semibold">Día de vencimiento</label>
                                <input type="number" min="1" max="31" class="form-control" id="txtDiaVencimiento" name="dia_vencimiento" value="5">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="selectEstadoApto" class="form-label small fw-semibold">Estado</label>
                                <select class="form-select" id="selectEstadoApto" name="estado">
                                    <option value="Disponible" selected>Disponible</option>
                                    <option value="Ocupado">Ocupado</option>
                                    <option value="Mantenimiento">Mantenimiento</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtInicioContrato" class="form-label small fw-semibold">Inicio de contrato</label>
                                <input type="date" class="form-control" id="txtInicioContrato" name="fecha_inicio" placeholder="dd/mm/aaaa">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="txtFinContrato" class="form-label small fw-semibold">Fin de contrato</label>
                                <input type="date" class="form-control" id="txtFinContrato" name="fecha_fin" placeholder="dd/mm/aaaa">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Inquilino -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Inquilino</div>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-4">
                                <label for="txtInqNombre" class="form-label small fw-semibold">Nombre</label>
                                <input type="text" class="form-control" id="txtInqNombre" name="inquilino_nombre" placeholder="Nombre completo">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtInqTelefono" class="form-label small fw-semibold">Teléfono</label>
                                <input type="text" class="form-control" id="txtInqTelefono" name="inquilino_telefono" placeholder="+502 ....">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtInqCorreo" class="form-label small fw-semibold">Correo</label>
                                <input type="email" class="form-control" id="txtInqCorreo" name="inquilino_correo" placeholder="correo@ejemplo.com">
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="txtInqDpi" class="form-label small fw-semibold">DPI / identificación</label>
                                <input type="text" class="form-control" id="txtInqDpi" name="inquilino_dpi" placeholder="DPI o pasaporte">
                            </div>
                        </div>
                    </div>

                    <!-- 3. Fiador -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Fiador</div>
                        
                        <div class="row g-3">
                            <div class="col-12 col-md-4">
                                <label for="txtFiadorNombre" class="form-label small fw-semibold">Nombre del fiador</label>
                                <input type="text" class="form-control" id="txtFiadorNombre" name="fiador_nombre" placeholder="Nombre completo del fiador">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtFiadorTelefono" class="form-label small fw-semibold">Teléfono del fiador</label>
                                <input type="text" class="form-control" id="txtFiadorTelefono" name="fiador_telefono" placeholder="Teléfono de contacto">
                            </div>
                            <div class="col-12 col-md-4">
                                <label for="txtFiadorDpi" class="form-label small fw-semibold">DPI del fiador</label>
                                <input type="text" class="form-control" id="txtFiadorDpi" name="fiador_dpi" placeholder="DPI del fiador">
                            </div>
                        </div>
                    </div>

                    <!-- 4. Depósito de garantía -->
                    <div class="form-section-card mb-3">
                        <div class="form-section-title">Depósito de garantía</div>
                        
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="txtGarantiaMonto" class="form-label small fw-semibold">Monto (Q)</label>
                                <input type="number" step="0.01" class="form-control" id="txtGarantiaMonto" name="deposito_monto" value="0">
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="selectGarantiaDevuelto" class="form-label small fw-semibold">Devuelto</label>
                                <select class="form-select" id="selectGarantiaDevuelto" name="deposito_devuelto">
                                    <option value="No" selected>No</option>
                                    <option value="Sí">Sí</option>
                                </select>
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
