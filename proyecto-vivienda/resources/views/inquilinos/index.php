<input type="hidden" id="txtTipo" value="INQ">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Directorio de Inquilinos</h1>
        <p class="text-muted small mb-0">Gestión de contactos, documentos de identidad y asignación de dormitorios</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= url('/inquilinos/estado-cuenta') ?>" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Estado de Cuenta</span>
        </a>
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoInquilino">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nuevo Inquilino (Demo)</span>
        </button>
    </div>
</div>

<!-- Búsqueda AJAX -->
<div class="content-card p-3 mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-white">Buscar:</span>
                <input type="text" class="form-control" id="txtBuscarInquilino" placeholder="Nombre, DPI o código de apartamento...">
            </div>
        </div>
        <div class="col-12 col-md-4 d-flex gap-2">
            <button type="button" class="btn btn-primary btn-sm" id="btnBuscarInquilino" data-url="<?= url('/inquilinos/buscar') ?>">
                Buscar Inquilino
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimpiarInquilinos">
                Limpiar
            </button>
        </div>
    </div>
</div>

<!-- Tabla de Inquilinos -->
<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom" id="tablaInquilinos">
            <thead>
                <tr>
                    <th>Nombre Completo</th>
                    <th>Documento (DPI / Pasaporte)</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Unidad Asignada</th>
                    <th>Fiador</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inquilinos as $inq): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= e($inq['nombre']) ?></td>
                        <td><code><?= e($inq['documento']) ?></code></td>
                        <td><?= e($inq['telefono']) ?></td>
                        <td><?= e($inq['email']) ?></td>
                        <td>
                            <a href="<?= url('/apartamentos/' . $inq['apartamento_codigo']) ?>" class="badge bg-primary-subtle text-primary text-decoration-none">
                                <?= e($inq['apartamento_codigo']) ?>
                            </a>
                        </td>
                        <td class="small text-muted"><?= e($inq['fiador_nombre'] ?? '-') ?></td>
                        <td><?= status_badge($inq['estado']) ?></td>
                        <td class="text-end">
                            <a href="<?= url('/inquilinos/' . $inq['id'] . '/estado-cuenta') ?>" class="btn btn-outline-primary btn-sm py-0 px-2" title="Consultar Estado de Cuenta">
                                Estado de Cuenta
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Formulario Nuevo Inquilino -->
<div class="modal fade" id="modalInquilino" tabindex="-1" aria-labelledby="modalInquilinoTitulo" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalInquilinoTitulo">Registrar Inquilino (Demo)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formInquilinoDemo" data-url="<?= url('/inquilinos/validar-demostracion') ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="alert alert-warning py-2 small mb-3">
                        <strong>Modo Demostración:</strong> Los datos serán validados pero no se guardarán en la base de datos.
                    </div>

                    <div class="mb-2">
                        <label for="txtNombreInquilino" class="form-label small fw-semibold">Nombre Completo</label>
                        <input type="text" class="form-control form-control-sm" id="txtNombreInquilino" name="nombre" placeholder="Ej. Juan Pérez García">
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label for="txtDocInquilino" class="form-label small fw-semibold">Documento (DPI / Pasaporte)</label>
                            <input type="text" class="form-control form-control-sm" id="txtDocInquilino" name="documento" placeholder="DPI 2000 00000 0101">
                        </div>
                        <div class="col-6">
                            <label for="txtTelInquilino" class="form-label small fw-semibold">Teléfono</label>
                            <input type="text" class="form-control form-control-sm" id="txtTelInquilino" name="telefono" placeholder="+502 5555-0000">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="txtEmailInquilino" class="form-label small fw-semibold">Correo Electrónico</label>
                        <input type="email" class="form-control form-control-sm" id="txtEmailInquilino" name="email" placeholder="ejemplo@correo.com">
                    </div>

                    <div class="mb-2">
                        <label for="txtEmergenciaInquilino" class="form-label small fw-semibold">Contacto de Emergencia</label>
                        <input type="text" class="form-control form-control-sm" id="txtEmergenciaInquilino" name="contacto_emergencia" placeholder="Nombre y teléfono de familiar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-brand-primary btn-sm">Validar Inquilino Demo</button>
                </div>
            </form>
        </div>
    </div>
</div>
