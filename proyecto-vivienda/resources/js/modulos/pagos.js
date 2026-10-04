import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarExito, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarPagos() {
    var modalElemento = document.getElementById("modalPago");
    var modalPago = modalElemento ? new Modal(modalElemento) : null;

    // 1. Abrir Modal de Registrar Pago Demo
    $("#btnNuevoPago").on("click", function () {
        var form = document.getElementById("formPagoDemo");
        if (form) {
            form.reset();
        }
        if (modalPago) {
            modalPago.show();
        }
    });

    // Auto-completar inquilino y monto de renta al seleccionar apartamento
    $("#selectAptoPago").on("change", function () {
        var $opcion = $(this).find(":selected");
        var inq = $opcion.attr("data-inquilino") || "";
        var rent = $opcion.attr("data-alquiler") || "";
        if (inq) {
            $("#txtInquilinoPago").val(inq);
        }
        if (rent) {
            $("#txtMontoPago").val(rent);
        }
    });

    // 2. Validación de Formulario de Pago (POST AJAX)
    $("#formPagoDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlValidacion = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        // Validaciones UX en cliente
        var apto = $.trim($("#selectAptoPago").val());
        var mes = parseInt($("#selectMesPeriodo").val(), 10) || 0;
        var fechaPago = $.trim($("#txtFechaPago").val());
        var monto = parseFloat($("#txtMontoPago").val()) || 0;
        var referencia = $.trim($("#txtReferenciaPago").val());

        if (!apto) {
            mostrarAviso("Debe seleccionar un apartamento.");
            return;
        }

        if (mes < 1 || mes > 12) {
            mostrarAviso("Debe seleccionar un mes de período válido.");
            return;
        }

        if (!fechaPago) {
            mostrarAviso("Debe indicar la fecha en que se recibió el pago.");
            return;
        }

        if (monto <= 0) {
            mostrarAviso("El monto pagado debe ser mayor a 0.00.");
            return;
        }

        if (!referencia) {
            mostrarAviso("Debe ingresar la referencia bancaria o número de boleta.");
            return;
        }

        var formData = $form.serialize();

        peticionAjax(urlValidacion, formData, { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                if (modalPago) {
                    modalPago.hide();
                }

                var datos = respuesta.datos;
                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>Apartamento:</strong> ${escapeHtml(datos.apartamento_codigo)}</li>
                            <li><strong>Concepto:</strong> <span class="badge bg-primary-subtle text-primary">${escapeHtml(datos.tipo_concepto)}</span></li>
                            <li><strong>Período Cubierto:</strong> ${escapeHtml(datos.periodo)}</li>
                            <li><strong>Fecha Recepción:</strong> ${escapeHtml(datos.fecha_pago)}</li>
                            <li><strong>Monto Pagado:</strong> ${datos.moneda} ${datos.monto}</li>
                            <li><strong>Mora Registrada:</strong> ${datos.moneda} ${datos.mora}</li>
                            <li><strong>Método:</strong> ${escapeHtml(datos.metodo)}</li>
                            <li><strong>Referencia Bancaria:</strong> <code>${escapeHtml(datos.referencia)}</code></li>
                        </ul>
                    </div>
                `;

                mostrarModalDemo(
                    "Pago Validado (Simulación)",
                    "El comprobante de pago fue validado exitosamente.",
                    htmlDetalles
                );
            }
        });
    });

    // 3. Búsqueda de Pago por Referencia Bancaria (POST AJAX)
    $("#btnBuscarReferencia").on("click", function () {
        var $btn = $(this);
        var urlBuscar = $btn.attr("data-url");
        var referencia = $.trim($("#txtBuscarReferencia").val());

        if (!referencia) {
            mostrarAviso("Ingrese la referencia bancaria para buscar.");
            return;
        }

        peticionAjax(urlBuscar, { referencia: referencia }, { boton: $btn[0] }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                var p = respuesta.datos;
                mostrarExito(`Pago encontrado: ${p.apartamento_codigo} - ${p.moneda} ${p.monto} (${p.referencia})`);

                // Filtrar tabla
                var $filas = $("#tablaPagos tbody tr");
                $filas.each(function () {
                    var $tr = $(this);
                    if ($tr.text().toUpperCase().indexOf(referencia.toUpperCase()) !== -1) {
                        $tr.removeClass("d-none").addClass("table-warning");
                        setTimeout(function () { $tr.removeClass("table-warning"); }, 2000);
                    } else {
                        $tr.addClass("d-none");
                    }
                });
            }
        });
    });

    // 4. Limpiar Filtro de Pagos
    $("#btnLimpiarFiltroPagos").on("click", function () {
        $("#txtBuscarReferencia").val("");
        $("#tablaPagos tbody tr").removeClass("d-none");
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
