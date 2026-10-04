<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Acceso') ?> - La Estanza</title>
    <meta name="description" content="Acceso al sistema administrativo de apartamentos y dorms La Estanza.">
    <link rel="icon" type="image/svg+xml" href="<?= asset('assets/images/logo.svg') ?>">

    <!-- Tipografía Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- CSS Compilado -->
    <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
</head>
<body>
    <div id="app-config" data-base-url="<?= e(url()) ?>"></div>

    <!-- Banner Demo -->
    <?php require __DIR__ . '/../partials/aviso-demo.php'; ?>

    <div class="login-page-wrapper">
        <?= $content ?>
    </div>

    <!-- Bundle JS -->
    <script src="<?= asset('assets/js/app.js') ?>"></script>
</body>
</html>
