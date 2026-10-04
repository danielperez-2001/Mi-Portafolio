import { Modal } from "bootstrap";
import $ from "jquery";
import { peticionAjax } from "../utilidades/ajax";
import { mostrarAviso, mostrarExito, mostrarModalDemo } from "../utilidades/alertas";

export function iniciarInquilinos() {
    var modalElemento = document.getElementById("modalInquilino");
    var modalInquilino = modalElemento ? new Modal(modalElemento) : null;

    $("#btnNuevoInquilino").on("click", function () {
        var form = document.getElementById("formInquilinoDemo");
        if (form) form.reset();
        if (modalInquilino) modalInquilino.show();
    });

    $("#formInquilinoDemo").on("submit", function (e) {
        e.preventDefault();

        var $form = $(this);
        var urlValidacion = $form.attr("data-url") || $form.attr("action");
        var btnSubmit = $form.find("button[type='submit']")[0];

        var nombre = $.trim($("#txtNombreInquilino").val());
        var doc = $.trim($("#txtDocInquilino").val());
        var tel = $.trim($("#txtTelInquilino").val());

        if (!nombre) {
            mostrarAviso("El nombre del inquilino es obligatorio.");
            return;
        }
        if (!doc) {
            mostrarAviso("El documento de identificación es obligatorio.");
            return;
        }
        if (!tel) {
            mostrarAviso("El teléfono de contacto es obligatorio.");
            return;
        }

        peticionAjax(urlValidacion, $form.serialize(), { boton: btnSubmit }).done(function (respuesta) {
            if (respuesta.status) {
                if (modalInquilino) modalInquilino.hide();

                var d = respuesta.datos;
                var htmlDetalles = `
                    <div class="card bg-light border-0 p-3">
                        <ul class="list-unstyled mb-0 small">
                            <li><strong>Nombre:</strong> ${escapeHtml(d.nombre)}</li>
                            <li><strong>Identificación:</strong> ${escapeHtml(d.documento)}</li>
                            <li><strong>Teléfono:</strong> ${escapeHtml(d.telefono)}</li>
                            <li><strong>Correo:</strong> ${escapeHtml(d.email || '-')}</li>
                        </ul>
                    </div>
                `;

                mostrarModalDemo("Inquilino Validado (Simulación)", "Validación de datos exitosa.", htmlDetalles);
            }
        });
    });

    $("#btnBuscarInquilino").on("click", function () {
        var $btn = $(this);
        var urlBuscar = $btn.attr("data-url");
        var termino = $.trim($("#txtBuscarInquilino").val());

        if (!termino) {
            mostrarAviso("Ingrese un nombre, documento o apartamento para buscar.");
            return;
        }

        peticionAjax(urlBuscar, { termino: termino }, { boton: $btn[0] }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                mostrarExito(`Se encontraron ${respuesta.total} coincidencia(s).`);

                var $filas = $("#tablaInquilinos tbody tr");
                $filas.each(function () {
                    var $tr = $(this);
                    if ($tr.text().toUpperCase().indexOf(termino.toUpperCase()) !== -1) {
                        $tr.removeClass("d-none").addClass("table-warning");
                        setTimeout(function () { $tr.removeClass("table-warning"); }, 2000);
                    } else {
                        $tr.addClass("d-none");
                    }
                });
            }
        });
    });

    $("#btnLimpiarInquilinos").on("click", function () {
        $("#txtBuscarInquilino").val("");
        $("#tablaInquilinos tbody tr").removeClass("d-none");
    });

    // 4. Selector Dinámico de Estado de Cuenta (img1.png)
    $("#selectInquilinoEstadoCuenta").on("change", function () {
        var $select = $(this);
        var inquilinoId = $select.val();
        var urlConsultar = $select.attr("data-url");
        var $contenedor = $("#contenedorEstadoCuenta");

        if (!inquilinoId) {
            $contenedor.html(`
                <div class="content-card text-center py-5">
                    <div class="mb-3 text-muted">
                        <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Seleccione un inquilino</h5>
                    <p class="text-muted small mx-auto" style="max-width: 480px;">
                        Elija un inquilino del selector desplegable superior para consultar su estado de cuenta en tiempo real, desglose de cargos por período, abonos recibidos, comprobantes bancarios y balance.
                    </p>
                </div>
            `);
            return;
        }

        $contenedor.html(`
            <div class="content-card text-center py-5">
                <div class="spinner-border text-primary mb-2" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <div class="small text-muted">Consultando movimientos y saldos contables...</div>
            </div>
        `);

        peticionAjax(urlConsultar, { inquilino_id: inquilinoId }).done(function (respuesta) {
            if (respuesta.status && respuesta.datos) {
                renderizarEstadoCuenta(respuesta.datos);
            }
        });
    });

    // 5. Botón de Imprimir / Exportar Comprobante
    $(document).on("click", "#btnImprimirEstadoCuenta", function () {
        mostrarExito("Modo Demostración: Comprobante preparado para impresión / exportación PDF.");
        setTimeout(function () {
            window.print();
        }, 500);
    });
}

