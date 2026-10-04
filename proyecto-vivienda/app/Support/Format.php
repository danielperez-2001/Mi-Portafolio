<?php

namespace App\Support;

class Format
{
    private const MESES = [
        1 => 'Enero',
        2 => 'Febrero',
        3 => 'Marzo',
        4 => 'Abril',
        5 => 'Mayo',
        6 => 'Junio',
        7 => 'Julio',
        8 => 'Agosto',
        9 => 'Septiembre',
        10 => 'Octubre',
        11 => 'Noviembre',
        12 => 'Diciembre'
    ];

    public static function money(float|int|null $amount, string $currency = 'GTQ'): string
    {
        if ($amount === null) {
            return '-';
        }

        $formatted = number_format((float) $amount, 2, '.', ',');
        return ($currency === 'USD') ? ('$ ' . $formatted) : ('Q ' . $formatted);
    }

    public static function date(?string $date, string $format = 'd/m/Y'): string
    {
        if (empty($date)) {
            return '-';
        }

        $ts = strtotime($date);
        return ($ts !== false) ? date($format, $ts) : $date;
    }

    public static function monthName(int $monthNumber): string
    {
        return self::MESES[$monthNumber] ?? "Mes {$monthNumber}";
    }

    public static function statusBadge(string $status): string
    {
        $statusKey = strtolower(trim($status));
        return match ($statusKey) {
            'pagado' => '<span class="badge bg-success-subtle text-success border border-success-subtle">Pagado</span>',
            'parcial' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">Pago Parcial</span>',
            'pendiente' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Pendiente</span>',
            'vacio', 'desocupado' => '<span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Vacío</span>',
            'sin_informacion', 'sin_datos' => '<span class="badge bg-info-subtle text-info border border-info-subtle">Sin Información</span>',
            'activo', 'ocupado' => '<span class="badge bg-primary-subtle text-primary border border-primary-subtle">Ocupado</span>',
            'vigente' => '<span class="badge bg-success-subtle text-success border border-success-subtle">Vigente</span>',
            'vencido' => '<span class="badge bg-danger-subtle text-danger border border-danger-subtle">Vencido</span>',
            'mantenimiento' => '<span class="badge bg-warning-subtle text-warning border border-warning-subtle">Mantenimiento</span>',
            default => '<span class="badge bg-light text-dark border">' . htmlspecialchars(ucfirst($status)) . '</span>',
        };
    }
}
