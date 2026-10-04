<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\ContratoModel;
use App\Models\PagoModel;
use Throwable;

class PagoController extends Controller
{
    public function pagos(): void
    {
        $pagoModel = new PagoModel();
        $apartamentoModel = new ApartamentoModel();
        $contratoModel = new ContratoModel();

        $pagos = $pagoModel->todos();
        $apartamentos = $apartamentoModel->todos();
        $contratos = $contratoModel->todos();

        $this->render('pagos/index', [
            'titulo' => 'Registro y Control de Pagos',
            'pagos' => $pagos,
            'apartamentos' => $apartamentos,
            'contratos' => $contratos,
            'tipoOperacion' => 'PAG'
        ]);
    }

    public function buscarPago(): void
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
                    'message' => 'Debe ingresar la referencia bancaria del pago.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $pagoModel = new PagoModel();
            $datos = $pagoModel->buscarPorReferenciaDemostracion($referencia);

            if ($datos === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'PAGO_NO_ENCONTRADO',
                    'message' => "No se encontró ningún pago con la referencia bancaria '{$referencia}'.",
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_DEMO',
                'message' => 'Consulta de pago de demostración realizada con éxito.',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error buscarPago: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_BUSQUEDA_PAGO',
                'message' => 'No se pudo consultar el pago.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function validarPagoDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $apartamentoCodigo = isset($_POST['apartamento_codigo']) ? trim((string) $_POST['apartamento_codigo']) : '';
            $mesPeriodo = isset($_POST['mes_periodo']) ? (int) $_POST['mes_periodo'] : 0;
            $anio = isset($_POST['anio']) ? (int) $_POST['anio'] : (int) date('Y');
            $fechaPago = isset($_POST['fecha_pago']) ? trim((string) $_POST['fecha_pago']) : '';
            $monto = isset($_POST['monto']) ? (float) $_POST['monto'] : 0;
            $mora = isset($_POST['mora']) ? (float) $_POST['mora'] : 0.0;
            $moneda = isset($_POST['moneda']) ? strtoupper(trim((string) $_POST['moneda'])) : 'GTQ';
            $metodo = isset($_POST['metodo']) ? trim((string) $_POST['metodo']) : '';
            $referencia = isset($_POST['referencia']) ? trim((string) $_POST['referencia']) : '';
            $tipoConcepto = isset($_POST['tipo_concepto']) ? trim((string) $_POST['tipo_concepto']) : 'renta_mensual';
            $observaciones = isset($_POST['observaciones']) ? trim((string) $_POST['observaciones']) : '';

            $errores = [];

            if ($apartamentoCodigo === '') {
                $errores[] = 'Debe seleccionar el apartamento o dorm correspondiente.';
            }
            if ($mesPeriodo < 1 || $mesPeriodo > 12) {
                $errores[] = 'El mes del período cubierto debe estar entre 1 (Enero) y 12 (Diciembre).';
            }
            if ($anio < 2020 || $anio > 2035) {
                $errores[] = 'El año del período no es válido.';
            }
            if ($fechaPago === '') {
                $errores[] = 'La fecha de recepción del pago es obligatoria.';
            }
            if ($monto <= 0) {
                $errores[] = 'El monto del pago debe ser mayor a 0.00.';
            }
            if ($mora < 0) {
                $errores[] = 'El monto de mora no puede ser negativo.';
            }
            if (!in_array($moneda, ['GTQ', 'USD'], true)) {
                $errores[] = 'La moneda debe ser GTQ (Quetzales) o USD (Dólares).';
            }
            if ($metodo === '') {
                $errores[] = 'El método de pago es requerido (ej. Depósito Bancario, Transferencia ACH).';
            }
            if ($referencia === '') {
                $errores[] = 'La referencia bancaria o número de comprobante es obligatoria.';
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

            // Aclaración sobre el tipo de concepto: separar depósito de garantía de pago de renta
            $conceptoTexto = ($tipoConcepto === 'deposito_garantia')
                ? 'Depósito de Garantía (Fondo en custodia)'
                : 'Pago de Renta Mensual';

            echo json_encode([
                'status' => true,
                'codigo' => 'VALIDACION_DEMO_EXITOSA',
                'message' => "Validación de pago exitosa. Modo demostración: ningún registro fue guardado en la base de datos.",
                'datos' => [
                    'apartamento_codigo' => $apartamentoCodigo,
                    'periodo' => "Mes {$mesPeriodo} / {$anio}",
                    'fecha_pago' => $fechaPago,
                    'monto' => $monto,
                    'mora' => $mora,
                    'moneda' => $moneda,
                    'metodo' => $metodo,
                    'referencia' => $referencia,
                    'tipo_concepto' => $conceptoTexto,
                    'observaciones' => $observaciones,
                    'aviso' => 'Los datos no fueron guardados en base de datos (Etapa 1: Demostración).'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarPagoDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_VALIDACION_PAGO',
                'message' => 'No se pudo procesar la validación del pago.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
