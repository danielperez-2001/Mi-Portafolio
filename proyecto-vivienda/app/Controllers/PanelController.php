<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\ContratoModel;
use App\Models\DataDemo;
use App\Models\GastoModel;
use App\Models\PagoModel;
use App\Models\ReporteModel;

class PanelController extends Controller
{
    public function index(): void
    {
        $reporteModel = new ReporteModel();
        $resumen = $reporteModel->obtenerResumenGeneral(2026);

        $pagoModel = new PagoModel();
        $ultimosPagos = array_slice(array_reverse($pagoModel->todos()), 0, 5);

        $gastoModel = new GastoModel();
        $ultimosGastos = array_slice(array_reverse($gastoModel->todos()), 0, 5);

        $apartamentoModel = new ApartamentoModel();
        $apartamentos = $apartamentoModel->todos();

        $contratoModel = new ContratoModel();
        $contratos = $contratoModel->todos();
        $contratosPorVencer = DataDemo::getContratosPorVencer(30);

        $this->render('panel/index', [
            'titulo' => 'Panel de Control - La Estanza',
            'resumen' => $resumen,
            'ultimosPagos' => $ultimosPagos,
            'ultimosGastos' => $ultimosGastos,
            'apartamentos' => $apartamentos,
            'contratos' => $contratos,
            'contratosPorVencer' => $contratosPorVencer,
            'tipoOperacion' => 'PAN'
        ]);
    }
}
