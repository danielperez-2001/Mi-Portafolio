import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarExito, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarApartamentos() {
    var modalElemento = document.getElementById("modalApartamento");
    var modalApartamento = modalElemento ? new Modal(modalElemento) : null;

    // 1. Abrir Modal de Nuevo Apartamento
    $("#btnNuevoApartamento").on("click", function () {
        var form = document.getElementById("formApartamentoDemo");
        if (form) {
            form.reset();
        }
        if (modalApartamento) {
            modalApartamento.show();
        }
    });

    // 2. Validación de Formulario en Demostración (POST AJAX)
    $("#formApartamentoDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlValidacion = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        // Validaciones UX en cliente según img4.png
        var edificio = $.trim($("#txtEdificioApto").val());
        var numero = $.trim($("#txtNumeroApto").val());
        var renta = parseFloat($("#txtRentaMensual").val()) || 0;
        var diaVencimiento = parseInt($("#txtDiaVencimiento").val(), 10) || 5;

        if (!edificio) {
            mostrarAviso("Por favor ingrese el nombre del edificio o módulo.");
            return;
        }

        if (!numero) {
            mostrarAviso("Por favor ingrese el número de apartamento o dorm.");
            return;
        }

        if (diaVencimiento < 1 || diaVencimiento > 31) {
            mostrarAviso("El día de vencimiento debe estar entre 1 y 31.");
            return;
        }

        var formData = $form.serialize();

        peticionAjax(urlValidacion, formData, { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                if (modalApartamento) {
                    modalApartamento.hide();
                }

                var d = respuesta.datos;
                var apt = d.apartamento;
                var inq = d.inquilino;
                var fia = d.fiador;
                var gar = d.garantia;

                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3 text-start small">
                        <div class="fw-bold text-dark border-bottom pb-1 mb-2">1. Datos del Apartamento</div>
                        <ul class="list-unstyled mb-2">
                            <li><strong>Unidad:</strong> ${escapeHtml(apt.edificio)} - ${escapeHtml(apt.numero)}</li>
                            <li><strong>Renta Mensual:</strong> Q ${Number(apt.renta_mensual).toLocaleString('es-GT', {minimumFractionDigits: 2})}</li>
                            <li><strong>Vence:</strong> Día ${apt.dia_vencimiento} de cada mes</li>
                            <li><strong>Estado:</strong> <span class="badge bg-primary">${escapeHtml(apt.estado)}</span></li>
                            <li><strong>Vigencia:</strong> ${escapeHtml(apt.fecha_inicio)} al ${escapeHtml(apt.fecha_fin)}</li>
                        </ul>

                        <div class="fw-bold text-dark border-bottom pb-1 mb-2">2. Inquilino Asignado</div>
                        <ul class="list-unstyled mb-2">
                            <li><strong>Nombre:</strong> ${escapeHtml(inq.nombre)}</li>
                            <li><strong>Teléfono:</strong> ${escapeHtml(inq.telefono)} | <strong>DPI:</strong> ${escapeHtml(inq.dpi)}</li>
                            <li><strong>Correo:</strong> ${escapeHtml(inq.correo)}</li>
                        </ul>

                        <div class="fw-bold text-dark border-bottom pb-1 mb-2">3. Fiador</div>
                        <ul class="list-unstyled mb-2">
                            <li><strong>Nombre Fiador:</strong> ${escapeHtml(fia.nombre)}</li>
                            <li><strong>Teléfono Fiador:</strong> ${escapeHtml(fia.telefono)} | <strong>DPI:</strong> ${escapeHtml(fia.dpi)}</li>
                        </ul>

                        <div class="fw-bold text-dark border-bottom pb-1 mb-2">4. Depósito de Garantía</div>
                        <ul class="list-unstyled mb-0">
                            <li><strong>Monto:</strong> Q ${Number(gar.monto).toLocaleString('es-GT', {minimumFractionDigits: 2})}</li>
                            <li><strong>Devuelto:</strong> ${escapeHtml(gar.devuelto)}</li>
                        </ul>
                    </div>
                `;

                mostrarModalDemo(
                    "Apartamento Validado (Simulación)",
                    "El apartamento con inquilino, fiador y garantía fue validado con éxito.",
                    htmlDetalles
                );
            }
        });
    });

    // 3. Búsqueda de Apartamento por Código (POST AJAX)
    $("#btnBuscarApartamento").on("click", function () {
        var $btn = $(this);
        var urlBuscar = $btn.attr("data-url");
        var codigo = $.trim($("#txtBuscarCodigo").val());

        if (!codigo) {
            mostrarAviso("Ingrese un código de apartamento para buscar.");
            return;
        }

        peticionAjax(urlBuscar, { codigo_apartamento: codigo }, { boton: $btn[0] }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                var apto = respuesta.datos;
                mostrarExito(`Apartamento encontrado: ${apto.codigo} (${apto.tipo})`);

                // Filtrar visualmente la tabla
                var $filas = $("#tablaApartamentos tbody tr");
                $filas.each(function () {
                    var $tr = $(this);
                    var textoFila = $tr.text().toUpperCase();
                    if (textoFila.indexOf(codigo.toUpperCase()) !== -1) {
                        $tr.removeClass("d-none").addClass("table-warning");
                        setTimeout(function () { $tr.removeClass("table-warning"); }, 2000);
                    } else {
                        $tr.addClass("d-none");
                    }
                });
            }
        });
    });

    // 4. Limpiar Filtro de Búsqueda
    $("#btnLimpiarBusqueda").on("click", function () {
        $("#txtBuscarCodigo").val("");
        $("#tablaApartamentos tbody tr").removeClass("d-none");
    });
}

function escapeHtml(texto) {
    if (!texto) return "";
    return String(texto)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
