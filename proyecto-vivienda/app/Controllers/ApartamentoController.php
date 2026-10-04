<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\ContratoModel;
use App\Models\DataDemo;
use App\Models\PagoModel;
use Throwable;

class ApartamentoController extends Controller
{
    public function apartamentos(): void
    {
        $apartamentoModel = new ApartamentoModel();
        $apartamentos = $apartamentoModel->todos();
        $propiedades = DataDemo::getPropiedades();

        $this->render('apartamentos/index', [
            'titulo' => 'Gestión de Apartamentos y Dorms',
            'apartamentos' => $apartamentos,
            'propiedades' => $propiedades,
            'tipoOperacion' => 'APT'
        ]);
    }

    public function detalle(string $id): void
    {
        $apartamentoModel = new ApartamentoModel();
        $apartamento = $apartamentoModel->buscarPorId($id);

        if (!$apartamento) {
            $apartamento = $apartamentoModel->buscarPorCodigoDemostracion($id);
        }

        if (!$apartamento) {
            http_response_code(404);
            $this->render('auth/404', [
                'titulo' => 'Apartamento no encontrado',
                'mensaje' => "No se encontró el apartamento o dorm con identificador '{$id}'."
            ], 'layouts/auth');
            return;
        }

        $contratoModel = new ContratoModel();
        $pagoModel = new PagoModel();

        $contratos = $contratoModel->buscarPorApartamento($apartamento['id']);
        $pagos = $pagoModel->filtrar(['apartamento_codigo' => $apartamento['codigo']]);

        $this->render('apartamentos/detalle', [
            'titulo' => "Detalle de {$apartamento['codigo']} - {$apartamento['tipo']}",
            'apartamento' => $apartamento,
            'contratos' => $contratos,
            'pagos' => $pagos,
            'tipoOperacion' => 'APT_DET'
        ]);
    }

    public function buscarApartamento(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $codigo = isset($_POST['codigo_apartamento']) && is_string($_POST['codigo_apartamento'])
                ? trim($_POST['codigo_apartamento']) : '';

            if ($codigo === '') {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'CODIGO_REQUERIDO',
                    'message' => 'Debe ingresar el código del apartamento.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $apartamentoModel = new ApartamentoModel();
            $datos = $apartamentoModel->buscarPorCodigoDemostracion($codigo);

            if ($datos === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'APARTAMENTO_NO_ENCONTRADO',
                    'message' => "No se encontró ningún apartamento con el código '{$codigo}'.",
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_DEMO',
                'message' => 'Consulta de datos de demostración realizada con éxito.',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error buscarApartamento: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_BUSQUEDA_APARTAMENTO',
                'message' => 'No se pudo consultar el apartamento debido a un error interno.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function validarApartamentoDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            // 1. Datos del apartamento
            $edificio = isset($_POST['edificio']) ? trim((string) $_POST['edificio']) : '';
            $numero = isset($_POST['numero']) ? trim((string) $_POST['numero']) : '';
            $rentaMensual = isset($_POST['renta_mensual']) ? (float) $_POST['renta_mensual'] : 0.0;
            $diaVencimiento = isset($_POST['dia_vencimiento']) ? (int) $_POST['dia_vencimiento'] : 5;
            $estado = isset($_POST['estado']) ? trim((string) $_POST['estado']) : 'Disponible';
            $fechaInicio = isset($_POST['fecha_inicio']) ? trim((string) $_POST['fecha_inicio']) : '';
            $fechaFin = isset($_POST['fecha_fin']) ? trim((string) $_POST['fecha_fin']) : '';

            $codigo = isset($_POST['codigo']) && trim((string)$_POST['codigo']) !== ''
                ? trim((string)$_POST['codigo'])
                : trim("{$edificio} {$numero}");

            // 2. Datos del Inquilino
            $inquilinoNombre = isset($_POST['inquilino_nombre']) ? trim((string) $_POST['inquilino_nombre']) : '';
            $inquilinoTelefono = isset($_POST['inquilino_telefono']) ? trim((string) $_POST['inquilino_telefono']) : '';
            $inquilinoCorreo = isset($_POST['inquilino_correo']) ? trim((string) $_POST['inquilino_correo']) : '';
            $inquilinoDpi = isset($_POST['inquilino_dpi']) ? trim((string) $_POST['inquilino_dpi']) : '';

            // 3. Datos del Fiador
            $fiadorNombre = isset($_POST['fiador_nombre']) ? trim((string) $_POST['fiador_nombre']) : '';
            $fiadorTelefono = isset($_POST['fiador_telefono']) ? trim((string) $_POST['fiador_telefono']) : '';
            $fiadorDpi = isset($_POST['fiador_dpi']) ? trim((string) $_POST['fiador_dpi']) : '';

            // 4. Depósito de garantía
            $depositoMonto = isset($_POST['deposito_monto']) ? (float) $_POST['deposito_monto'] : 0.0;
            $depositoDevuelto = isset($_POST['deposito_devuelto']) ? trim((string) $_POST['deposito_devuelto']) : 'No';

            $errores = [];

            if ($edificio === '') {
                $errores[] = 'El campo Edificio es obligatorio.';
            }
            if ($numero === '') {
                $errores[] = 'El campo Número de apartamento es obligatorio.';
            }
            if ($rentaMensual < 0) {
                $errores[] = 'El monto de renta mensual no puede ser negativo.';
            }
            if ($diaVencimiento < 1 || $diaVencimiento > 31) {
                $errores[] = 'El día de vencimiento debe estar entre 1 y 31.';
            }
            if ($inquilinoCorreo !== '' && !filter_var($inquilinoCorreo, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electrónico del inquilino no tiene un formato válido.';
            }
            if ($estado === 'Ocupado' && $inquilinoNombre === '') {
                $errores[] = 'Si el apartamento se marca como Ocupado, debe ingresar el nombre del inquilino.';
            }
            if ($depositoMonto < 0) {
                $errores[] = 'El monto del depósito de garantía no puede ser negativo.';
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
                'message' => 'Apartamento, inquilino, fiador y depósito validados exitosamente. Modo demostración: ningún dato fue guardado en la base de datos.',
                'datos' => [
                    'apartamento' => [
                        'edificio' => $edificio,
                        'numero' => $numero,
                        'codigo' => $codigo,
                        'renta_mensual' => $rentaMensual,
                        'dia_vencimiento' => $diaVencimiento,
                        'estado' => $estado,
                        'fecha_inicio' => $fechaInicio ?: 'No especificada',
                        'fecha_fin' => $fechaFin ?: 'No especificada'
                    ],
                    'inquilino' => [
                        'nombre' => $inquilinoNombre ?: '(Sin inquilino asignado)',
                        'telefono' => $inquilinoTelefono ?: 'No registrado',
                        'correo' => $inquilinoCorreo ?: 'No registrado',
                        'dpi' => $inquilinoDpi ?: 'No registrado'
                    ],
                    'fiador' => [
                        'nombre' => $fiadorNombre ?: '(Sin fiador)',
                        'telefono' => $fiadorTelefono ?: 'No registrado',
                        'dpi' => $fiadorDpi ?: 'No registrado'
                    ],
                    'garantia' => [
                        'monto' => $depositoMonto,
                        'devuelto' => $depositoDevuelto
                    ],
                    'aviso' => 'Los datos no fueron guardados en base de datos (Etapa 1: Demostración).'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarApartamentoDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_VALIDACION_APARTAMENTO',
                'message' => 'No se pudo procesar la validación del apartamento.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
