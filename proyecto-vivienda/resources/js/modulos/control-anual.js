import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarError } from "../utilidades/alertas";

export function iniciarControlAnual() {
    var modalDetalleElemento = document.getElementById("modalDetalleMes");
    var modalDetalle = modalDetalleElemento ? new Modal(modalDetalleElemento) : null;

    // 1. Filtrado dinámico de la matriz (POST AJAX)
    $("#formFiltrosControlAnual").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlFiltrar = $form.attr("data-url") || $form.attr("action");
        var btnFiltrar = $form.find("button[type='submit']")[0];
        var formData = $form.serialize();

        peticionAjax(urlFiltrar, formData, { boton: btnFiltrar }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                renderizarMatriz(respuesta.datos);
            }
        });
    });

    // 2. Click en una celda mensual para ver detalle de pagos (POST AJAX)
    $(document).on("click", ".celda-mes-btn", function (e) {
        e.preventDefault();

        var $btn = $(this);
        var apto = $btn.attr("data-apto");
        var mes = parseInt($btn.attr("data-mes"), 10);
        var anio = parseInt($btn.attr("data-anio"), 10) || 2026;
        var estado = $btn.attr("data-estado");
        var urlDetalle = $("#tablaControlAnual").attr("data-url-detalle");

        if (!urlDetalle) {
            mostrarError("No se encontró la ruta para consultar el detalle del mes.");
            return;
        }

        // Si la celda está vacía
        if (estado === "vacio") {
            $("#modalDetalleMesTitulo").text(`${apto} - Mes ${mes} / ${anio}`);
            $("#modalDetalleMesCuerpo").html(`
                <div class="alert alert-secondary text-center py-4 mb-0">
                    <h5 class="fw-bold">Unidad Vacía</h5>
                    <p class="small text-muted mb-0">Este apartamento o dorm no contó con contrato vigente durante este mes.</p>
                </div>
            `);
            if (modalDetalle) modalDetalle.show();
            return;
        }

        // Si la celda no tiene pagos registrados aún
        if (estado === "sin_informacion" || estado === "pendiente") {
            var badgePendiente = (estado === 'pendiente')
                ? '<span class="badge bg-danger">Pendiente / En Mora</span>'
                : '<span class="badge bg-secondary">Sin Información</span>';

            $("#modalDetalleMesTitulo").text(`${apto} - Mes ${mes} / ${anio}`);
            $("#modalDetalleMesCuerpo").html(`
                <div class="alert alert-light border py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Estado del Período:</strong> ${badgePendiente}
                    </div>
                    <p class="small text-muted mb-0">No se encontraron pagos registrados en el sistema para este período.</p>
                </div>
            `);
            if (modalDetalle) modalDetalle.show();
            return;
        }

        peticionAjax(urlDetalle, {
            apartamento_codigo: apto,
            mes: mes,
            anio: anio
        }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                var d = respuesta.datos;
                $("#modalDetalleMesTitulo").text(`Detalle de Pagos: ${d.apartamento.codigo} - Mes ${d.mes} / ${d.anio}`);

                var htmlPagos = "";
                if (d.pagos && d.pagos.length > 0) {
                    htmlPagos = `<div class="table-responsive"><table class="table table-sm table-bordered align-middle">
                        <thead class="table-light small">
                            <tr>
                                <th>Fecha Pago</th>
                                <th>Monto</th>
                                <th>Mora</th>
                                <th>Método</th>
                                <th>Referencia Bancaria</th>
                                <th>Notas</th>
                            </tr>
                        </thead>
                        <tbody class="small">`;

                    d.pagos.forEach(function (p) {
                        htmlPagos += `
                            <tr>
                                <td>${escapeHtml(p.fecha_pago)}</td>
                                <td class="fw-bold">${escapeHtml(p.moneda)} ${parseFloat(p.monto).toFixed(2)}</td>
                                <td>${parseFloat(p.mora) > 0 ? (p.moneda + ' ' + parseFloat(p.mora).toFixed(2)) : '-'}</td>
                                <td>${escapeHtml(p.metodo)}</td>
                                <td><code>${escapeHtml(p.referencia)}</code></td>
                                <td class="text-muted">${escapeHtml(p.observaciones || '-')}</td>
                            </tr>
                        `;
                    });

                    htmlPagos += `</tbody></table></div>`;
                } else {
                    htmlPagos = `<p class="text-muted small">No se registraron recibos detallados para este mes.</p>`;
                }

                $("#modalDetalleMesCuerpo").html(`
                    <div class="card bg-light border-0 p-3 mb-3">
                        <div class="row g-2 small">
                            <div class="col-6"><strong>Apartamento:</strong> ${escapeHtml(d.apartamento.codigo)} (${escapeHtml(d.apartamento.tipo)})</div>
                            <div class="col-6"><strong>Inquilino:</strong> ${escapeHtml(d.apartamento.inquilino_actual || 'Sin contrato')}</div>
                            <div class="col-6"><strong>Renta Mensual:</strong> ${escapeHtml(d.apartamento.moneda)} ${parseFloat(d.apartamento.alquiler).toFixed(2)}</div>
                            <div class="col-6"><strong>Total Recaudado:</strong> ${escapeHtml(d.apartamento.moneda)} ${d.pagos.reduce((acc, x) => acc + parseFloat(x.monto), 0).toFixed(2)}</div>
                        </div>
                    </div>
                    <h6 class="fw-bold mb-2">Comprobantes y Boletas Recibidas</h6>
                    ${htmlPagos}
                `);

                if (modalDetalle) {
                    modalDetalle.show();
                }
            }
        });
    });

    // 3. Resetear Filtros
    $("#btnLimpiarFiltrosCA").on("click", function () {
        var form = document.getElementById("formFiltrosControlAnual");
        if (form) {
            form.reset();
            $(form).trigger("submit");
        }
    });
}

