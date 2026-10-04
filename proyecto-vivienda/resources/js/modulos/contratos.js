import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarExito, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarContratos() {
    var modalElemento = document.getElementById("modalContrato");
    var modalContrato = modalElemento ? new Modal(modalElemento) : null;

    $("#btnNuevoContrato").on("click", function () {
        var form = document.getElementById("formContratoDemo");
        if (form) form.reset();
        if (modalContrato) modalContrato.show();
    });

    $("#formContratoDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlValidacion = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        var aptoId = $("#selectAptoContrato").val();
        var inqId = $("#selectInqContrato").val();
        var fechaInicio = $.trim($("#txtFechaInicioContrato").val());
        var fechaFin = $.trim($("#txtFechaFinContrato").val());
        var renta = parseFloat($("#txtRentaContrato").val()) || 0;

        if (!aptoId) {
            mostrarAviso("Debe seleccionar un apartamento.");
            return;
        }
        if (!inqId) {
            mostrarAviso("Debe seleccionar un inquilino.");
            return;
        }
        if (!fechaInicio || !fechaFin) {
            mostrarAviso("Debe ingresar las fechas de inicio y vencimiento del contrato.");
            return;
        }
        if (renta <= 0) {
            mostrarAviso("El monto de alquiler debe ser mayor a 0.");
            return;
        }

        peticionAjax(urlValidacion, $form.serialize(), { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                if (modalContrato) modalContrato.hide();

                var d = respuesta.datos;
                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>Vigencia:</strong> ${escapeHtml(d.fecha_inicio)} al ${escapeHtml(d.fecha_fin)}</li>
                            <li><strong>Renta Pactada:</strong> ${escapeHtml(d.moneda)} ${d.monto_alquiler}</li>
                            <li><strong>Depósito de Garantía:</strong> ${escapeHtml(d.moneda)} ${d.deposito_garantia}</li>
                            <li><strong>Renovación:</strong> ${escapeHtml(d.tipo_renovacion)}</li>
                        </ul>
                    </div>
                `;

                mostrarModalDemo("Contrato Validado (Simulación)", "Validación de contrato exitosa.", htmlDetalles);
            }
        });
    });

    $("#btnBuscarContrato").on("click", function () {
        var $btn = $(this);
        var urlBuscar = $btn.attr("data-url");
        var codigo = $.trim($("#txtBuscarContrato").val());

        if (!codigo) {
            mostrarAviso("Ingrese un código de contrato (ej. CON-2026-001).");
            return;
        }

        peticionAjax(urlBuscar, { codigo_contrato: codigo }, { boton: $btn[0] }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                var c = respuesta.datos;
                mostrarExito(`Contrato ${c.codigo} encontrado.`);

                var $filas = $("#tablaContratos tbody tr");
                $filas.each(function () {
                    var $tr = $(this);
                    if ($tr.text().toUpperCase().indexOf(codigo.toUpperCase()) !== -1) {
                        $tr.removeClass("d-none").addClass("table-warning");
                        setTimeout(function () { $tr.removeClass("table-warning"); }, 2000);
                    } else {
                        $tr.addClass("d-none");
                    }
                });
            }
        });
    });

    $("#btnLimpiarContratos").on("click", function () {
        $("#txtBuscarContrato").val("");
        $("#tablaContratos tbody tr").removeClass("d-none");
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
