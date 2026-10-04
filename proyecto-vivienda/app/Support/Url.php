<?php

namespace App\Support;

class Url
{
    private static ?string $base = null;

    public static function setBase(string $url): void
    {
        self::$base = rtrim($url, '/');
    }

    public static function base(): string
    {
        if (self::$base !== null) {
            return self::$base;
        }

        $configured = Env::get('APP_URL');
        if (!empty($configured)) {
            self::$base = rtrim($configured, '/');
            return self::$base;
        }

        // Detección automática en base al servidor actual
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
            (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
        $protocol = $https ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

        // Detectar si está en una subcarpeta (ej. XAMPP htdocs/proyecto de vivienda/public)
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseFolder = dirname($scriptName);
        $baseFolder = str_replace('\\', '/', $baseFolder);
        if ($baseFolder === '/' || $baseFolder === '.') {
            $baseFolder = '';
        }

        self::$base = rtrim($protocol . $host . $baseFolder, '/');
        return self::$base;
    }

    public static function to(string $path = ''): string
    {
        $base = self::base();
        $path = '/' . ltrim($path, '/');
        return ($path === '/') ? ($base ?: '/') : ($base . $path);
    }

    public static function asset(string $path): string
    {
        $base = self::base();
        return $base . '/' . ltrim($path, '/');
    }

    public static function current(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return self::base() . $uri;
    }
}
