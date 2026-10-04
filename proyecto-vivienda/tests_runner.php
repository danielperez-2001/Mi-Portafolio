<?php

/**
 * Runner de Pruebas Automatizadas de Rutas, Controladores y Respuestas JSON
 * La Estanza - Sistema de Alquileres (Etapa 1)
 */

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Request;
use App\Core\Router;
use App\Core\View;
use App\Support\Env;

// Cargar entorno
Env::load(__DIR__ . '/.env.example');
View::init(__DIR__ . '/resources/views');

$pruebasPasadas = 0;
$pruebasFalladas = 0;

function probar(string $nombre, callable $test): void {
    global $pruebasPasadas, $pruebasFalladas;
    try {
        $resultado = $test();
        if ($resultado === true) {
            echo " [PASS] {$nombre}\n";
            $pruebasPasadas++;
        } else {
            echo " [FAIL] {$nombre} - Resultado falso\n";
            $pruebasFalladas++;
        }
    } catch (Throwable $e) {
        echo " [FAIL] {$nombre} - Excepción: " . $e->getMessage() . " en " . $e->getFile() . ":" . $e->getLine() . "\n";
        $pruebasFalladas++;
    }
}

function simularPeticion(string $method, string $uri, array $post = [], bool $isAjax = false): array {
    $_SERVER['REQUEST_METHOD'] = strtoupper($method);
    $_SERVER['REQUEST_URI'] = $uri;
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['HTTP_HOST'] = 'localhost:8000';
    if ($isAjax) {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
    } else {
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    }
    $_GET = [];
    $_POST = $post;

    $router = new Router();
    require __DIR__ . '/routes/web.php';

    ob_start();
    $router->dispatch(new Request());
    $output = ob_get_clean();
    $status = http_response_code() ?: 200;

    return [
        'status' => $status,
        'output' => $output
    ];
}

echo "=== INICIANDO SUITE DE PRUEBAS AUTOMATIZADAS LA ESTANZA ===\n\n";

// 1. Pruebas de Rutas GET y Vistas
probar("GET /panel responde 200 y renderiza vista del Dashboard con txtTipo=PAN", function () {
    $res = simularPeticion('GET', '/panel');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'Panel de Control') &&
           str_contains($res['output'], 'id="txtTipo" value="PAN"');
});

probar("GET /control-anual responde 200 y renderiza Matriz con txtTipo=CA", function () {
    $res = simularPeticion('GET', '/control-anual');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'Control Anual de Pagos') &&
           str_contains($res['output'], 'id="txtTipo" value="CA"') &&
           str_contains($res['output'], 'Enero') &&
           str_contains($res['output'], 'Diciembre');
});

probar("GET /apartamentos responde 200 con listado y txtTipo=APT", function () {
    $res = simularPeticion('GET', '/apartamentos');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'id="txtTipo" value="APT"') &&
           str_contains($res['output'], 'DORM-101');
});

probar("GET /apartamentos/DORM-101 responde 200 con detalle y txtTipo=APT_DET", function () {
    $res = simularPeticion('GET', '/apartamentos/DORM-101');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'id="txtTipo" value="APT_DET"') &&
           str_contains($res['output'], 'Ficha Técnica');
});

probar("GET /inquilinos responde 200 con txtTipo=INQ", function () {
    $res = simularPeticion('GET', '/inquilinos');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="INQ"');
});

probar("GET /contratos responde 200 con txtTipo=CON", function () {
    $res = simularPeticion('GET', '/contratos');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="CON"');
});

probar("GET /pagos responde 200 con txtTipo=PAG", function () {
    $res = simularPeticion('GET', '/pagos');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="PAG"');
});

probar("GET /gastos responde 200 con txtTipo=GAS", function () {
    $res = simularPeticion('GET', '/gastos');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="GAS"');
});

probar("GET /reportes responde 200 con txtTipo=REP", function () {
    $res = simularPeticion('GET', '/reportes');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="REP"');
});

probar("GET /usuarios responde 200 con txtTipo=USU (Arquitectura multi-ciudad)", function () {
    $res = simularPeticion('GET', '/usuarios');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="USU"');
});

probar("GET /configuracion responde 200 con txtTipo=CFG", function () {
    $res = simularPeticion('GET', '/configuracion');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="CFG"');
});

probar("GET /login responde 200 con layout de auth y txtTipo=LOG", function () {
    $res = simularPeticion('GET', '/login');
    return $res['status'] === 200 && str_contains($res['output'], 'id="txtTipo" value="LOG"');
});

// 2. Errores 404 y 405
probar("Ruta inexistente GET /no-existe responde HTTP 404", function () {
    $res = simularPeticion('GET', '/no-existe');
    return $res['status'] === 404;
});

probar("Ruta con método incorrecto POST /panel responde HTTP 405", function () {
    $res = simularPeticion('POST', '/panel');
    return $res['status'] === 405;
});

