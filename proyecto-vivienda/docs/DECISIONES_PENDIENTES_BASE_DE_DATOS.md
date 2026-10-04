# Decisiones Técnicas Pendientes para la Etapa 2 (Base de Datos Central y Persistencia)

Este documento recopila las decisiones de arquitectura, modelo de datos y reglas de negocio que deben abordarse en la **Etapa 2**, una vez completada y aprobada la interfaz MVC y la experiencia de usuario.

---

## 1. Arquitectura de Base de Datos Centralizada
- **Motor Recomendado**: MySQL 8.0+ o PostgreSQL 15+ alojado en un servidor accesible vía IP/dominio seguro (SSL/TLS).
- **Acceso Multi-Ciudad**: Los operadores en Ciudad de Guatemala y Quetzaltenango se conectarán mediante navegador al mismo servidor web central (o cluster balanceado), el cual gestionará un único pool de conexiones PDO transaccionales.

### Tablas Principales Previstas:
1. `propiedades`: id, nombre, direccion, ciudad, zona, created_at.
2. `apartamentos`: id, propiedad_id, codigo, tipo, nivel, moneda ('GTQ','USD'), alquiler_base, deposito_base, estado ('ocupado','vacio','mantenimiento').
3. `inquilinos`: id, nombre, tipo_documento, documento, telefono, email, contacto_emergencia, estado, created_at.
4. `contratos`: id, codigo, apartamento_id, inquilino_id, fecha_inicio, fecha_fin, plazo_meses, renta_pactada, moneda, deposito_garantia, estado_deposito ('custodiado','devuelto_total','devuelto_parcial','retenido'), tipo_renovacion, estado ('vigente','finalizado','rescindido').
5. `pagos`: id, contrato_id, apartamento_id, inquilino_id, anio, mes_periodo, fecha_pago, monto, mora, moneda, metodo_pago, referencia_bancaria, tipo_concepto ('renta','garantia'), estado ('pagado','parcial','anulado'), usuario_registro_id, created_at.
6. `gastos`: id, fecha, categoria, propiedad_id, apartamento_id (nullable), moneda, monto, descripcion, referencia_comprobante, usuario_registro_id, created_at.
7. `usuarios`: id, nombre, usuario, email, password_hash, rol_id, ciudad_sede, estado, created_at.
8. `auditoria`: id, usuario_id, accion, tabla_afectada, registro_id, datos_anteriores, datos_nuevos, ip, user_agent, created_at.

---

## 2. Roles, Permisos y Autenticación
- **Mecanismo**: Contraseñas cifradas con `password_hash(..., PASSWORD_ARGON2ID)`.
- **Manejo de Sesiones**: Sesiones PHP protegidas con cookies `HttpOnly`, `SameSite=Lax` y `Secure`.
- **Roles Definidos**:
  - `Superadministrador`: Acceso total, configuración del sistema, auditoría, anulación de pagos.
  - `Gestor Administrativo (Operaciones)`: Registro de inquilinos, contratos, pagos, gastos y control anual.
  - `Auditor / Solo Lectura`: Consulta de informes, balances y matriz anual sin capacidad de edición.

---

## 3. Reglas de Negocio a Definir con Gerencia
1. **Política de Mora**:
   - ¿Se mantendrá como registro manual con base en boleta bancaria o se calculará automáticamente un porcentaje / cargo fijo tras el día 5 de cada mes?
2. **Pagos Parciales**:
   - Definir si los abonos sucesivos generan recibos independientes vinculados al mismo período y si se permite pagar meses adelantados.
3. **Devolución de Depósitos de Garantía**:
   - Procedimiento de liquidación al finalizar contrato (deducción por daños, pintura, recibos de luz pendientes).
4. **Facturación e Impuestos**:
   - Integración con Facturación Electrónica en Línea (FEL de SAT Guatemala) para emisión de DTE por concepto de alquileres.
5. **Tipo de Cambio (GTQ vs USD)**:
   - Política contable: ¿Se fijará una tasa de referencia oficial (Banco de Guatemala) para balances unificados anuales o se mantendrán libros contables separados?
6. **Migración de Datos del Excel Histórico**:
   - Creación de un comando CLI de importación y limpieza para cargar inquilinos vigentes y saldos iniciales del archivo "PRESUPUESTO DORMS LA ESTANZA 2026".
