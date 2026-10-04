<?php

namespace App\Core;

use RuntimeException;

class View
{
    private static string $viewsPath = '';

    public static function init(string $baseViewsPath): void
    {
        self::$viewsPath = rtrim($baseViewsPath, '/\\');
    }

    public static function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        if (self::$viewsPath === '') {
            self::$viewsPath = dirname(__DIR__, 2) . '/resources/views';
        }

        $viewFile = self::resolvePath($view);
        if (!file_exists($viewFile)) {
            throw new RuntimeException("No se encontró la vista: {$view} en {$viewFile}");
        }

        // Extraer variables para el scope de la vista
        extract($data, EXTR_SKIP);

        // Capturar contenido de la vista
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Si se especificó un layout, renderizarlo inyectando $content
        if ($layout !== null) {
            $layoutFile = self::resolvePath($layout);
            if (!file_exists($layoutFile)) {
                throw new RuntimeException("No se encontró el layout: {$layout} en {$layoutFile}");
            }
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    private static function resolvePath(string $path): string
    {
        if (self::$viewsPath === '') {
            self::$viewsPath = dirname(__DIR__, 2) . '/resources/views';
        }
        $normalized = str_replace('.', '/', $path);
        return self::$viewsPath . '/' . ltrim($normalized, '/') . '.php';
    }
}
