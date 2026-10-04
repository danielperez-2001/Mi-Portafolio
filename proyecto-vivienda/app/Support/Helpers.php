<?php

use App\Support\Env;
use App\Support\Format;
use App\Support\Url;

if (!function_exists('e')) {
    function e(mixed $value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('url')) {
    function url(string $path = ''): string
    {
        return Url::to($path);
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return Url::asset($path);
    }
}

if (!function_exists('env')) {
    function env(string $key, mixed $default = null): mixed
    {
        return Env::get($key, $default);
    }
}

if (!function_exists('money')) {
    function money(float|int|null $amount, string $currency = 'GTQ'): string
    {
        return Format::money($amount, $currency);
    }
}

if (!function_exists('format_date')) {
    function format_date(?string $date, string $format = 'd/m/Y'): string
    {
        return Format::date($date, $format);
    }
}

if (!function_exists('status_badge')) {
    function status_badge(string $status): string
    {
        return Format::statusBadge($status);
    }
}

if (!function_exists('month_name')) {
    function month_name(int $monthNumber): string
    {
        return Format::monthName($monthNumber);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        return 'demo_token_' . md5('la_estanza_csrf');
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}
