<?php

/**
 * --------------------------------------------------------------------------
 * Rutas Web del Sistema de Alquileres - La Estanza
 * --------------------------------------------------------------------------
 * Estilo de definición requerido:
 * $router->get('/ruta', [new Controlador(), 'metodo']);
 * $router->post('/ruta', [new Controlador(), 'metodo']);
 *
 * GET: Abre las pantallas y vistas del sistema administrativo.
 * POST: Consultas y validaciones AJAX con datos ficticios (Modo Demostración).
 * --------------------------------------------------------------------------
 */

use App\Controllers\ApartamentoController;
use App\Controllers\AuthController;
use App\Controllers\ConfiguracionController;
use App\Controllers\ContratoController;
use App\Controllers\ControlAnualController;
use App\Controllers\GastoController;
use App\Controllers\InquilinoController;
use App\Controllers\PagoController;
use App\Controllers\PanelController;
use App\Controllers\ReporteController;
use App\Controllers\UsuarioController;
use App\Core\Response;

// 1. Redirección de la raíz al Panel
$router->get('/', function () {
    Response::redirect(url('/panel'));
});

// 2. Pantalla principal: Panel de Control (Dashboard)
$router->get('/panel', [new PanelController(), 'index']);

// 3. Módulo de Apartamentos y Dorms
$router->get('/apartamentos', [new ApartamentoController(), 'apartamentos']);
$router->get('/apartamentos/{id}', [new ApartamentoController(), 'detalle']);
$router->post('/apartamentos/buscar', [new ApartamentoController(), 'buscarApartamento']);
$router->post('/apartamentos/validar-demostracion', [new ApartamentoController(), 'validarApartamentoDemostracion']);

// 4. Módulo de Inquilinos y Estados de Cuenta
$router->get('/inquilinos', [new InquilinoController(), 'inquilinos']);
$router->get('/inquilinos/estado-cuenta', [new InquilinoController(), 'estadoCuentaGeneral']);
$router->get('/inquilinos/{id}/estado-cuenta', [new InquilinoController(), 'estadoCuenta']);
$router->post('/inquilinos/buscar', [new InquilinoController(), 'buscarInquilino']);
$router->post('/inquilinos/validar-demostracion', [new InquilinoController(), 'validarInquilinoDemostracion']);
$router->post('/inquilinos/estado-cuenta/consultar', [new InquilinoController(), 'consultarEstadoCuenta']);

// 5. Módulo de Contratos
$router->get('/contratos', [new ContratoController(), 'contratos']);
$router->post('/contratos/buscar', [new ContratoController(), 'buscarContrato']);
$router->post('/contratos/validar-demostracion', [new ContratoController(), 'validarContratoDemostracion']);

// 6. Módulo de Pagos
$router->get('/pagos', [new PagoController(), 'pagos']);
$router->post('/pagos/buscar', [new PagoController(), 'buscarPago']);
$router->post('/pagos/validar-demostracion', [new PagoController(), 'validarPagoDemostracion']);

// 7. Módulo de Gastos y Mantenimiento
$router->get('/gastos', [new GastoController(), 'gastos']);
$router->post('/gastos/buscar', [new GastoController(), 'buscarGasto']);
$router->post('/gastos/validar-demostracion', [new GastoController(), 'validarGastoDemostracion']);

// 8. Pantalla Principal: Control Anual (Matriz de Pagos y Ocupación)
$router->get('/control-anual', [new ControlAnualController(), 'controlAnual']);
$router->post('/control-anual/filtrar', [new ControlAnualController(), 'filtrar']);
$router->post('/control-anual/detalle-mes', [new ControlAnualController(), 'detalleMes']);

// 9. Reportes e Informes Anuales
$router->get('/reportes', [new ReporteController(), 'reportes']);

// 10. Gestión de Usuarios y Accesos Multi-Ciudad (Preparada Etapa 2)
$router->get('/usuarios', [new UsuarioController(), 'usuarios']);

// 11. Configuración del Sistema
$router->get('/configuracion', [new ConfiguracionController(), 'configuracion']);

// 12. Autenticación / Login (Pantalla Visual Preparada)
$router->get('/login', [new AuthController(), 'login']);
$router->post('/login/validar-demostracion', [new AuthController(), 'validarLoginDemostracion']);
