<?php

/**
 * --------------------------------------------------------------------------
 * La Estanza - Sistema de Alquileres de Apartamentos y Dorms
 * Punto de Entrada Principal (Front Controller)
 * --------------------------------------------------------------------------
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

// 1. Cargar Autoloader de Composer o Autoloader PSR-4 nativo de contingencia
$composerAutoload = BASE_PATH . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require_once $composerAutoload;
} else {
    // Autoloader PSR-4 para desarrollo sin dependencias de vendor
    spl_autoload_register(function (string $class) {
        $prefix = 'App\\';
        $baseDir = BASE_PATH . '/app/';
        $len = strlen($prefix);

        if (strncmp($prefix, $class, $len) !== 0) {
            return;
        }

        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    });

    // Cargar helpers globales
    $helpersFile = BASE_PATH . '/app/Support/Helpers.php';
    if (file_exists($helpersFile)) {
        require_once $helpersFile;
    }
}

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Support\Env;

// 2. Cargar variables de entorno desde .env si existe, o .env.example
$envFile = BASE_PATH . '/.env';
if (!file_exists($envFile)) {
    $envFile = BASE_PATH . '/.env.example';
}
Env::load($envFile);

// Configuración de errores según APP_DEBUG
$debug = (bool) Env::get('APP_DEBUG', true);
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(0);
}

// Configurar zona horaria
date_default_timezone_set((string) Env::get('APP_TIMEZONE', 'America/Guatemala'));

// 3. Inicializar motor de vistas
View::init(BASE_PATH . '/resources/views');

// 4. Instanciar enrutador y cargar rutas web
$router = new Router();
require_once BASE_PATH . '/routes/web.php';

// 5. Despachar la petición HTTP actual
$request = new Request();
$router->dispatch($request);
