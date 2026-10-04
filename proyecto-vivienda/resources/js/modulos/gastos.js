import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarExito, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarGastos() {
    var modalElemento = document.getElementById("modalGasto");
    var modalGasto = modalElemento ? new Modal(modalElemento) : null;

    $("#btnNuevoGasto").on("click", function () {
        var form = document.getElementById("formGastoDemo");
        if (form) form.reset();
        if (modalGasto) modalGasto.show();
    });

    $("#formGastoDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlValidacion = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        var fecha = $.trim($("#txtFechaGasto").val());
        var categoria = $("#selectCatGasto").val();
        var monto = parseFloat($("#txtMontoGasto").val()) || 0;
        var desc = $.trim($("#txtDescGasto").val());

        if (!fecha) {
            mostrarAviso("Debe indicar la fecha del gasto.");
            return;
        }
        if (!categoria) {
            mostrarAviso("Debe seleccionar una categoría.");
            return;
        }
        if (monto <= 0) {
            mostrarAviso("El monto del gasto debe ser mayor a 0.");
            return;
        }
        if (!desc) {
            mostrarAviso("Debe agregar una descripción del gasto.");
            return;
        }

        peticionAjax(urlValidacion, $form.serialize(), { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                if (modalGasto) modalGasto.hide();

                var d = respuesta.datos;
                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>Fecha:</strong> ${escapeHtml(d.fecha)}</li>
                            <li><strong>Categoría:</strong> ${escapeHtml(d.categoria)}</li>
                            <li><strong>Propiedad/Apto:</strong> ${escapeHtml(d.propiedad_ubicacion)} (${escapeHtml(d.apartamento_codigo)})</li>
                            <li><strong>Importe:</strong> ${escapeHtml(d.moneda)} ${d.monto}</li>
                            <li><strong>Descripción:</strong> ${escapeHtml(d.descripcion)}</li>
                        </ul>
                    </div>
                `;

                mostrarModalDemo("Gasto Validado (Simulación)", "Gasto procesado en modo demostración.", htmlDetalles);
            }
        });
    });

    $("#btnBuscarGasto").on("click", function () {
        var $btn = $(this);
        var urlBuscar = $btn.attr("data-url");
        var ref = $.trim($("#txtBuscarGasto").val());

        if (!ref) {
            mostrarAviso("Ingrese la referencia o factura del gasto.");
            return;
        }

        peticionAjax(urlBuscar, { referencia: ref }, { boton: $btn[0] }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                var g = respuesta.datos;
                mostrarExito(`Gasto encontrado: ${g.categoria} - ${g.moneda} ${g.monto}`);

                var $filas = $("#tablaGastos tbody tr");
                $filas.each(function () {
                    var $tr = $(this);
                    if ($tr.text().toUpperCase().indexOf(ref.toUpperCase()) !== -1) {
                        $tr.removeClass("d-none").addClass("table-warning");
                        setTimeout(function () { $tr.removeClass("table-warning"); }, 2000);
                    } else {
                        $tr.addClass("d-none");
                    }
                });
            }
        });
    });

    $("#btnLimpiarGastos").on("click", function () {
        $("#txtBuscarGasto").val("");
        $("#tablaGastos tbody tr").removeClass("d-none");
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
