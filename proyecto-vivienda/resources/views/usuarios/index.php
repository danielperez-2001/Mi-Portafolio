<input type="hidden" id="txtTipo" value="USU">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Usuarios y Accesos Multi-Ciudad</h1>
        <p class="text-muted small mb-0">Control de operadores concurrentes desde diferentes ubicaciones (Preparado para Etapa 2)</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-brand-primary btn-sm d-flex align-items-center gap-1" id="btnNuevoUsuarioDemo">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nuevo Operador (Demo)</span>
        </button>
    </div>
</div>

<div class="alert alert-info border-0 mb-3 small py-2">
    <strong>Arquitectura Multi-Ciudad:</strong> Dos personas desde diferentes ciudades (ej. Ciudad de Guatemala y Quetzaltenango) accederán simultáneamente a través de navegador a este mismo sistema MVC, compartiendo una única base de datos centralizada en la nube o servidor dedicado en la Etapa 2.
</div>

<div class="content-card">
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol en el Sistema</th>
                    <th>Ciudad / Sede de Conexión</th>
                    <th>Estado</th>
                    <th>Último Acceso</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= e($u['nombre']) ?></td>
                        <td><code><?= e($u['usuario']) ?></code></td>
                        <td><?= e($u['email']) ?></td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary"><?= e($u['rol']) ?></span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border"><?= e($u['ciudad']) ?></span>
                        </td>
                        <td><?= status_badge($u['estado']) ?></td>
                        <td class="small text-muted"><?= e($u['ultimo_acceso']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
