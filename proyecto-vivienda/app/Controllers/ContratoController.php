<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\ContratoModel;
use App\Models\InquilinoModel;
use Throwable;

class ContratoController extends Controller
{
    public function contratos(): void
    {
        $contratoModel = new ContratoModel();
        $apartamentoModel = new ApartamentoModel();
        $inquilinoModel = new InquilinoModel();

        $contratos = $contratoModel->todos();
        $apartamentos = $apartamentoModel->todos();
        $inquilinos = $inquilinoModel->todos();

        $this->render('contratos/index', [
            'titulo' => 'Contratos de Alquiler',
            'contratos' => $contratos,
            'apartamentos' => $apartamentos,
            'inquilinos' => $inquilinos,
            'tipoOperacion' => 'CON'
        ]);
    }

    public function buscarContrato(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $codigo = isset($_POST['codigo_contrato']) && is_string($_POST['codigo_contrato'])
                ? trim($_POST['codigo_contrato']) : '';

            if ($codigo === '') {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'CODIGO_REQUERIDO',
                    'message' => 'Debe ingresar el código de contrato.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $contratoModel = new ContratoModel();
            $datos = $contratoModel->buscarPorCodigoDemostracion($codigo);

            if ($datos === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'CONTRATO_NO_ENCONTRADO',
                    'message' => "No se encontró ningún contrato con el código '{$codigo}'.",
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_DEMO',
                'message' => 'Consulta de contrato de demostración realizada con éxito.',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error buscarContrato: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_BUSQUEDA_CONTRATO',
                'message' => 'No se pudo consultar el contrato.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function validarContratoDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $apartamentoId = isset($_POST['apartamento_id']) ? (int) $_POST['apartamento_id'] : 0;
            $inquilinoId = isset($_POST['inquilino_id']) ? (int) $_POST['inquilino_id'] : 0;
            $fechaInicio = isset($_POST['fecha_inicio']) ? trim((string) $_POST['fecha_inicio']) : '';
            $fechaFin = isset($_POST['fecha_fin']) ? trim((string) $_POST['fecha_fin']) : '';
            $alquiler = isset($_POST['monto_alquiler']) ? (float) $_POST['monto_alquiler'] : 0;
            $moneda = isset($_POST['moneda']) ? strtoupper(trim((string) $_POST['moneda'])) : 'GTQ';
            $deposito = isset($_POST['deposito_garantia']) ? (float) $_POST['deposito_garantia'] : 0;
            $tipoRenovacion = isset($_POST['tipo_renovacion']) ? trim((string) $_POST['tipo_renovacion']) : 'Anual';

            $errores = [];

            if ($apartamentoId <= 0) {
                $errores[] = 'Debe seleccionar un apartamento o dorm.';
            }
            if ($inquilinoId <= 0) {
                $errores[] = 'Debe seleccionar un inquilino.';
            }
            if ($fechaInicio === '') {
                $errores[] = 'La fecha de inicio de contrato es requerida.';
            }
            if ($fechaFin === '') {
                $errores[] = 'La fecha de vencimiento de contrato es requerida.';
            }
            if ($fechaInicio !== '' && $fechaFin !== '' && strtotime($fechaFin) <= strtotime($fechaInicio)) {
                $errores[] = 'La fecha de fin debe ser posterior a la fecha de inicio.';
            }
            if ($alquiler <= 0) {
                $errores[] = 'El monto de renta pactada debe ser mayor a cero.';
            }
            if ($deposito <= 0) {
                $errores[] = 'El depósito de garantía en custodia debe ser mayor a cero.';
            }

            if (!empty($errores)) {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'DATOS_INVALIDOS',
                    'message' => implode(' ', $errores),
                    'datos' => ['errores' => $errores],
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'VALIDACION_DEMO_EXITOSA',
                'message' => 'Contrato validado correctamente en modo demostración. Ningún dato fue guardado en la base de datos.',
                'datos' => [
                    'apartamento_id' => $apartamentoId,
                    'inquilino_id' => $inquilinoId,
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'monto_alquiler' => $alquiler,
                    'moneda' => $moneda,
                    'deposito_garantia' => $deposito,
                    'tipo_renovacion' => $tipoRenovacion,
                    'aviso' => 'Los datos no fueron guardados en base de datos (Etapa 1: Demostración).'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarContratoDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_VALIDACION_CONTRATO',
                'message' => 'No se pudo procesar la validación del contrato.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
