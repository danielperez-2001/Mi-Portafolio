<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\ApartamentoModel;
use App\Models\InquilinoModel;
use Throwable;

class InquilinoController extends Controller
{
    public function inquilinos(): void
    {
        $inquilinoModel = new InquilinoModel();
        $apartamentoModel = new ApartamentoModel();

        $inquilinos = $inquilinoModel->todos();
        $apartamentos = $apartamentoModel->todos();

        $this->render('inquilinos/index', [
            'titulo' => 'Directorio de Inquilinos',
            'inquilinos' => $inquilinos,
            'apartamentos' => $apartamentos,
            'tipoOperacion' => 'INQ'
        ]);
    }

    public function buscarInquilino(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $termino = isset($_POST['termino']) && is_string($_POST['termino'])
                ? trim($_POST['termino']) : '';

            if ($termino === '') {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'TERMINO_REQUERIDO',
                    'message' => 'Debe ingresar un término de búsqueda para consultar inquilinos.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $inquilinoModel = new InquilinoModel();
            $resultados = $inquilinoModel->buscarPorTerminoDemostracion($termino);

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_DEMO',
                'message' => 'Búsqueda de inquilinos de demostración realizada.',
                'datos' => $resultados,
                'total' => count($resultados),
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error buscarInquilino: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_BUSQUEDA_INQUILINO',
                'message' => 'Ocurrió un error al buscar inquilinos.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function validarInquilinoDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $nombre = isset($_POST['nombre']) ? trim((string) $_POST['nombre']) : '';
            $documento = isset($_POST['documento']) ? trim((string) $_POST['documento']) : '';
            $telefono = isset($_POST['telefono']) ? trim((string) $_POST['telefono']) : '';
            $email = isset($_POST['email']) ? trim((string) $_POST['email']) : '';
            $contactoEmergencia = isset($_POST['contacto_emergencia']) ? trim((string) $_POST['contacto_emergencia']) : '';

            $errores = [];

            if ($nombre === '') {
                $errores[] = 'El nombre completo del inquilino es obligatorio.';
            }
            if ($documento === '') {
                $errores[] = 'El documento de identificación (DPI o Pasaporte) es obligatorio.';
            }
            if ($telefono === '') {
                $errores[] = 'El teléfono de contacto es obligatorio.';
            }
            if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errores[] = 'El correo electrónico no tiene un formato válido.';
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
                'message' => 'Inquilino validado exitosamente en modo demostración. No se guardaron cambios en la base de datos.',
                'datos' => [
                    'nombre' => $nombre,
                    'documento' => $documento,
                    'telefono' => $telefono,
                    'email' => $email,
                    'contacto_emergencia' => $contactoEmergencia,
                    'aviso' => 'Los datos no fueron guardados en base de datos (Etapa 1: Demostración).'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarInquilinoDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_VALIDACION_INQUILINO',
                'message' => 'No se pudo procesar la validación del inquilino.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function estadoCuentaGeneral(): void
    {
        $inquilinoModel = new InquilinoModel();
        $inquilinos = $inquilinoModel->todos();

        $this->render('inquilinos/estado-cuenta', [
            'titulo' => 'Estado de cuenta por inquilino',
            'inquilinos' => $inquilinos,
            'inquilinoSeleccionado' => null,
            'estadoCuenta' => null,
            'tipoOperacion' => 'INQ_EDC'
        ]);
    }

    public function estadoCuenta(string $id): void
    {
        $inquilinoModel = new InquilinoModel();
        $inquilinos = $inquilinoModel->todos();
        $inquilino = $inquilinoModel->buscarPorId($id);

        if (!$inquilino) {
            http_response_code(404);
            $this->render('auth/404', [
                'titulo' => 'Inquilino no encontrado',
                'mensaje' => "No se encontró el inquilino solicitado con ID {$id}."
            ], 'layouts/auth');
            return;
        }

        $estadoCuenta = $inquilinoModel->obtenerEstadoCuenta($id);

        $this->render('inquilinos/estado-cuenta', [
            'titulo' => 'Estado de cuenta por inquilino',
            'inquilinos' => $inquilinos,
            'inquilinoSeleccionado' => $inquilino,
            'estadoCuenta' => $estadoCuenta,
            'tipoOperacion' => 'INQ_EDC'
        ]);
    }

    public function consultarEstadoCuenta(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $id = isset($_POST['inquilino_id']) ? (int) $_POST['inquilino_id'] : 0;

            if ($id <= 0) {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'INQUILINO_REQUERIDO',
                    'message' => 'Debe seleccionar un inquilino para consultar su estado de cuenta.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $inquilinoModel = new InquilinoModel();
            $datos = $inquilinoModel->obtenerEstadoCuenta($id);

            if ($datos === null) {
                http_response_code(404);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'INQUILINO_NO_ENCONTRADO',
                    'message' => 'No se encontró el inquilino solicitado en el sistema de demostración.',
                    'datos' => null,
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            echo json_encode([
                'status' => true,
                'codigo' => 'CONSULTA_ESTADO_CUENTA',
                'message' => 'Estado de cuenta y balance de movimientos consultados exitosamente (Demostración).',
                'datos' => $datos,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error consultarEstadoCuenta: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_ESTADO_CUENTA',
                'message' => 'Ocurrió un error al procesar la consulta del estado de cuenta.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
