<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ControlAnualModel;
use App\Models\ReporteModel;

class ReporteController extends Controller
{
    public function reportes(): void
    {
        $reporteModel = new ReporteModel();
        $controlModel = new ControlAnualModel();

        $anio = isset($_GET['anio']) ? (int) $_GET['anio'] : 2026;
        $resumen = $reporteModel->obtenerResumenGeneral($anio);
        $matriz = $controlModel->obtenerMatrizAnual($anio);

        $this->render('reportes/index', [
            'titulo' => 'Informes y Resúmenes Anuales',
            'anio' => $anio,
            'resumen' => $resumen,
            'matriz' => $matriz,
            'tipoOperacion' => 'REP'
        ]);
    }
}