function renderizarMatriz(datosMatriz) {
    var $tbody = $("#tablaControlAnual tbody.cuerpo-filas");
    var anio = datosMatriz.anio;
    var filas = datosMatriz.filas;

    $tbody.empty();

    if (!filas || filas.length === 0) {
        $tbody.html('<tr><td colspan="14" class="text-center py-4 text-muted">No se encontraron apartamentos con los filtros seleccionados.</td></tr>');
        return;
    }

    filas.forEach(function (f) {
        var apto = f.apartamento;
        var meses = f.meses;
        var monedaSymbol = apto.moneda === 'USD' ? '$' : 'Q';

        var trHtml = `
            <tr>
                <td class="col-fija">
                    <div class="fw-bold text-dark">${escapeHtml(apto.codigo)} <span class="badge ${apto.moneda === 'USD' ? 'badge-usd' : 'badge-gtq'} ms-1">${escapeHtml(apto.moneda)}</span></div>
                    <div class="small text-muted text-truncate" style="max-width: 200px;">${escapeHtml(apto.tipo)}</div>
                    <div class="small text-muted"><i class="small">Renta:</i> <strong>${monedaSymbol} ${parseFloat(apto.alquiler).toFixed(2)}</strong></div>
                </td>
        `;

        for (var m = 1; m <= 12; m++) {
            var c = meses[m];
            var claseEstado = "celda-estado-" + c.estado;
            var contenido = "";

            if (c.estado === "vacio") {
                contenido = `<span class="badge bg-light text-secondary border">Vacío</span>`;
            } else if (c.estado === "sin_informacion") {
                contenido = `<span class="badge bg-light text-muted">-</span>`;
            } else if (c.estado === "pendiente") {
                contenido = `
                    <div class="text-danger fw-bold small">Pendiente</div>
                    <div class="celda-meta text-danger">${monedaSymbol} ${parseFloat(c.monto_esperado).toFixed(2)}</div>
                `;
            } else {
                var refStr = c.referencias && c.referencias.length > 0 ? c.referencias[0] : "";
                var fechaStr = c.fechas && c.fechas.length > 0 ? c.fechas[0].substring(5) : "";
                contenido = `
                    <div class="celda-monto">${monedaSymbol} ${parseFloat(c.monto_pagado).toFixed(2)}</div>
                    ${refStr ? `<span class="celda-ref">${escapeHtml(refStr)}</span>` : ""}
                    ${fechaStr ? `<span class="celda-meta">${escapeHtml(fechaStr)}</span>` : ""}
                `;
            }

            trHtml += `
                <td class="celda-mes">
                    <a href="javascript:void(0)" class="celda-item ${claseEstado} celda-mes-btn"
                       data-apto="${escapeHtml(apto.codigo)}" data-mes="${m}" data-anio="${anio}" data-estado="${c.estado}">
                        ${contenido}
                    </a>
                </td>
            `;
        }

        // Columna de total fila
        var totalFila = 0;
        for (var m = 1; m <= 12; m++) {
            totalFila += parseFloat(meses[m].monto_pagado) || 0;
        }

        trHtml += `
            <td class="text-end fw-bold bg-light">
                ${monedaSymbol} ${totalFila.toFixed(2)}
            </td>
        </tr>`;

        $tbody.append(trHtml);
    });

    // Actualizar filas de resumen al pie si existen
    if (datosMatriz.resumen) {
        var r = datosMatriz.resumen;
        for (var m = 1; m <= 12; m++) {
            $(`.total-ingresos-gtq-mes-${m}`).text("Q " + (r.ingresos_gtq[m] || 0).toFixed(2));
            $(`.total-ingresos-usd-mes-${m}`).text("$ " + (r.ingresos_usd[m] || 0).toFixed(2));
            $(`.total-gastos-gtq-mes-${m}`).text("Q " + (r.gastos_gtq[m] || 0).toFixed(2));
            $(`.total-gastos-usd-mes-${m}`).text("$ " + (r.gastos_usd[m] || 0).toFixed(2));
        }
        $(".total-ingresos-gtq-anual").text("Q " + (r.total_ingresos_gtq || 0).toFixed(2));
        $(".total-ingresos-usd-anual").text("$ " + (r.total_ingresos_usd || 0).toFixed(2));
        $(".total-gastos-gtq-anual").text("Q " + (r.total_gastos_gtq || 0).toFixed(2));
        $(".total-gastos-usd-anual").text("$ " + (r.total_gastos_usd || 0).toFixed(2));
    }
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
