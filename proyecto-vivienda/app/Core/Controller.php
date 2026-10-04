<?php

namespace App\Core;

abstract class Controller
{
    protected function render(string $view, array $data = [], ?string $layout = 'layouts/main'): void
    {
        View::render($view, $data, $layout);
    }

    protected function json(array $data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    protected function jsonSuccess(string $codigo, string $message, mixed $datos = null, ?int $total = null, int $statusCode = 200): void
    {
        $payload = [
            'status' => true,
            'codigo' => $codigo,
            'message' => $message,
            'datos' => $datos,
            'demo' => true
        ];

        if ($total !== null) {
            $payload['total'] = $total;
        }

        Response::json($payload, $statusCode);
    }

    protected function jsonError(string $codigo, string $message, mixed $datos = null, int $statusCode = 422): void
    {
        Response::json([
            'status' => false,
            'codigo' => $codigo,
            'message' => $message,
            'datos' => $datos,
            'demo' => true
        ], $statusCode);
    }

    protected function redirect(string $path, int $statusCode = 302): void
    {
        $url = str_starts_with($path, 'http://') || str_starts_with($path, 'https://')
            ? $path
            : url($path);

        Response::redirect($url, $statusCode);
    }
}
