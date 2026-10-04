<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $get;
    private array $post;
    private array $server;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
        $this->server = $_SERVER;
        $this->get = $_GET;
        $this->post = $_POST;
        $this->uri = $this->parseUri();
    }

    private function parseUri(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';

        // Remover query string (?foo=bar)
        if (($pos = strpos($requestUri, '?')) !== false) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        // Decodificar caracteres especiales como espacios %20
        $requestUri = rawurldecode($requestUri);

        // Remover prefijo de subcarpeta si se ejecuta en XAMPP o Apache con index.php
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        if (str_ends_with($scriptName, 'index.php')) {
            $baseDir = dirname($scriptName);
            $baseDir = str_replace('\\', '/', $baseDir);

            if ($baseDir !== '/' && $baseDir !== '.' && str_starts_with($requestUri, $baseDir)) {
                $requestUri = substr($requestUri, strlen($baseDir));
            }
        }

        // Si la URI contiene index.php al inicio
        if (str_starts_with($requestUri, '/index.php')) {
            $requestUri = substr($requestUri, strlen('/index.php'));
        }

        $requestUri = '/' . trim($requestUri, '/');
        return $requestUri === '' ? '/' : $requestUri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post);
    }

    public function post(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }

    public function get(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }

    public function isAjax(): bool
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') ||
               (isset($_SERVER['HTTP_ACCEPT']) &&
                str_contains($_SERVER['HTTP_ACCEPT'], 'application/json'));
    }
}
