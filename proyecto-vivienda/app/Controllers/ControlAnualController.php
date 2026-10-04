<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\ControlAnualModel;
use App\Models\DataDemo;
use App\Models\PagoModel;
use Throwable;

class ControlAnualController extends Controller
{
    public function controlAnual(): void
    {
        $controlModel = new ControlAnualModel();
        $apartamentoModel = new ApartamentoModel();
        $propiedades = DataDemo::getPropiedades();
        $apartamentos = $apartamentoModel->todos();
        $inquilinos = DataDemo::getInquilinos();

        $anio = isset($_GET['anio']) ? (int) $_GET['anio'] : 2026;
        $matriz = $controlModel->obtenerMatrizAnual($anio);

        $this->render('control-anual/index', [
            'titulo' => 'Control Anual de Pagos y Ocupación',
            'anio' => $anio,
            'matriz' => $matriz,
            'propiedades' => $propiedades,
            'apartamentos' => $apartamentos,
            'inquilinos' => $inquilinos,
            'tipoOperacion' => 'CA'
        ]);
    }

    public function filtrar(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $anio = isset($_POST['anio']) ? (int) $_POST['anio'] : 2026;
            $filtros = [];

            if (!empty($_POST['propiedad_id'])) {
                $filtros['propiedad_id'] = (int) $_POST['propiedad_id'];
            }
            if (!empty($_POST['apartamento_codigo'])) {
                $filtros['busqueda'] = trim((string) $_POST['apartamento_codigo']);
            }
            if (!empty($_POST['moneda']) && in_array(strtoupper($_POST['moneda']), ['GTQ', 'USD'], true)) {
                $filtros['moneda'] = strtoupper(trim($_POST['moneda']));
            }
            if (!empty($_POST['inquilino'])) {
                $filtros['busqueda'] = trim((string) $_POST['inquilino']);
            }

            $controlModel = new ControlAnualModel();
            $datos = $controlModel->obtenerMatrizAnual($anio, $filtros);

            echo json_encode([
                'status' => true,
                'codigo' => 'MATRIZ_FILTRADA_EXITO',
                'message' => 'Matriz de control anual actualizada con éxito.',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error filtrar ControlAnual: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_FILTRADO_MATRIZ',
                'message' => 'No se pudo actualizar el control anual.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function detalleMes(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $apartamentoCodigo = isset($_POST['apartamento_codigo']) ? trim((string) $_POST['apartamento_codigo']) : '';
            $mes = isset($_POST['mes']) ? (int) $_POST['mes'] : 0;
            $anio = isset($_POST['anio']) ? (int) $_POST['anio'] : 2026;

            if ($apartamentoCodigo === '' || $mes < 1 || $mes > 12) {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'PARAMETROS_INVALIDOS',
                    'message' => 'Se requiere código de apartamento y número de mes válido.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $pagoModel = new PagoModel();
            $pagos = $pagoModel->filtrar([
                'apartamento_codigo' => $apartamentoCodigo,
                'mes' => $mes,
                'anio' => $anio
            ]);

            $apartamentoModel = new ApartamentoModel();
            $apto = $apartamentoModel->buscarPorCodigoDemostracion($apartamentoCodigo);

            echo json_encode([
                'status' => true,
                'codigo' => 'DETALLE_MES_DEMO',
                'message' => 'Detalle del mes consultado.',
                'datos' => [
                    'apartamento' => $apto,
                    'mes' => $mes,
                    'anio' => $anio,
                    'pagos' => $pagos,
                    'total_pagos' => count($pagos)
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error detalleMes: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_DETALLE_MES',
                'message' => 'No se pudo consultar el detalle del mes.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
