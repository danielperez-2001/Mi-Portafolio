<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\DataDemo;
use App\Models\GastoModel;
use Throwable;

class GastoController extends Controller
{
    public function gastos(): void
    {
        $gastoModel = new GastoModel();
        $apartamentoModel = new ApartamentoModel();

        $gastos = $gastoModel->todos();
        $apartamentos = $apartamentoModel->todos();
        $propiedades = DataDemo::getPropiedades();

        $this->render('gastos/index', [
            'titulo' => 'Control de Gastos y Mantenimiento',
            'gastos' => $gastos,
            'apartamentos' => $apartamentos,
            'propiedades' => $propiedades,
            'tipoOperacion' => 'GAS'
        ]);
    }

    public function buscarGasto(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $referencia = isset($_POST['referencia']) && is_string($_POST['referencia'])
                ? trim($_POST['referencia']) : '';

            if ($referencia === '') {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'REFERENCIA_REQUERIDA',
                    'message' => 'Debe ingresar la referencia o comprobante del gasto.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $gastoModel = new GastoModel();
            $datos = $gastoModel->buscarPorReferenciaDemostracion($referencia);

            if ($datos === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'GASTO_NO_ENCONTRADO',
                    'message' => "No se encontró ningún gasto con la referencia '{$referencia}'.",
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_DEMO',
                'message' => 'Gasto de demostración consultado exitosamente.',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error buscarGasto: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_BUSQUEDA_GASTO',
                'message' => 'No se pudo consultar el gasto.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function validarGastoDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $fecha = isset($_POST['fecha']) ? trim((string) $_POST['fecha']) : '';
            $categoria = isset($_POST['categoria']) ? trim((string) $_POST['categoria']) : '';
            $propiedad = isset($_POST['propiedad_ubicacion']) ? trim((string) $_POST['propiedad_ubicacion']) : '';
            $apartamentoCodigo = isset($_POST['apartamento_codigo']) ? trim((string) $_POST['apartamento_codigo']) : 'General';
            $moneda = isset($_POST['moneda']) ? strtoupper(trim((string) $_POST['moneda'])) : 'GTQ';
            $monto = isset($_POST['monto']) ? (float) $_POST['monto'] : 0;
            $descripcion = isset($_POST['descripcion']) ? trim((string) $_POST['descripcion']) : '';
            $referencia = isset($_POST['referencia']) ? trim((string) $_POST['referencia']) : '';

            $errores = [];

            if ($fecha === '') {
                $errores[] = 'La fecha del gasto es obligatoria.';
            }
            if ($categoria === '') {
                $errores[] = 'Debe seleccionar una categoría de gasto válida.';
            }
            if ($propiedad === '') {
                $errores[] = 'Debe indicar la propiedad o módulo afectado.';
            }
            if (!in_array($moneda, ['GTQ', 'USD'], true)) {
                $errores[] = 'La moneda debe ser GTQ (Quetzales) o USD (Dólares).';
            }
            if ($monto <= 0) {
                $errores[] = 'El importe del gasto debe ser mayor a 0.00.';
            }
            if ($descripcion === '') {
                $errores[] = 'La descripción del gasto es obligatoria.';
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
                'message' => 'Gasto validado correctamente. Modo demostración: ningún gasto fue guardado en la base de datos.',
                'datos' => [
                    'fecha' => $fecha,
                    'categoria' => $categoria,
                    'propiedad_ubicacion' => $propiedad,
                    'apartamento_codigo' => $apartamentoCodigo,
                    'moneda' => $moneda,
                    'monto' => $monto,
                    'descripcion' => $descripcion,
                    'referencia' => $referencia,
                    'aviso' => 'Los datos no fueron guardados en base de datos (Etapa 1: Demostración).'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarGastoDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_VALIDACION_GASTO',
                'message' => 'No se pudo procesar la validación del gasto.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
