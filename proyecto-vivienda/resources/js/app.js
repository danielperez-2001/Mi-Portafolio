/**
 * --------------------------------------------------------------------------
 * Sistema de Alquileres La Estanza - Orquestador Principal JavaScript
 * --------------------------------------------------------------------------
 * Enrutador de módulos según el campo oculto #txtTipo en cada vista.
 * Códigos soportados y documentados:
 *   - PAN: Panel de Control (Dashboard)
 *   - APT: Apartamentos y Dorms (Listado y Búsqueda)
 *   - APT_DET: Detalle de Apartamento
 *   - INQ: Directorio de Inquilinos
 *   - CON: Contratos de Alquiler
 *   - PAG: Pagos y Comprobantes
 *   - GAS: Control de Gastos y Mantenimiento
 *   - CA:  Control Anual (Matriz de Pagos y Ocupación)
 *   - REP: Reportes e Informes Anuales
 *   - USU: Control de Usuarios y Accesos Multi-Ciudad
 *   - CFG: Configuración del Sistema
 *   - LOG: Inicio de Sesión (Visual / Demostración)
 * --------------------------------------------------------------------------
 */

import { Dropdown, Modal } from "bootstrap";
import $ from "jquery";
import Swal from "sweetalert2";
import toastr from "toastr";

import { iniciarApartamentos } from "./modulos/apartamentos";
import { iniciarAuth } from "./modulos/auth";
import { iniciarConfiguracion } from "./modulos/configuracion";
import { iniciarContratos } from "./modulos/contratos";
import { iniciarControlAnual } from "./modulos/control-anual";
import { iniciarGastos } from "./modulos/gastos";
import { iniciarInquilinos } from "./modulos/inquilinos";
import { iniciarPagos } from "./modulos/pagos";
import { iniciarPanel } from "./modulos/panel";
import { iniciarReportes } from "./modulos/reportes";
import { iniciarUsuarios } from "./modulos/usuarios";

$(function () {
    // 1. Control del Sidebar en Dispositivos Móviles
    $("#btnToggleSidebar").on("click", function () {
        $(".app-sidebar").toggleClass("show");
    });

    $(document).on("click", function (e) {
        if ($(window).width() < 992) {
            var $target = $(e.target);
            if (!$target.closest(".app-sidebar").length && !$target.closest("#btnToggleSidebar").length) {
                $(".app-sidebar").removeClass("show");
            }
        }
    });

    // 2. Detección del tipo de pantalla / operación mediante campo oculto #txtTipo
    var campoTipo = document.getElementById("txtTipo");
    if (!campoTipo) {
        return;
    }

    var tipoOperacion = campoTipo.value;

    if (tipoOperacion === "PAN") {
        iniciarPanel();
    } else if (tipoOperacion === "APT" || tipoOperacion === "APT_DET") {
        iniciarApartamentos();
    } else if (tipoOperacion === "INQ" || tipoOperacion === "INQ_EDC") {
        iniciarInquilinos();
    } else if (tipoOperacion === "CON") {
        iniciarContratos();
    } else if (tipoOperacion === "PAG") {
        iniciarPagos();
    } else if (tipoOperacion === "GAS") {
        iniciarGastos();
    } else if (tipoOperacion === "CA") {
        iniciarControlAnual();
    } else if (tipoOperacion === "REP") {
        iniciarReportes();
    } else if (tipoOperacion === "USU") {
        iniciarUsuarios();
    } else if (tipoOperacion === "CFG") {
        iniciarConfiguracion();
    } else if (tipoOperacion === "LOG") {
        iniciarAuth();
    }
});
