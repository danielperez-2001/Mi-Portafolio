# Referencia Funcional: Análisis de Presupuesto Dorms La Estanza 2026

## 1. Declaración de Disponibilidad del Documento Original
El archivo físico **"PRESUPUESTO DORMS LA ESTANZA 2026.pdf"** (exportado del Excel corporativo) no se encontraba en el directorio de trabajo local ni en el escritorio al momento de iniciar esta etapa.

Siguiendo la instrucción explícita del cliente:
> *"Si no está disponible, informa esa limitación y trabaja con estos requisitos."*

Se procedió a estructurar toda la lógica del sistema, los modelos de datos demostrativos, las pantallas y las validaciones de negocio en torno a los requerimientos funcionales especificados, con datos 100% ficticios y sin incluir información sensible ni contactos reales.

---

## 2. Reglas de Negocio Implementadas

### A. Gestión de Inmuebles (Apartamentos y Dorms)
- **Estructura**: Cada unidad cuenta con código identificador (`DORM-101`, `APT-201`, `SUITE-401`), propiedad/módulo asociado, nivel, tipo habitacional (Dorm Individual, Compartido, Suite), moneda de contrato asignada, renta base y depósito de garantía.
- **Estados de Inmueble**: `ocupado`, `vacio`, `mantenimiento`.

### B. Contratos y Plazos
- **Períodos**: Cada contrato define fecha de inicio, fecha de fin y plazo en meses.
- **Renovaciones**: Tipos documentados (Anual automática, Revisión semestral, Vencimiento programado).
- **Monto de Renta vs Depósito de Garantía**: Se manejan como conceptos estrictamente diferenciados.

### C. Depósitos de Garantía (Fondos en Custodia)
- **Diferenciación Fundamental**: El sistema distingue inequívocamente el **Depósito de Garantía** (fondo de respaldo en custodia que se reembolsa al finiquitar el contrato) del **Pago de Renta por Depósito Bancario** (ingreso por canon mensual de arrendamiento).
- El depósito de garantía no se suma a los ingresos del mes en el flujo de caja operativo.

### D. Cobranza y Pagos de Enero a Diciembre (Control Anual)
- **Período Cubierto vs Fecha de Recepción**: Se separan formalmente:
  - *Período cubierto*: Mes (1 al 12) y Año (ej. 2026) al que corresponde el canon de arrendamiento.
  - *Fecha de recepción*: Día exacto en que el inquilino realizó el pago en el banco.
- **Referencias Bancarias**: Se preservan siempre como texto alfanumérico (ej. `DEP-884910`, `TRF-102941`, `WIRE-US91024`) sin forzar conversión a enteros.
- **Mora Separada**: La mora se registra en un campo independiente; no se calculan moras automáticas sin una política institucional previamente aprobada.
- **Abonos y Pagos Parciales**: Si el inquilino abona un monto inferior a la renta pactada, la celda mensual se etiqueta automáticamente como `parcial` (mostrando el saldo restante).

### E. Manejo Estricto de Monedas (Quetzales GTQ y Dólares USD)
- **Regla Crítica**: No se consolidan ni suman importes en Quetzales y Dólares en un solo total sin una política de tipo de cambio oficial aprobada.
- Cada resumen (ingresos, gastos, balance y control anual) desglosa filas separadas:
  - Totales en **GTQ (Q)**
  - Totales en **USD ($)**

### F. Distinción entre Unidad Vacía y Celda Sin Datos
- **Unidad Vacía (`vacio`)**: Habitación sin contrato vigente durante el período en cuestión (celda gris neutra).
- **Pendiente / Mora (`pendiente`)**: Unidad con contrato activo en un mes transcurrido sin registro de pago (celda roja).
- **Sin Información (`sin_informacion`)**: Períodos futuros o registros pendientes de verificar (celda púrpura/azul).

### G. Control de Gastos y Mantenimiento
- Clasificación por categorías: *Servicios*, *Mantenimiento*, *Reparaciones*, *Compras*, *Extras*.
- Imputación a propiedad general o apartamento específico.

---

## 3. Aspectos Pendientes de Revisar del Excel Original
Cuando se disponga del archivo Excel / PDF original, se deben auditar:
1. Errores de fórmula identificados como `#REF!` o `#¡VALOR!` en las hojas de cálculo de origen.
2. Posibles desfases en fechas de recepción vs meses contables.
3. Tratamiento de comisiones bancarias por transferencias ACH o pagos con tarjeta.
4. Política de redondeo para pagos en dólares con transferencias intermediarias.
