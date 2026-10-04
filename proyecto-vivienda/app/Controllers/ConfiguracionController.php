<?php

namespace App\Controllers;

use App\Core\Controller;

class ConfiguracionController extends Controller
{
    public function configuracion(): void
    {
        $parametros = [
            'moneda_principal' => 'GTQ (Quetzales)',
            'moneda_secundaria' => 'USD (Dólares Estadounidenses)',
            'dias_gracia_pago' => 5,
            'dias_anticipacion_aviso_vencimiento' => 60,
            'ano_operativo_predeterminado' => 2026,
            'empresa_nombre' => 'La Estanza - Alquileres & Dorms',
            'empresa_direccion' => 'Zona 16, Ciudad de Guatemala'
        ];

        $this->render('configuracion/index', [
            'titulo' => 'Configuración del Sistema',
            'parametros' => $parametros,
            'tipoOperacion' => 'CFG'
        ]);
    }
}