// 3. Peticiones POST AJAX de Demostración
probar("POST /apartamentos/buscar encuentra DORM-101 y responde JSON con status=true", function () {
    $res = simularPeticion('POST', '/apartamentos/buscar', ['codigo_apartamento' => 'DORM-101'], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'CONSULTA_DEMO' &&
           $json['datos']['codigo'] === 'DORM-101' &&
           $json['demo'] === true;
});

probar("POST /apartamentos/buscar con código no existente responde HTTP 404 y codigo APARTAMENTO_NO_ENCONTRADO", function () {
    $res = simularPeticion('POST', '/apartamentos/buscar', ['codigo_apartamento' => 'INEXISTENTE-999'], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 404 &&
           $json['status'] === false &&
           $json['codigo'] === 'APARTAMENTO_NO_ENCONTRADO';
});

probar("POST /apartamentos/validar-demostracion valida campos y responde demo=true", function () {
    $res = simularPeticion('POST', '/apartamentos/validar-demostracion', [
        'codigo' => 'DORM-105',
        'propiedad_id' => '1',
        'tipo' => 'Dorm Individual',
        'nivel' => 'Nivel 1',
        'moneda' => 'GTQ',
        'alquiler' => '2200.00',
        'deposito' => '2200.00'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'VALIDACION_DEMO_EXITOSA' &&
           $json['demo'] === true;
});

probar("POST /pagos/validar-demostracion valida boleta y distingue depósito de garantía", function () {
    $res = simularPeticion('POST', '/pagos/validar-demostracion', [
        'apartamento_codigo' => 'DORM-101',
        'mes_periodo' => '5',
        'anio' => '2026',
        'fecha_pago' => '2026-05-02',
        'monto' => '2200.00',
        'mora' => '0.00',
        'moneda' => 'GTQ',
        'metodo' => 'Depósito Bancario',
        'referencia' => 'DEP-TEST-9991',
        'tipo_concepto' => 'renta_mensual'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'VALIDACION_DEMO_EXITOSA' &&
           $json['datos']['referencia'] === 'DEP-TEST-9991';
});

probar("POST /control-anual/filtrar devuelve matriz recalculada", function () {
    $res = simularPeticion('POST', '/control-anual/filtrar', [
        'anio' => '2026',
        'moneda' => 'GTQ'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'MATRIZ_FILTRADA_EXITO' &&
           isset($json['datos']['filas']) &&
           isset($json['datos']['resumen']);
});

probar("POST /control-anual/detalle-mes obtiene detalle para modal", function () {
    $res = simularPeticion('POST', '/control-anual/detalle-mes', [
        'apartamento_codigo' => 'DORM-101',
        'mes' => '1',
        'anio' => '2026'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'DETALLE_MES_DEMO' &&
           $json['datos']['apartamento']['codigo'] === 'DORM-101';
});

probar("POST /login/validar-demostracion valida simulación de acceso", function () {
    $res = simularPeticion('POST', '/login/validar-demostracion', [
        'usuario' => 'admin.laestanza',
        'clave' => 'demo12345'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'LOGIN_DEMO_EXITOSO';
});

probar("GET /inquilinos/estado-cuenta renderiza pantalla de estado de cuenta", function () {
    $res = simularPeticion('GET', '/inquilinos/estado-cuenta');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'Estado de cuenta por inquilino') &&
           str_contains($res['output'], 'Elige un inquilino');
});

probar("GET /inquilinos/1/estado-cuenta renderiza estado de cuenta de Carlos Mendoza con movimientos", function () {
    $res = simularPeticion('GET', '/inquilinos/1/estado-cuenta');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'Carlos Mendoza') &&
           str_contains($res['output'], 'Historial Cronológico de Movimientos') &&
           str_contains($res['output'], 'Depósito de Garantía');
});

probar("POST /inquilinos/estado-cuenta/consultar devuelve movimientos y saldos", function () {
    $res = simularPeticion('POST', '/inquilinos/estado-cuenta/consultar', [
        'inquilino_id' => '1'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'CONSULTA_ESTADO_CUENTA' &&
           isset($json['datos']['resumen']) &&
           isset($json['datos']['movimientos']) &&
           count($json['datos']['movimientos']) > 0;
});

probar("POST /apartamentos/validar-demostracion con inquilino, fiador y garantía", function () {
    $res = simularPeticion('POST', '/apartamentos/validar-demostracion', [
        'edificio' => 'Adamant',
        'numero' => '204',
        'renta_mensual' => '3500.00',
        'dia_vencimiento' => '5',
        'estado' => 'Disponible',
        'fecha_inicio' => '2026-10-01',
        'fecha_fin' => '2027-09-30',
        'inquilino_nombre' => 'Rodrigo Morales Demo',
        'inquilino_telefono' => '+502 5559-0011',
        'inquilino_correo' => 'rodrigo.demo@laestanza.local',
        'inquilino_dpi' => 'DPI 2990 11223 0101',
        'fiador_nombre' => 'Estuardo Morales Padre',
        'fiador_telefono' => '+502 5559-2233',
        'fiador_dpi' => 'DPI 1780 44556 0101',
        'deposito_monto' => '3500.00',
        'deposito_devuelto' => 'No'
    ], true);
    $json = json_decode($res['output'], true);
    return $res['status'] === 200 &&
           $json['status'] === true &&
           $json['codigo'] === 'VALIDACION_DEMO_EXITOSA' &&
           isset($json['datos']['apartamento']) &&
           isset($json['datos']['inquilino']) &&
           isset($json['datos']['fiador']) &&
           isset($json['datos']['garantia']) &&
           $json['datos']['fiador']['nombre'] === 'Estuardo Morales Padre';
});

probar("GET /panel renderiza widget de Contratos que vencen (<= 30 días)", function () {
    $res = simularPeticion('GET', '/panel');
    return $res['status'] === 200 &&
           str_contains($res['output'], 'Contratos que vencen') &&
           str_contains($res['output'], 'Adamant 204') &&
           str_contains($res['output'], 'MOISES PARDO');
});

echo "\n=== RESUMEN DE PRUEBAS ===\n";
echo "Total Pasadas: {$pruebasPasadas}\n";
echo "Total Falladas: {$pruebasFalladas}\n";

if ($pruebasFalladas > 0) {
    exit(1);
}
exit(0);
