<input type="hidden" id="txtTipo" value="LOG">

<div class="login-card">
    <div class="login-card-header">
        <img src="<?= asset('assets/images/logo.svg') ?>" alt="La Estanza" height="42" class="mb-2">
        <p class="small text-white-50 mb-0">Gestión de Alquileres de Apartamentos y Dorms</p>
    </div>

    <div class="login-card-body">
        <div class="alert alert-info border-0 small py-2 mb-3">
            <strong>Pantalla visual de acceso:</strong> La autenticación definitiva con roles y base de datos centralizada se implementará en la Etapa 2.
        </div>

        <form id="formLoginDemo" data-url="<?= url('/login/validar-demostracion') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="txtUsuario" class="form-label small fw-semibold">Usuario / Correo</label>
                <input type="text" class="form-control" id="txtUsuario" name="usuario" value="admin.laestanza" placeholder="Ingrese su usuario">
            </div>

            <div class="mb-3">
                <label for="txtClave" class="form-label small fw-semibold">Contraseña</label>
                <input type="password" class="form-control" id="txtClave" name="clave" value="demo12345" placeholder="Ingrese su contraseña">
            </div>

            <div class="mb-3">
                <label for="selectNodoAcceso" class="form-label small fw-semibold">Nodo de Conexión</label>
                <select class="form-select small" id="selectNodoAcceso" name="nodo">
                    <option value="gt">Sede Central - Ciudad de Guatemala</option>
                    <option value="xela">Sede Operativa - Quetzaltenango (Remoto)</option>
                </select>
                <div class="form-text" style="font-size: 0.72rem;">Ambas sedes compartirán la misma base de datos central en la Etapa 2.</div>
            </div>

            <button type="submit" class="btn btn-brand-primary w-100 py-2 fw-semibold">
                Simular Inicio de Sesión
            </button>
        </form>

        <div class="text-center mt-3">
            <a href="<?= url('/panel') ?>" class="text-decoration-none small text-muted">
                &larr; Ingresar directamente al Panel de Control (Modo Demo)
            </a>
        </div>
    </div>
</div>
