<?php

/**
 * Enrutador para el servidor de desarrollo integrado de PHP.
 * Uso:
 *   php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Bloquear acceso a archivos sensibles o privados fuera de public
if (
    $uri === '/.env' || str_starts_with($uri, '/.env.') ||
    str_starts_with($uri, '/config/') || $uri === '/config' ||
    str_starts_with($uri, '/app/') || $uri === '/app' ||
    str_starts_with($uri, '/storage/') || $uri === '/storage' ||
    str_starts_with($uri, '/resources/') || $uri === '/resources' ||
    str_starts_with($uri, '/vendor/') || $uri === '/vendor'
) {
    http_response_code(403);
    echo "Acceso denegado.";
    exit;
}

// Ruta en el sistema de archivos relativa a public/
$publicFile = __DIR__ . '/public' . $uri;

// Si el archivo estático existe dentro de public/, servirlo con Content-Type adecuado
if ($uri !== '/' && file_exists($publicFile) && !is_dir($publicFile)) {
    $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
    $mimes = [
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'json' => 'application/json; charset=utf-8'
    ];

    if (isset($mimes[$ext])) {
        header('Content-Type: ' . $mimes[$ext]);
    }
    readfile($publicFile);
    exit;
}

// Para todo lo demás, despachar mediante public/index.php
require_once __DIR__ . '/public/index.php';