function renderizarEstadoCuenta(datos) {
    var inq = datos.inquilino;
    var apto = datos.apartamento || {};
    var con = datos.contrato || {};
    var res = datos.resumen;
    var movs = datos.movimientos || [];
    var moneda = res.moneda || 'GTQ';

    var htmlFilas = "";
    movs.forEach(function (m) {
        var badgeTipo = "";
        if (m.tipo === "Cargo") {
            badgeTipo = '<span class="badge bg-danger-subtle text-danger">Cargo</span>';
        } else if (m.tipo === "Abono") {
            badgeTipo = '<span class="badge bg-success-subtle text-success">Abono</span>';
        } else {
            badgeTipo = `<span class="badge bg-secondary-subtle text-secondary">${escapeHtml(m.tipo)}</span>`;
        }

        var debitoStr = Number(m.debito) > 0 ? `${moneda} ${Number(m.debito).toLocaleString('es-GT', {minimumFractionDigits: 2})}` : '-';
        var creditoStr = Number(m.credito) > 0 ? `${moneda} ${Number(m.credito).toLocaleString('es-GT', {minimumFractionDigits: 2})}` : '-';
        var saldoStr = `${moneda} ${Number(m.saldo).toLocaleString('es-GT', {minimumFractionDigits: 2})}`;

        htmlFilas += `
            <tr>
                <td>${escapeHtml(m.fecha)}</td>
                <td>${badgeTipo}</td>
                <td class="fw-semibold text-dark">${escapeHtml(m.concepto)}</td>
                <td class="text-end fw-bold ${Number(m.debito) > 0 ? 'text-dark' : 'text-muted'}">${debitoStr}</td>
                <td class="text-end fw-bold ${Number(m.credito) > 0 ? 'text-success' : 'text-muted'}">${creditoStr}</td>
                <td class="text-end fw-bold ${Number(m.saldo) > 0 ? 'text-danger' : 'text-dark'}">${saldoStr}</td>
                <td><code>${escapeHtml(m.referencia)}</code></td>
                <td><span class="badge bg-secondary-subtle text-secondary">${escapeHtml(m.estado)}</span></td>
            </tr>
        `;
    });

    var htmlTotal = `
        <!-- Ficha Resumen del Inquilino, Contrato y Fiador -->
        <div class="content-card mb-4">
            <div class="row g-4">
                <div class="col-12 col-md-4 border-end-md">
                    <span class="badge bg-primary-subtle text-primary mb-2 fw-semibold">Datos del Inquilino</span>
                    <h4 class="fw-bold text-dark mb-1">${escapeHtml(inq.nombre)}</h4>
                    <div class="small text-muted mb-1"><strong>Documento:</strong> ${escapeHtml(inq.documento)}</div>
                    <div class="small text-muted mb-1"><strong>Teléfono:</strong> ${escapeHtml(inq.telefono)}</div>
                    <div class="small text-muted"><strong>Correo:</strong> ${escapeHtml(inq.email || '-')}</div>
                </div>
                <div class="col-12 col-md-4 border-end-md">
                    <span class="badge bg-secondary-subtle text-secondary mb-2 fw-semibold">Unidad y Contrato</span>
                    <h5 class="fw-bold text-dark mb-1">${escapeHtml(inq.apartamento_codigo)} - ${escapeHtml(apto.tipo || 'Unidad Habitacional')}</h5>
                    <div class="small text-muted mb-1"><strong>Renta Mensual:</strong> ${moneda} ${Number(res.renta_mensual).toLocaleString('es-GT', {minimumFractionDigits: 2})}</div>
                    <div class="small text-muted mb-1"><strong>Vencimiento:</strong> Día ${inq.dia_vencimiento || 5} de cada mes</div>
                    <div class="small text-muted"><strong>Vigencia:</strong> ${escapeHtml(con.fecha_inicio || '2026-01-01')} al ${escapeHtml(con.fecha_fin || '2026-12-31')}</div>
                </div>
                <div class="col-12 col-md-4">
                    <span class="badge bg-info-subtle text-info mb-2 fw-semibold">Garantía y Fiador</span>
                    <div class="fw-bold text-dark mb-1">${escapeHtml(inq.fiador_nombre || 'Sin fiador registrado')}</div>
                    <div class="small text-muted mb-1"><strong>Teléfono Fiador:</strong> ${escapeHtml(inq.fiador_telefono || '-')}</div>
                    <div class="small text-muted mb-1"><strong>DPI Fiador:</strong> ${escapeHtml(inq.fiador_dpi || '-')}</div>
                    <div class="small text-muted">
                        <strong>Depósito en Garantía:</strong> ${moneda} ${Number(res.deposito_garantia).toLocaleString('es-GT', {minimumFractionDigits: 2})}
                        <span class="badge bg-light text-dark border ms-1">Custodiado (Devuelto: ${escapeHtml(res.deposito_devuelto)})</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Saldo Financiero -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi kpi-gtq">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Total Facturado (Cargos)</div>
                    <div class="kpi-value text-primary">${moneda} ${Number(res.total_cargos).toLocaleString('es-GT', {minimumFractionDigits: 2})}</div>
                    <div class="kpi-subtext">Rentas periódicas acumuladas</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi kpi-usd">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Total Pagos Recibidos</div>
                    <div class="kpi-value text-success">${moneda} ${Number(res.total_pagos).toLocaleString('es-GT', {minimumFractionDigits: 2})}</div>
                    <div class="kpi-subtext">Boletas y transferencias validadas</div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi ${Number(res.saldo_pendiente) > 0 ? 'kpi-gastos' : 'kpi-ocupacion'}">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Saldo Pendiente Actual</div>
                    <div class="kpi-value ${Number(res.saldo_pendiente) > 0 ? 'text-danger' : 'text-success'}">
                        ${moneda} ${Number(res.saldo_pendiente).toLocaleString('es-GT', {minimumFractionDigits: 2})}
                    </div>
                    <div class="kpi-subtext">
                        <span class="badge bg-${res.estado_cuenta_clase}-subtle text-${res.estado_cuenta_clase}">
                            ${escapeHtml(res.estado_cuenta)}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card-kpi">
                    <div class="kpi-accent-bar"></div>
                    <div class="kpi-label">Depósito de Garantía</div>
                    <div class="kpi-value text-purple">${moneda} ${Number(res.deposito_garantia).toLocaleString('es-GT', {minimumFractionDigits: 2})}</div>
                    <div class="kpi-subtext">Fondo en custodia no imputable</div>
                </div>
            </div>
        </div>

        <!-- Tabla de Movimientos Contables -->
        <div class="content-card">
            <div class="content-card-header">
                <div>
                    <h2 class="card-title-main">Historial Cronológico de Movimientos</h2>
                    <p class="card-subtitle-main">Detalle de cargos por renta, abonos, mora desglosada y saldo acumulado</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th>Concepto / Detalle</th>
                            <th class="text-end">Débito (+)</th>
                            <th class="text-end">Crédito (-)</th>
                            <th class="text-end">Saldo Acumulado</th>
                            <th>Boleta / Comprobante</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${htmlFilas}
                    </tbody>
                </table>
            </div>
        </div>
    `;

    $("#contenedorEstadoCuenta").html(htmlTotal);
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
