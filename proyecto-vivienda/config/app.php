<?php

return [
    'name' => env('APP_NAME', 'La Estanza - Sistema de Alquileres'),
    'env' => env('APP_ENV', 'local'),
    'debug' => env('APP_DEBUG', true),
    'url' => env('APP_URL', 'http://localhost:8000'),
    'timezone' => env('APP_TIMEZONE', 'America/Guatemala'),
    'locale' => env('APP_LOCALE', 'es'),
    'demo_mode' => env('DEMO_MODE', true),

    'monedas' => [
        'GTQ' => [
            'nombre' => 'Quetzales de Guatemala',
            'simbolo' => 'Q',
            'codigo' => 'GTQ',
            'decimales' => 2
        ],
        'USD' => [
            'nombre' => 'Dólares Estadounidenses',
            'simbolo' => '$',
            'codigo' => 'USD',
            'decimales' => 2
        ]
    ],

    'estados_pago' => [
        'pagado' => 'Pagado Completo',
        'parcial' => 'Pago Parcial / Abono',
        'pendiente' => 'Pendiente / En Mora',
        'vacio' => 'Unidad Vacía / Sin Ocupante',
        'sin_informacion' => 'Sin Información Registrada'
    ],

    'metodos_pago' => [
        'Depósito Bancario',
        'Transferencia ACH / Inmediata',
        'Transferencia Internacional Wire',
        'Cheque',
        'Tarjeta de Débito/Crédito'
    ]
];
