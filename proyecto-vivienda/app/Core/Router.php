<?php

namespace App\Core;

use Throwable;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, callable|array $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    private function addRoute(string $method, string $path, callable|array $action): void
    {
        $normalizedPath = '/' . trim($path, '/');
        if ($normalizedPath === '//') {
            $normalizedPath = '/';
        }

        // Convertir parámetros dinámicos {param} a regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $normalizedPath);
        $pattern = '#^' . $pattern . '$#';

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $normalizedPath,
            'pattern' => $pattern,
            'action' => $action,
            'has_params' => str_contains($normalizedPath, '{')
        ];
    }

    public function dispatch(Request $request): void
    {
        $requestMethod = $request->getMethod();
        $requestUri = $request->getUri();

        $matchedRoute = null;
        $matchedParams = [];
        $methodNotAllowed = false;

        foreach ($this->routes as $route) {
            if (preg_match($route['pattern'], $requestUri, $matches)) {
                if ($route['method'] === $requestMethod) {
                    $matchedRoute = $route;
                    array_shift($matches); // Quitar match completo
                    $matchedParams = $matches;
                    break;
                } else {
                    $methodNotAllowed = true;
                }
            }
        }

        if ($matchedRoute !== null) {
            $this->executeAction($matchedRoute['action'], $matchedParams, $request);
            return;
        }

        if ($methodNotAllowed) {
            $this->handleMethodNotAllowed($request);
            return;
        }

        $this->handleNotFound($request);
    }

    private function executeAction(callable|array $action, array $params, Request $request): void
    {
        try {
            if (is_array($action)) {
                [$controller, $method] = $action;
                if (!method_exists($controller, $method)) {
                    throw new \BadMethodCallException("Método no encontrado: " . get_class($controller) . "::{$method}");
                }
                call_user_func_array([$controller, $method], $params);
                return;
            }

            if (is_callable($action)) {
                call_user_func_array($action, $params);
                return;
            }

            throw new \InvalidArgumentException("Acción de ruta no invocable");
        } catch (Throwable $e) {
            error_log("Error al ejecutar ruta: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine());
            $this->handleServerError($request, $e);
        }
    }

    private function handleNotFound(Request $request): void
    {
        http_response_code(404);

        if ($request->isAjax()) {
            Response::json([
                'status' => false,
                'codigo' => 'RUTA_NO_ENCONTRADA',
                'message' => 'El recurso solicitado no fue encontrado.',
                'datos' => null,
                'demo' => true
            ], 404);
            return;
        }

        // Renderizar vista o HTML limpio de 404
        View::render('auth/404', [
            'titulo' => '404 - Página no encontrada',
            'mensaje' => 'La pantalla que intentas consultar no existe o ha sido movida.'
        ], 'layouts/auth');
    }

    private function handleMethodNotAllowed(Request $request): void
    {
        http_response_code(405);

        if ($request->isAjax()) {
            Response::json([
                'status' => false,
                'codigo' => 'METODO_NO_PERMITIDO',
                'message' => 'El método HTTP utilizado no está permitido para esta ruta.',
                'datos' => null,
                'demo' => true
            ], 405);
            return;
        }

        View::render('auth/404', [
            'titulo' => '405 - Método no permitido',
            'mensaje' => 'El método HTTP solicitado no está permitido para este recurso.'
        ], 'layouts/auth');
    }

    private function handleServerError(Request $request, Throwable $e): void
    {
        http_response_code(500);

        if ($request->isAjax()) {
            Response::json([
                'status' => false,
                'codigo' => 'ERROR_INTERNO_SERVIDOR',
                'message' => 'Ocurrió un error inesperado al procesar la solicitud.',
                'datos' => null,
                'demo' => true
            ], 500);
            return;
        }

        View::render('auth/404', [
            'titulo' => '500 - Error Interno',
            'mensaje' => 'Ocurrió un error en el servidor. Por favor verifique los registros de la aplicación.'
        ], 'layouts/auth');
    }
}
