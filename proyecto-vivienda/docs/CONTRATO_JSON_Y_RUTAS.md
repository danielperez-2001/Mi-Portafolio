# Contrato JSON, Rutas Web y Códigos txtTipo - La Estanza

## 1. Códigos de Operación en Vistas (`txtTipo`)

Cada pantalla incluye un campo oculto `<input type="hidden" id="txtTipo" value="...">` que el script orquestador `resources/js/app.js` lee para activar exclusivamente los eventos y módulos correspondientes:

| Código | Pantalla / Módulo | Controlador | Archivo JS |
|---|---|---|---|
| `PAN` | Panel de Control (Dashboard) | `PanelController::index` | `resources/js/modulos/panel.js` |
| `APT` | Catálogo de Apartamentos y Dorms | `ApartamentoController::apartamentos` | `resources/js/modulos/apartamentos.js` |
| `APT_DET`| Detalle de Apartamento / Historial | `ApartamentoController::detalle` | `resources/js/modulos/apartamentos.js` |
| `INQ` | Directorio de Inquilinos | `InquilinoController::inquilinos` | `resources/js/modulos/inquilinos.js` |
| `CON` | Contratos de Arrendamiento | `ContratoController::contratos` | `resources/js/modulos/contratos.js` |
| `PAG` | Control de Cobranza y Pagos | `PagoController::pagos` | `resources/js/modulos/pagos.js` |
| `GAS` | Gastos Operativos y Mantenimiento| `GastoController::gastos` | `resources/js/modulos/gastos.js` |
| `CA` | Control Anual (Matriz 2026) | `ControlAnualController::controlAnual`| `resources/js/modulos/control-anual.js` |
| `REP` | Reportes e Informes Financieros | `ReporteController::reportes` | `resources/js/modulos/reportes.js` |
| `USU` | Usuarios Multi-Ciudad | `UsuarioController::usuarios` | `resources/js/modulos/usuarios.js` |
| `CFG` | Configuración del Sistema | `ConfiguracionController::configuracion`| `resources/js/modulos/configuracion.js` |
| `LOG` | Inicio de Sesión (Visual Demo) | `AuthController::login` | `resources/js/modulos/auth.js` |

---

## 2. Mapa Completo de Rutas Web (`routes/web.php`)

### Rutas GET (Navegación y Vistas):
| Método | Ruta | Controlador :: Método | Descripción |
|---|---|---|---|
| `GET` | `/` | Redirección a `/panel` | Redirige al panel inicial |
| `GET` | `/panel` | `PanelController::index` | Dashboard principal |
| `GET` | `/apartamentos` | `ApartamentoController::apartamentos` | Listado de apartamentos y dorms |
| `GET` | `/apartamentos/{id}` | `ApartamentoController::detalle` | Detalle, contrato e historial del inmueble |
| `GET` | `/inquilinos` | `InquilinoController::inquilinos` | Catálogo de inquilinos y contactos |
| `GET` | `/contratos` | `ContratoController::contratos` | Contratos vigentes, plazos y depósitos |
| `GET` | `/pagos` | `PagoController::pagos` | Registro y consulta de pagos |
| `GET` | `/gastos` | `GastoController::gastos` | Egresos operativos y reparaciones |
| `GET` | `/control-anual` | `ControlAnualController::controlAnual`| Matriz anual de pagos y ocupación 2026 |
| `GET` | `/reportes` | `ReporteController::reportes` | Balances y comparativas por moneda |
| `GET` | `/usuarios` | `UsuarioController::usuarios` | Operadores y ciudades de acceso |
| `GET` | `/configuracion`| `ConfiguracionController::configuracion`| Parámetros generales |
| `GET` | `/login` | `AuthController::login` | Pantalla visual de autenticación |

### Rutas POST (Consultas y Validaciones AJAX - Modo Demostración):
| Método | Ruta | Controlador :: Método | Función AJAX |
|---|---|---|---|
| `POST` | `/apartamentos/buscar` | `ApartamentoController::buscarApartamento` | Consulta apartamento por código |
| `POST` | `/apartamentos/validar-demostracion`| `ApartamentoController::validarApartamentoDemostracion`| Valida formulario de unidad |
| `POST` | `/inquilinos/buscar` | `InquilinoController::buscarInquilino` | Búsqueda de inquilino por término |
| `POST` | `/inquilinos/validar-demostracion`| `InquilinoController::validarInquilinoDemostracion`| Valida datos de nuevo inquilino |
| `POST` | `/contratos/buscar` | `ContratoController::buscarContrato` | Consulta contrato por código |
| `POST` | `/contratos/validar-demostracion` | `ContratoController::validarContratoDemostracion` | Valida vigencias y garantías |
| `POST` | `/pagos/buscar` | `PagoController::buscarPago` | Consulta pago por referencia bancaria |
| `POST` | `/pagos/validar-demostracion` | `PagoController::validarPagoDemostracion` | Valida boleta/transferencia recibida |
| `POST` | `/gastos/buscar` | `GastoController::buscarGasto` | Consulta gasto por comprobante |
| `POST` | `/gastos/validar-demostracion` | `GastoController::validarGastoDemostracion` | Valida formulario de egreso |
| `POST` | `/control-anual/filtrar` | `ControlAnualController::filtrar` | Filtra dinámicamente la matriz anual |
| `POST` | `/control-anual/detalle-mes` | `ControlAnualController::detalleMes` | Obtiene pagos del mes para el modal |
| `POST` | `/login/validar-demostracion` | `AuthController::validarLoginDemostracion` | Simula autenticación en pantalla visual |

---

## 3. Contrato de Respuesta JSON Uniforme

Todas las respuestas de los endpoints POST retornan una estructura consistente:

```json
{
  "status": true,
  "codigo": "CONSULTA_DEMO",
  "message": "Consulta de datos de demostración realizada con éxito.",
  "datos": {
    "codigo": "DORM-101",
    "tipo": "Dorm Individual",
    "moneda": "GTQ",
    "alquiler": 2200.00
  },
  "total": 1,
  "demo": true
}
```

### Respuestas de Error de Validación (HTTP 422):
```json
{
  "status": false,
  "codigo": "DATOS_INVALIDOS",
  "message": "El código de apartamento/dorm es obligatorio.",
  "datos": {
    "errores": [
      "El código de apartamento/dorm es obligatorio."
    ]
  },
  "demo": true
}
```

### Respuestas de Recurso no Encontrado (HTTP 404):
```json
{
  "status": false,
  "codigo": "APARTAMENTO_NO_ENCONTRADO",
  "message": "No se encontró ningún apartamento con el código 'XYZ'.",
  "datos": null,
  "demo": true
}
```
