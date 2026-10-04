# Sistema de Alquileres de Apartamentos y Dorms - La Estanza (Etapa 1)

Sistema web administrativo y de control anual para la gestión de unidades habitacionales, contratos de arrendamiento, depósitos en custodia, cobranza mensual y gastos operativos de **La Estanza**.

Diseñado bajo el patrón de arquitectura **PHP MVC propio**, empaquetado de assets con **Webpack**, estilos modernos con **Bootstrap 5 / Vanilla CSS** y dinamismo con **JavaScript ES Modules + jQuery**.

---

## 1. Alcance de la Etapa 1
Esta primera etapa entrega el sistema 100% navegable con código funcional y datos ficticios centralizados:
- **Arquitectura MVC propia**: Front Controller (`public/index.php`), Enrutador (`App\Core\Router`), Controladores por módulo (`App\Controllers`), Modelos sobre datos en memoria (`App\Models\DataDemo`) y Vistas (`resources/views`).
- **Control Anual (Pantalla Principal)**: Matriz interactiva de pagos de enero a diciembre por apartamento, con estados (`pagado`, `parcial`, `pendiente`, `vacio`, `sin_informacion`), selector de año dinámico, modal de comprobantes y resúmenes mensuales desglosados en **Quetzales (GTQ)** y **Dólares (USD)** sin sumas cruzadas.
- **Formularios de Demostración**: Validación en cliente y en servidor vía endpoints POST AJAX (`/apartamentos/validar-demostracion`, `/pagos/validar-demostracion`, etc.). Muestran previsualización y notifican explícitamente que la información no fue persistida en base de datos.
- **Sin Persistencia Temporal Indebida**: No se utilizan bases de datos locales provisionales (SQLite), archivos JSON de escritura, sesiones ni `localStorage` como sustituto de la base de datos.
- **Preparación Multi-Ciudad**: Los endpoints JSON pertenecen al mismo MVC (sin APIs externas) y el sistema resuelve URLs de forma relativa/dinámica para soportar que dos operadores accedan simultáneamente desde ciudades distintas (ej. Ciudad de Guatemala y Quetzaltenango) hacia una base de datos central en la Etapa 2.

---

## 2. Requisitos del Entorno
- **PHP**: Versión 8.1 o superior (probado en PHP 8.2.12 ZTS / XAMPP).
- **Composer**: Versión 2.x para autoloading PSR-4 (`App\\`).
- **Node.js y npm**: Node v18+ (probado en Node v24.21.0 y npm 11.19.0).
- **Servidor Web**: Apache (XAMPP) con módulo `mod_rewrite` habilitado, o el Servidor Integrado de PHP (`php -S`).

---

## 3. Instalación y Puesta en Marcha

### Paso 1: Clonar o posicionarse en el proyecto
```bash
cd "c:\Users\ariel\OneDrive\Escritorio\proyecto de vivienda"
```

### Paso 2: Instalar dependencias de PHP (Composer)
Genera el cargador PSR-4 optimizado y el archivo `composer.lock`:
```bash
composer install
```

### Paso 3: Configurar variables de entorno
Copie `.env.example` a `.env`:
```bash
cp .env.example .env
```
Ajuste `APP_URL` según su forma de ejecución:
- Servidor integrado PHP: `APP_URL="http://localhost:8000"`
- XAMPP VirtualHost: `APP_URL="http://laestanza.local"`
- XAMPP subdirectorio: `APP_URL="http://localhost/proyecto%20de%20vivienda/public"`

### Paso 4: Instalar dependencias de JavaScript y CSS (npm)
Instala Bootstrap 5, jQuery, SweetAlert2, Toastr, Webpack y plugins:
```bash
npm install
```

### Paso 5: Compilar los assets con Webpack
Para compilar los bundles minificados de producción en `public/assets/`:
```bash
npm run build
```
Para desarrollo con source maps:
```bash
npm run dev
```
Para modo escucha y recarga automática ante cambios:
```bash
npm run watch
```

---

## 4. Opciones de Ejecución Local

### Opción A: Servidor Integrado de PHP (Recomendada para pruebas rápidas)
Ejecute en la raíz del proyecto:
```bash
php -S localhost:8000 router.php
```
Abra su navegador en: **`http://localhost:8000`** (redirige automáticamente a `/panel`).
> El archivo `router.php` despacha los estáticos de `public/` y bloquea el acceso a `.env`, `storage/` o código privado.

### Opción B: Apache / XAMPP con DocumentRoot en `public/`
1. Configure un VirtualHost en `httpd-vhosts.conf` de Apache:
```apache
<VirtualHost *:80>
    ServerName laestanza.local
    DocumentRoot "C:/Users/ariel/OneDrive/Escritorio/proyecto de vivienda/public"
    <Directory "C:/Users/ariel/OneDrive/Escritorio/proyecto de vivienda/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```
