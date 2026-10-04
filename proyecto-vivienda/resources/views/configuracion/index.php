<input type="hidden" id="txtTipo" value="CFG">

<div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Configuración del Sistema</h1>
        <p class="text-muted small mb-0">Parámetros operativos generales, monedas y políticas de vencimiento</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-md-7">
        <div class="content-card">
            <h2 class="card-title-main mb-3">Parámetros Generales de La Estanza</h2>
            <form id="formConfigDemo">
                <div class="mb-3">
                    <label for="cfgNombreEmpresa" class="form-label small fw-semibold">Nombre de la Empresa</label>
                    <input type="text" class="form-control form-control-sm" id="cfgNombreEmpresa" value="<?= e($parametros['empresa_nombre']) ?>" readonly>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="cfgMoneda1" class="form-label small fw-semibold">Moneda Local</label>
                        <input type="text" class="form-control form-control-sm" id="cfgMoneda1" value="<?= e($parametros['moneda_principal']) ?>" readonly>
                    </div>
                    <div class="col-6">
                        <label for="cfgMoneda2" class="form-label small fw-semibold">Moneda Internacional</label>
                        <input type="text" class="form-control form-control-sm" id="cfgMoneda2" value="<?= e($parametros['moneda_secundaria']) ?>" readonly>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label for="cfgDiasGracia" class="form-label small fw-semibold">Días de Gracia para Pagos</label>
                        <input type="number" class="form-control form-control-sm" id="cfgDiasGracia" value="<?= (int) $parametros['dias_gracia_pago'] ?>">
                        <div class="form-text" style="font-size: 0.72rem;">Días hábiles del mes antes de registrar estado en mora.</div>
                    </div>
                    <div class="col-6">
                        <label for="cfgAvisoVencimiento" class="form-label small fw-semibold">Alerta Previa de Vencimiento (Días)</label>
                        <input type="number" class="form-control form-control-sm" id="cfgAvisoVencimiento" value="<?= (int) $parametros['dias_anticipacion_aviso_vencimiento'] ?>">
                        <div class="form-text" style="font-size: 0.72rem;">Anticipación para recordatorio de renovación de contrato.</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-brand-primary btn-sm">Guardar Preferencias (Demo)</button>
            </form>
        </div>
    </div>

    <div class="col-12 col-md-5">
        <div class="content-card h-100">
            <h2 class="card-title-main mb-3">Reglas de Negocio Documentadas</h2>
            <div class="alert alert-light border small">
                <h6 class="fw-bold mb-1">Manejo de Monedas</h6>
                <p class="mb-2 text-muted">Los montos en Quetzales (GTQ) y Dólares (USD) no se consolidan en una sola cifra sin una política de tipo de cambio oficial aprobada.</p>

                <h6 class="fw-bold mb-1">Depósitos de Garantía</h6>
                <p class="mb-2 text-muted">El depósito de garantía se mantiene en cuenta de custodia y no se computa como pago de renta mensual.</p>

                <h6 class="fw-bold mb-1">Unidades Vacías</h6>
                <p class="mb-0 text-muted">Se distingue formalmente una unidad desocupada (sin contrato) de un pago pendiente o atrasado.</p>
            </div>
        </div>
    </div>
</div>
