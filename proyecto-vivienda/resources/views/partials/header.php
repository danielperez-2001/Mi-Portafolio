<header class="app-header">
    <div class="d-flex align-items-center gap-3">
        <button type="button" class="btn btn-outline-secondary btn-sm d-lg-none" id="btnToggleSidebar" aria-label="Abrir menú">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 header-breadcrumbs">
                <li class="breadcrumb-item"><a href="<?= url('/panel') ?>">La Estanza</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= e($titulo ?? 'Sistema') ?></li>
            </ol>
        </nav>
    </div>

    <div class="header-actions">
        <!-- Indicador de ubicación de acceso multi-ciudad -->
        <div class="d-none d-md-flex align-items-center gap-2 bg-light border px-2 py-1 rounded small">
            <span class="badge bg-success rounded-circle p-1"></span>
            <span class="text-muted">Nodo de Conexión:</span>
            <strong class="text-dark">Ciudad de Guatemala</strong>
        </div>

        <div class="d-flex align-items-center gap-2">
            <div class="text-end d-none d-sm-block">
                <div class="fw-semibold small text-dark">Operador Demo</div>
                <div class="text-muted" style="font-size: 0.72rem;">Administración Central</div>
            </div>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.85rem;">
                LE
            </div>
        </div>
    </div>
</header>