2. Agregue `127.0.0.1 laestanza.local` a su archivo `hosts` de Windows.
3. Abra **`http://laestanza.local`**.

---

## 5. Estructura de Directorios

```text
proyecto de vivienda/
├── app/
│   ├── Controllers/          # Controladores por módulo (render + validación JSON)
│   │   ├── ApartamentoController.php
│   │   ├── AuthController.php
│   │   ├── ConfiguracionController.php
│   │   ├── ContratoController.php
│   │   ├── ControlAnualController.php
│   │   ├── GastoController.php
│   │   ├── InquilinoController.php
│   │   ├── PagoController.php
│   │   ├── PanelController.php
│   │   ├── ReporteController.php
│   │   └── UsuarioController.php
│   ├── Core/                 # Núcleo MVC independiente
│   │   ├── Controller.php    # Controlador base con helpers JSON y render
│   │   ├── Request.php       # Abstracción de petición HTTP
│   │   ├── Response.php      # Respuesta JSON y redirecciones
│   │   ├── Router.php        # Enrutador con callbacks [new Ctrl(), 'metodo']
│   │   └── View.php          # Motor de vistas PHP y layouts
│   ├── Models/               # Modelos y acceso a datos
│   │   ├── ApartamentoModel.php
│   │   ├── ContratoModel.php
│   │   ├── ControlAnualModel.php
│   │   ├── DataDemo.php      # Repositorio central de datos ficticios en memoria
│   │   ├── GastoModel.php
│   │   ├── InquilinoModel.php
│   │   ├── PagoModel.php
│   │   └── ReporteModel.php
│   └── Support/              # Clases de soporte y helpers
│       ├── Env.php           # Lector de .env
│       ├── Format.php        # Formateo de monedas, fechas y badges
│       ├── Helpers.php       # Funciones globales e(), url(), asset(), money()
│       └── Url.php           # Generación de URLs canónicas
├── config/                   # Archivos de configuración
│   ├── app.php
│   └── database.php          # Especificación para la Etapa 2
├── docs/                     # Documentación técnica
│   ├── ARQUITECTURA.md
│   ├── CONTRATO_JSON_Y_RUTAS.md
│   ├── DECISIONES_PENDIENTES_BASE_DE_DATOS.md
│   └── REFERENCIA_FUNCIONAL_PRESUPUESTO.md
├── public/                   # Raíz web pública (DocumentRoot)
│   ├── assets/
│   │   ├── css/app.css       # CSS compilado y extraído por Webpack
│   │   ├── js/app.js         # JavaScript compilado por Webpack
│   │   └── images/logo.svg   # Identidad visual La Estanza
│   ├── .htaccess             # Reescribir URL en Apache/XAMPP
│   └── index.php             # Front Controller (Entrada principal)
├── resources/                # Recursos fuente (sin compilar)
│   ├── css/
│   │   ├── modulos/          # Estilos por pantalla (control-anual, panel, login)
│   │   ├── app.css           # Hoja de estilos maestra
│   │   └── variables.css     # Tokens de diseño y paleta cromática
│   ├── js/
│   │   ├── modulos/          # Controladores JS por pantalla
│   │   ├── utilidades/       # Helpers ajax.js y alertas.js
│   │   └── app.js            # Orquestador con selector por #txtTipo
│   └── views/                # Vistas PHP limpias (sin inline JS ni CSS)
│       ├── apartamentos/
│       ├── auth/
│       ├── configuracion/
│       ├── contratos/
│       ├── control-anual/     # Pantalla principal: Matriz mensual
│       ├── gastos/
│       ├── inquilinos/
│       ├── layouts/          # Layouts main.php y auth.php
│       ├── pagos/
│       ├── panel/
│       ├── partials/         # Componentes: sidebar, header, aviso-demo
│       ├── reportes/
│       └── usuarios/
├── routes/
│   └── web.php               # Definición de rutas GET y POST
├── storage/
│   └── logs/                 # Registro de errores de servidor
├── .env.example
├── .gitignore
├── composer.json
├── package.json
├── router.php                # Enrutador para servidor de desarrollo PHP
└── webpack.config.js         # Configuración de compilación Webpack
```

---

## 6. Códigos de Pantalla (`txtTipo`) y JavaScript

Cada vista expone un campo oculto `<input type="hidden" id="txtTipo" value="...">` que el script `resources/js/app.js` evalúa mediante condicionales `if / else if` para cargar únicamente el módulo necesario:

