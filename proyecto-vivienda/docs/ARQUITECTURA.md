# Arquitectura de Software - Sistema La Estanza (Etapa 1)

## 1. Visión General
El sistema web de alquileres de apartamentos y dorms para **La Estanza** ha sido concebido bajo una arquitectura **MVC (Modelo - Vista - Controlador)** propia en **PHP 8.x**, con enrutamiento explícito, separación estricta de responsabilidades, empaquetado moderno de assets mediante **Webpack** y diseño responsive empresarial con **Bootstrap 5**, **jQuery** y **CSS personalizado**.

Esta primera etapa implementa el núcleo funcional, enrutamiento, validaciones UX/servidor y pantallas navegables con datos ficticios realistas para el año fiscal 2026. **No conecta a base de datos persistente**, garantizando que los formularios no inserten datos en ningún medio de almacenamiento temporal (sin SQLite, sin sesiones, sin localStorage, sin archivos de texto).

---

## 2. Diagrama de Flujo de Peticiones

```
 Navegador Web (Cliente)
       │
       ▼
 public/index.php (Front Controller)
       │
       ├─► App\Support\Env (Carga variables de entorno .env)
       ├─► App\Core\View::init (Inicializa directorio de vistas)
       │
       ▼
 App\Core\Router (Despacha según HTTP Method y URI)
       │
       ├─► GET (Navegación) ─────────────► App\Controllers\*Controller
       │                                           │
       │                                           ▼
       │                                    App\Core\View::render
       │                                           │
       │                                           ▼
       │                                    resources/views/layouts/main.php
       │                                    + resources/views/{modulo}/*.php
       │
       └─► POST (Consulta/Validación Demo) ► App\Controllers\*Controller
                                                   │
                                                   ▼
                                            App\Models\*Model (DataDemo en memoria)
                                                   │
                                                   ▼
                                            App\Core\Response::json (Contrato Uniforme)
```

---

## 3. Separación Obligatoria de Responsabilidades

| Capa | Ubicación | Responsabilidad | Prohibiciones Estrictas |
|---|---|---|---|
| **Vistas** | `resources/views/` | Estructura HTML pura, presentación con helpers de escape `e()`, inclusión de componentes y tablas. | Cero lógica de negocio, cero consultas, cero procesamiento directo de `$_POST`, cero `<script>`, cero `<style>`, cero eventos `onclick` o atributos inline. |
| **CSS** | `resources/css/` | Diseño visual modular, tokens semánticos en `variables.css`, grilla de control anual y estilos responsivos. | No se escribe CSS en las vistas ni en bloques `<style>`. Se compila con Webpack hacia `public/assets/css/app.css`. |
| **JavaScript** | `resources/js/` | Módulos ES6, eventos de interfaz con jQuery, peticiones `$.ajax` estructuradas, alertas con SweetAlert2/toastr y control por `txtTipo`. | Cero consultas SQL o lógica de persistencia. No asume URLs quemadas; las lee de atributos `data-url`. |
| **Controladores** | `app/Controllers/` | Renderizan vistas o procesan peticiones AJAX, validan datos en servidor, invocan modelos y devuelven respuestas JSON estructuradas con bloques `try/catch`. | Cero HTML concatenado manualmente, cero CSS, cero funciones de interfaz. |
| **Modelos** | `app/Models/` | Métodos de consulta y filtrado sobre el repositorio central de datos ficticios (`DataDemo.php`). | No imprimen HTML/JSON, no envían headers, no leen `$_POST`, no conectan a base de datos en esta etapa. |

---

## 4. Acceso Concurrente Multi-Ciudad (Siguiente Etapa)
El requerimiento establece que **dos personas desde diferentes ciudades (ej. Ciudad de Guatemala y Quetzaltenango)** accederán al sistema a través de su navegador web y utilizarán una sola base de datos central.

Para cumplir con esta necesidad:
1. **Centralización Web**: El sistema es una aplicación web responsive centralizada. Ningún cliente depende de software local propietario.
2. **Helpers de URL Absoluta**: Todas las rutas y assets se resuelven mediante `Url::to()` y `Url::asset()`, soportando dominios públicos, subcarpetas en XAMPP o servidores integrados sin hardcodear `localhost`.
3. **Mismo MVC para JSON y HTML**: Los endpoints JSON pertenecen a los mismos controladores del MVC (sin microservicios externos innecesarios).
4. **Preparado para Base de Datos en la Nube**: Al pasar a la Etapa 2, bastará con reemplazar los métodos de `App\Models\DataDemo` por conexiones PDO hacia la base de datos MySQL/PostgreSQL centralizada, sin modificar la estructura de controladores ni de vistas.
