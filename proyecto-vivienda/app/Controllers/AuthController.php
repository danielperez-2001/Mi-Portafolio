<?php

namespace App\Controllers;

use App\Core\Controller;
use Throwable;

class AuthController extends Controller
{
    public function login(): void
    {
        $this->render('auth/login', [
            'titulo' => 'Iniciar Sesión - La Estanza (Demostración)',
            'tipoOperacion' => 'LOG'
        ], 'layouts/auth');
    }

    public function validarLoginDemostracion(): void
    {
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }

        try {
            $usuario = isset($_POST['usuario']) ? trim((string) $_POST['usuario']) : '';
            $clave = isset($_POST['clave']) ? (string) $_POST['clave'] : '';

            $errores = [];

            if ($usuario === '') {
                $errores[] = 'Debe ingresar el nombre de usuario.';
            }
            if ($clave === '') {
                $errores[] = 'Debe ingresar la contraseña.';
            }

            if (!empty($errores)) {
                http_response_code(422);
                echo json_encode([
                    'status' => false,
                    'codigo' => 'CREDENCIALES_REQUERIDAS',
                    'message' => implode(' ', $errores),
                    'datos' => ['errores' => $errores],
                    'demo' => true
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            // Simulación visual sin autenticación real de acuerdo con los requerimientos de la Etapa 1
            echo json_encode([
                'status' => true,
                'codigo' => 'LOGIN_DEMO_EXITOSO',
                'message' => 'Simulación de inicio de sesión completada. La autenticación real con base de datos central y sesiones se implementará en la Etapa 2.',
                'datos' => [
                    'usuario' => $usuario,
                    'rol' => 'Administrador (Demostración)',
                    'aviso' => 'Pantalla visual preparada. No existe sesión activa persistente.'
                ],
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Throwable $e) {
            error_log('Error validarLoginDemostracion: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => false,
                'codigo' => 'ERROR_LOGIN_DEMO',
                'message' => 'No se pudo procesar la solicitud de demostración.',
                'datos' => null,
                'demo' => true
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}