| Código | Módulo | Función Principal |
|---|---|---|
| `PAN` | Panel de Control | Métricas KPI, tasa de ocupación, cobros recientes |
| `CA` | Control Anual 2026 | Matriz de 12 meses, filtros dinámicos, modal de cobros |
| `APT` | Apartamentos y Dorms | Catálogo, búsqueda de código, modal de nuevo apartamento |
| `APT_DET`| Detalle Apartamento | Contrato asociado e historial de recibos de la unidad |
| `INQ` | Inquilinos | Catálogo de contactos, búsqueda AJAX, modal nuevo inquilino |
| `CON` | Contratos | Plazos, renta pactada, depósito de garantía en custodia |
| `PAG` | Cobranza y Pagos | Validación de recibos, distinción de garantía vs renta |
| `GAS` | Gastos y Reparaciones | Egresos clasificados por categoría y propiedad |
| `REP` | Reportes | Balances financieros separados en GTQ y USD |
| `USU` | Usuarios | Pantalla preparada para acceso concurrente multi-ciudad |
| `CFG` | Configuración | Parámetros del sistema y días de gracia |
| `LOG` | Autenticación | Pantalla visual de simulación de acceso |

---

## 7. Mapa de Rutas Web

### Rutas GET (Navegación):
- `GET /` -> Redirige a `/panel`
- `GET /panel` -> `[new PanelController(), 'index']`
- `GET /apartamentos` -> `[new ApartamentoController(), 'apartamentos']`
- `GET /apartamentos/{id}` -> `[new ApartamentoController(), 'detalle']`
- `GET /inquilinos` -> `[new InquilinoController(), 'inquilinos']`
- `GET /contratos` -> `[new ContratoController(), 'contratos']`
- `GET /pagos` -> `[new PagoController(), 'pagos']`
- `GET /gastos` -> `[new GastoController(), 'gastos']`
- `GET /control-anual` -> `[new ControlAnualController(), 'controlAnual']`
- `GET /reportes` -> `[new ReporteController(), 'reportes']`
- `GET /usuarios` -> `[new UsuarioController(), 'usuarios']`
- `GET /configuracion` -> `[new ConfiguracionController(), 'configuracion']`
- `GET /login` -> `[new AuthController(), 'login']`

### Rutas POST (Consultas y Validaciones Demo):
- `POST /apartamentos/buscar` -> `[new ApartamentoController(), 'buscarApartamento']`
- `POST /apartamentos/validar-demostracion` -> `[new ApartamentoController(), 'validarApartamentoDemostracion']`
- `POST /inquilinos/buscar` -> `[new InquilinoController(), 'buscarInquilino']`
- `POST /inquilinos/validar-demostracion` -> `[new InquilinoController(), 'validarInquilinoDemostracion']`
- `POST /contratos/buscar` -> `[new ContratoController(), 'buscarContrato']`
- `POST /contratos/validar-demostracion` -> `[new ContratoController(), 'validarContratoDemostracion']`
- `POST /pagos/buscar` -> `[new PagoController(), 'buscarPago']`
- `POST /pagos/validar-demostracion` -> `[new PagoController(), 'validarPagoDemostracion']`
- `POST /gastos/buscar` -> `[new GastoController(), 'buscarGasto']`
- `POST /gastos/validar-demostracion` -> `[new GastoController(), 'validarGastoDemostracion']`
- `POST /control-anual/filtrar` -> `[new ControlAnualController(), 'filtrar']`
- `POST /control-anual/detalle-mes` -> `[new ControlAnualController(), 'detalleMes']`
- `POST /login/validar-demostracion` -> `[new AuthController(), 'validarLoginDemostracion']`

---

## 8. Contrato Uniforme de Respuestas JSON

Todas las respuestas de los endpoints POST devuelven un formato estándar:

```json
{
  "status": true,
  "codigo": "CONSULTA_DEMO",
  "message": "Consulta de datos de demostración realizada con éxito.",
  "datos": { ... },
  "total": 1,
  "demo": true
}
```

Códigos HTTP coherentes:
- `200 OK`: Petición procesada exitosamente.
- `404 Not Found`: Apartamento, contrato, pago o gasto no encontrado.
- `422 Unprocessable Entity`: Errores de validación de campos del formulario.
- `405 Method Not Allowed`: Método HTTP no permitido para la ruta.
- `500 Internal Server Error`: Error no controlado (registrado con `error_log` sin filtrar trazas al cliente).

---

## 9. Próximos Pasos (Etapa 2)
Detente antes de iniciar la base de datos. Para la siguiente etapa queda preparado:
1. Creación del esquema relacional en MySQL/PostgreSQL centralizado.
2. Migración de las clases modelo de `DataDemo` hacia repositorios con PDO transaccional.
3. Autenticación real con sesiones seguras multi-ciudad y roles (`Superadministrador`, `Gestor`, `Auditor`).
4. Procedimiento de liquidación de depósitos de garantía al concluir contrato.
5. Políticas oficiales de cálculo de mora y tipo de cambio GTQ / USD.
6. Importación y saneamiento de los datos históricos del Excel de La Estanza.
