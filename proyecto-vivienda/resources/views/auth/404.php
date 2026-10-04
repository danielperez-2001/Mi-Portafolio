<div class="login-card text-center p-4">
    <div class="mb-3">
        <span class="badge bg-danger fs-4 px-3 py-2 rounded-pill">404</span>
    </div>
    <h2 class="h4 fw-bold text-dark mb-2"><?= e($titulo ?? 'Recurso no encontrado') ?></h2>
    <p class="text-muted small mb-4">
        <?= e($mensaje ?? 'La página que solicitas no existe o ha sido movida.') ?>
    </p>
    <a href="<?= url('/panel') ?>" class="btn btn-brand-primary w-100">
        Volver al Panel de Control
    </a>
</div>
