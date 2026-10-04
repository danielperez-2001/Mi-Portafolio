<?php

namespace App\Models;

/**
 * Repositorio central de datos ficticios en memoria para La Estanza (Demostración).
 * En la siguiente etapa estos datos serán provistos por una base de datos central.
 */
class DataDemo
{
    private static ?array $propiedades = null;
    private static ?array $apartamentos = null;
    private static ?array $inquilinos = null;
    private static ?array $contratos = null;
    private static ?array $pagos = null;
    private static ?array $gastos = null;

    public static function getPropiedades(): array
    {
        if (self::$propiedades === null) {
            self::$propiedades = [
                ['id' => 1, 'nombre' => 'Dorms La Estanza - Módulo Central', 'ubicacion' => 'Zona 16, Sector Universitario, Ciudad de Guatemala'],
                ['id' => 2, 'nombre' => 'Apartamentos La Estanza - Torre Alta', 'ubicacion' => 'Zona 16, Vista Hermosa IV, Ciudad de Guatemala']
            ];
        }
        return self::$propiedades;
    }

    public static function getApartamentos(): array
    {
        if (self::$apartamentos === null) {
            self::$apartamentos = [
                [
                    'id' => 1,
                    'codigo' => 'DORM-101',
                    'propiedad_id' => 1,
                    'propiedad_nombre' => 'Dorms La Estanza - Módulo Central',
                    'tipo' => 'Dorm Individual',
                    'nivel' => 'Nivel 1',
                    'moneda' => 'GTQ',
                    'alquiler' => 2200.00,
                    'deposito' => 2200.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Carlos Mendoza (Demo)',
                    'inquilino_id' => 1,
                    'contrato_activo' => 'CON-2026-001',
                    'caracteristicas' => 'Baño privado, amueblado básico, incluye agua e internet wifi.'
                ],
                [
                    'id' => 2,
                    'codigo' => 'DORM-102',
                    'propiedad_id' => 1,
                    'propiedad_nombre' => 'Dorms La Estanza - Módulo Central',
                    'tipo' => 'Dorm Individual',
                    'nivel' => 'Nivel 1',
                    'moneda' => 'GTQ',
                    'alquiler' => 2200.00,
                    'deposito' => 2200.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Sofia Morales (Demo)',
                    'inquilino_id' => 2,
                    'contrato_activo' => 'CON-2026-002',
                    'caracteristicas' => 'Cama individual, closet empotrado, vista al jardín interior.'
                ],
                [
                    'id' => 3,
                    'codigo' => 'DORM-103',
                    'propiedad_id' => 1,
                    'propiedad_nombre' => 'Dorms La Estanza - Módulo Central',
                    'tipo' => 'Dorm Doble Compartido',
                    'nivel' => 'Nivel 1',
                    'moneda' => 'GTQ',
                    'alquiler' => 3100.00,
                    'deposito' => 3100.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Javier Quintana (Demo)',
                    'inquilino_id' => 3,
                    'contrato_activo' => 'CON-2026-003',
                    'caracteristicas' => 'Dos camas, baño privado amplio, dos escritorios de estudio.'
                ],
                [
                    'id' => 4,
                    'codigo' => 'DORM-104',
                    'propiedad_id' => 1,
                    'propiedad_nombre' => 'Dorms La Estanza - Módulo Central',
                    'tipo' => 'Dorm Individual',
                    'nivel' => 'Nivel 1',
                    'moneda' => 'GTQ',
                    'alquiler' => 2200.00,
                    'deposito' => 2200.00,
                    'estado' => 'vacio',
                    'inquilino_actual' => null,
                    'inquilino_id' => null,
                    'contrato_activo' => null,
                    'caracteristicas' => 'Unidad desocupada disponible para nuevo contrato desde Abril.'
                ],
                [
                    'id' => 5,
                    'codigo' => 'APT-201',
                    'propiedad_id' => 2,
                    'propiedad_nombre' => 'Apartamentos La Estanza - Torre Alta',
                    'tipo' => 'Apartamento 1 Habitación',
                    'nivel' => 'Nivel 2',
                    'moneda' => 'GTQ',
                    'alquiler' => 3800.00,
                    'deposito' => 3800.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Mariana Estrada (Demo)',
                    'inquilino_id' => 4,
                    'contrato_activo' => 'CON-2026-004',
                    'caracteristicas' => 'Sala-comedor, cocina con gabinetes, 1 parqueo techado.'
                ],
                [
                    'id' => 6,
                    'codigo' => 'APT-202',
                    'propiedad_id' => 2,
                    'propiedad_nombre' => 'Apartamentos La Estanza - Torre Alta',
                    'tipo' => 'Apartamento 2 Habitaciones',
                    'nivel' => 'Nivel 2',
                    'moneda' => 'GTQ',
                    'alquiler' => 4800.00,
                    'deposito' => 4800.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Fernando Castillo (Demo)',
                    'inquilino_id' => 5,
                    'contrato_activo' => 'CON-2026-005',
                    'caracteristicas' => '2 dormitorios, 2 baños completos, balcón panorámico.'
                ],
                [
                    'id' => 7,
                    'codigo' => 'APT-301',
                    'propiedad_id' => 2,
                    'propiedad_nombre' => 'Apartamentos La Estanza - Torre Alta',
                    'tipo' => 'Apartamento Ejecutivo',
                    'nivel' => 'Nivel 3',
                    'moneda' => 'USD',
                    'alquiler' => 650.00,
                    'deposito' => 650.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Valeria Ruiz (Demo)',
                    'inquilino_id' => 6,
                    'contrato_activo' => 'CON-2026-006',
                    'caracteristicas' => 'Totalmente equipado, línea blanca completa, facturación en USD.'
                ],
                [
                    'id' => 8,
                    'codigo' => 'SUITE-401',
                    'propiedad_id' => 2,
                    'propiedad_nombre' => 'Apartamentos La Estanza - Torre Alta',
                    'tipo' => 'Penthouse Suite',
                    'nivel' => 'Nivel 4 (PH)',
                    'moneda' => 'USD',
                    'alquiler' => 950.00,
                    'deposito' => 950.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'Roberto Paiz (Demo)',
                    'inquilino_id' => 7,
                    'contrato_activo' => 'CON-2026-007',
                    'caracteristicas' => 'Terraza privada, acabados de lujo, 2 parqueos, aire acondicionado.'
                ],
                [
                    'id' => 9,
                    'codigo' => 'Adamant 204',
                    'propiedad_id' => 2,
                    'propiedad_nombre' => 'Edificio Adamant',
                    'tipo' => 'Apartamento 1 Habitación',
                    'nivel' => 'Nivel 2',
                    'moneda' => 'GTQ',
                    'alquiler' => 3500.00,
                    'deposito' => 3500.00,
                    'estado' => 'disponible',
                    'inquilino_actual' => 'none',
                    'inquilino_id' => null,
                    'contrato_activo' => 'CON-2026-008',
                    'caracteristicas' => 'Apartamento disponible con contrato por vencer o renovar.'
                ],
                [
                    'id' => 10,
                    'codigo' => 'El Dorm 227',
                    'propiedad_id' => 1,
                    'propiedad_nombre' => 'El Dorm',
                    'tipo' => 'Dorm Estudio Ejecutivo',
                    'nivel' => 'Nivel 2',
                    'moneda' => 'GTQ',
                    'alquiler' => 2400.00,
                    'deposito' => 2400.00,
                    'estado' => 'ocupado',
                    'inquilino_actual' => 'MOISES PARDO',
                    'inquilino_id' => 8,
                    'contrato_activo' => 'CON-2026-009',
                    'caracteristicas' => 'Dormitorio amueblado con baño privado, balcón y mesa de trabajo.'
                ]
            ];
        }
        return self::$apartamentos;
    }

    public static function getInquilinos(): array
    {
        if (self::$inquilinos === null) {
            self::$inquilinos = [
                [
                    'id' => 1,
                    'nombre' => 'Carlos Mendoza (Demo)',
                    'documento' => 'DPI 2548 10924 0101',
                    'telefono' => '+502 5551-0101',
                    'email' => 'carlos.mendoza.demo@laestanza.local',
                    'contacto_emergencia' => 'Luisa Mendoza (Madre) - Tel. 5551-9999',
                    'apartamento_codigo' => 'DORM-101',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-01-15',
                    'fiador_nombre' => 'Manuel Mendoza Santos',
                    'fiador_telefono' => '+502 5551-7788',
                    'fiador_dpi' => 'DPI 1890 44521 0101',
                    'fiador_relacion' => 'Padre',
                    'deposito_garantia' => 2200.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 2,
                    'nombre' => 'Sofia Morales (Demo)',
                    'documento' => 'DPI 3012 45891 0101',
                    'telefono' => '+502 5552-0202',
                    'email' => 'sofia.morales.demo@laestanza.local',
                    'contacto_emergencia' => 'Pedro Morales (Padre) - Tel. 5552-8888',
                    'apartamento_codigo' => 'DORM-102',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-06-01',
                    'fiador_nombre' => 'Elena Morales Méndez',
                    'fiador_telefono' => '+502 5552-3344',
                    'fiador_dpi' => 'DPI 2012 33491 0101',
                    'fiador_relacion' => 'Madre',
                    'deposito_garantia' => 2200.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 3,
                    'nombre' => 'Javier Quintana (Demo)',
                    'documento' => 'DPI 1984 72341 0101',
                    'telefono' => '+502 5553-0303',
                    'email' => 'javier.quintana.demo@laestanza.local',
                    'contacto_emergencia' => 'Carla Quintana (Hermana) - Tel. 5553-7777',
                    'apartamento_codigo' => 'DORM-103',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-02-01',
                    'fiador_nombre' => 'Andrés Quintana Ríos',
                    'fiador_telefono' => '+502 5553-6677',
                    'fiador_dpi' => 'DPI 1672 88123 0101',
                    'fiador_relacion' => 'Tío',
                    'deposito_garantia' => 3100.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 4,
                    'nombre' => 'Mariana Estrada (Demo)',
                    'documento' => 'DPI 2781 63452 0101',
                    'telefono' => '+502 5554-0404',
                    'email' => 'mariana.estrada.demo@laestanza.local',
                    'contacto_emergencia' => 'Jorge Estrada (Esposo) - Tel. 5554-6666',
                    'apartamento_codigo' => 'APT-201',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2024-11-01',
                    'fiador_nombre' => 'Jorge Estrada Solórzano',
                    'fiador_telefono' => '+502 5554-1122',
                    'fiador_dpi' => 'DPI 2341 66782 0101',
                    'fiador_relacion' => 'Esposo',
                    'deposito_garantia' => 3800.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 5,
                    'nombre' => 'Fernando Castillo (Demo)',
                    'documento' => 'DPI 2155 89123 0101',
                    'telefono' => '+502 5555-0505',
                    'email' => 'fernando.castillo.demo@laestanza.local',
                    'contacto_emergencia' => 'Claudia Castillo - Tel. 5555-5555',
                    'apartamento_codigo' => 'APT-202',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-08-01',
                    'fiador_nombre' => 'Claudia Castillo de León',
                    'fiador_telefono' => '+502 5555-4433',
                    'fiador_dpi' => 'DPI 1990 77123 0101',
                    'fiador_relacion' => 'Hermana',
                    'deposito_garantia' => 4800.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 6,
                    'nombre' => 'Valeria Ruiz (Demo)',
                    'documento' => 'Pasaporte A-4491029',
                    'telefono' => '+502 5556-0606',
                    'email' => 'valeria.ruiz.demo@laestanza.local',
                    'contacto_emergencia' => 'Esteban Ruiz (Hermano) - Tel. 5556-4444',
                    'apartamento_codigo' => 'APT-301',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-01-01',
                    'fiador_nombre' => 'Esteban Ruiz González',
                    'fiador_telefono' => '+502 5556-9900',
                    'fiador_dpi' => 'DPI 2110 55431 0101',
                    'fiador_relacion' => 'Hermano',
                    'deposito_garantia' => 650.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 7,
                    'nombre' => 'Roberto Paiz (Demo)',
                    'documento' => 'DPI 1672 90182 0101',
                    'telefono' => '+502 5557-0707',
                    'email' => 'roberto.paiz.demo@laestanza.local',
                    'contacto_emergencia' => 'Patricia Paiz - Tel. 5557-3333',
                    'apartamento_codigo' => 'SUITE-401',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2024-03-01',
                    'fiador_nombre' => 'Patricia Paiz de León',
                    'fiador_telefono' => '+502 5557-2211',
                    'fiador_dpi' => 'DPI 1540 88912 0101',
                    'fiador_relacion' => 'Esposa',
                    'deposito_garantia' => 1900.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ],
                [
                    'id' => 8,
                    'nombre' => 'MOISES PARDO',
                    'documento' => 'DPI 2841 99231 0101',
                    'telefono' => '+502 5558-8888',
                    'email' => 'moises.pardo.demo@laestanza.local',
                    'contacto_emergencia' => 'Elena Pardo (Esposa) - Tel. 5558-9999',
                    'apartamento_codigo' => 'El Dorm 227',
                    'estado' => 'activo',
                    'fecha_ingreso' => '2025-11-01',
                    'fiador_nombre' => 'Rigoberto Pardo García',
                    'fiador_telefono' => '+502 5558-7777',
                    'fiador_dpi' => 'DPI 1782 55412 0101',
                    'fiador_relacion' => 'Hermano',
                    'deposito_garantia' => 2400.00,
                    'deposito_devuelto' => 'No',
                    'dia_vencimiento' => 5
                ]
            ];
        }
        return self::$inquilinos;
    }

    public static function getContratos(): array
    {
        if (self::$contratos !== null) {
            return self::$contratos;
        }

        self::$contratos = [
                [
                    'id' => 1,
                    'codigo' => 'CON-2026-001',
                    'apartamento_id' => 1,
                    'apartamento_codigo' => 'DORM-101',
                    'inquilino_id' => 1,
                    'inquilino_nombre' => 'Carlos Mendoza (Demo)',
                    'fecha_inicio' => '2026-01-01',
                    'fecha_fin' => '2026-12-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 2200.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 2200.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Anual automática con preaviso 30 días',
                    'estado' => 'vigente',
                    'observaciones' => 'Contrato anual estándar de dormitorio individual.'
                ],
                [
                    'id' => 2,
                    'codigo' => 'CON-2026-002',
                    'apartamento_id' => 2,
                    'apartamento_codigo' => 'DORM-102',
                    'inquilino_id' => 2,
                    'inquilino_nombre' => 'Sofia Morales (Demo)',
                    'fecha_inicio' => '2026-01-01',
                    'fecha_fin' => '2026-12-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 2200.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 2200.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Revisión semestral',
                    'estado' => 'vigente',
                    'observaciones' => 'Paga por depósito bancario los primeros 5 días del mes.'
                ],
                [
                    'id' => 3,
                    'codigo' => 'CON-2026-003',
                    'apartamento_id' => 3,
                    'apartamento_codigo' => 'DORM-103',
                    'inquilino_id' => 3,
                    'inquilino_nombre' => 'Javier Quintana (Demo)',
                    'fecha_inicio' => '2026-01-01',
                    'fecha_fin' => '2026-12-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 3100.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 3100.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Renovación sujeta a revisión académica',
                    'estado' => 'vigente',
                    'observaciones' => 'Dorm compartido. En marzo realizó abono parcial.'
                ],
                [
                    'id' => 4,
                    'codigo' => 'CON-2026-004',
                    'apartamento_id' => 5,
                    'apartamento_codigo' => 'APT-201',
                    'inquilino_id' => 4,
                    'inquilino_nombre' => 'Mariana Estrada (Demo)',
                    'fecha_inicio' => '2025-11-01',
                    'fecha_fin' => '2026-10-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 3800.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 3800.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Renovación anual',
                    'estado' => 'vigente',
                    'observaciones' => 'Incluye tarjeta de acceso vehicular al parqueo techado.'
                ],
                [
                    'id' => 5,
                    'codigo' => 'CON-2026-005',
                    'apartamento_id' => 6,
                    'apartamento_codigo' => 'APT-202',
                    'inquilino_id' => 5,
                    'inquilino_nombre' => 'Fernando Castillo (Demo)',
                    'fecha_inicio' => '2025-08-01',
                    'fecha_fin' => '2026-07-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 4800.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 4800.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'A convenir al vencimiento',
                    'estado' => 'vigente',
                    'observaciones' => 'Vence en julio 2026. Requiere seguimiento de renovación en junio.'
                ],
                [
                    'id' => 6,
                    'codigo' => 'CON-2026-006',
                    'apartamento_id' => 7,
                    'apartamento_codigo' => 'APT-301',
                    'inquilino_id' => 6,
                    'inquilino_nombre' => 'Valeria Ruiz (Demo)',
                    'fecha_inicio' => '2026-01-01',
                    'fecha_fin' => '2026-12-31',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 650.00,
                    'moneda' => 'USD',
                    'deposito_garantia' => 650.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Automática en dólares',
                    'estado' => 'vigente',
                    'observaciones' => 'Contrato pactado en USD. Pagos mediante transferencia internacional.'
                ],
                [
                    'id' => 7,
                    'codigo' => 'CON-2026-007',
                    'apartamento_id' => 8,
                    'apartamento_codigo' => 'SUITE-401',
                    'inquilino_id' => 7,
                    'inquilino_nombre' => 'Roberto Paiz (Demo)',
                    'fecha_inicio' => '2025-03-01',
                    'fecha_fin' => '2027-02-28',
                    'plazo_meses' => 24,
                    'monto_alquiler' => 950.00,
                    'moneda' => 'USD',
                    'deposito_garantia' => 1900.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Plazo bianual',
                    'estado' => 'vigente',
                    'observaciones' => 'Penthouse. Depósito doble de garantía en custodia.'
                ],
                [
                    'id' => 8,
                    'codigo' => 'CON-2026-008',
                    'apartamento_id' => 9,
                    'apartamento_codigo' => 'Adamant 204',
                    'inquilino_id' => null,
                    'inquilino_nombre' => 'none',
                    'fecha_inicio' => '2025-10-04',
                    'fecha_fin' => '2026-10-03',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 3500.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 3500.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Vencimiento próximo (Revisar renovación)',
                    'estado' => 'por_vencer',
                    'observaciones' => 'Vence en menos de 24 horas. Pendiente firma de nuevo inquilino.'
                ],
                [
                    'id' => 9,
                    'codigo' => 'CON-2026-009',
                    'apartamento_id' => 10,
                    'apartamento_codigo' => 'El Dorm 227',
                    'inquilino_id' => 8,
                    'inquilino_nombre' => 'MOISES PARDO',
                    'fecha_inicio' => '2025-11-03',
                    'fecha_fin' => '2026-11-02',
                    'plazo_meses' => 12,
                    'monto_alquiler' => 2400.00,
                    'moneda' => 'GTQ',
                    'deposito_garantia' => 2400.00,
                    'estado_deposito' => 'custodiado',
                    'tipo_renovacion' => 'Renovación anual sujeta a confirmación',
                    'estado' => 'vigente',
                    'observaciones' => 'Vence en exactamente 31 días. Notificado para renovación.'
                ]
            ];

        return self::$contratos;
    }

    public static function getPagos(): array
    {
        if (self::$pagos === null) {
            // Pagos demostrativos para el Control Anual 2026
            self::$pagos = [
                // DORM-101 (Carlos Mendoza - Q2,200) - Pagado Enero, Febrero, Marzo, Abril
                [
                    'id' => 1,
                    'contrato_id' => 1,
                    'apartamento_codigo' => 'DORM-101',
                    'inquilino_nombre' => 'Carlos Mendoza (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-04',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-884910',
                    'estado' => 'pagado',
                    'observaciones' => 'Pago de renta puntual correspondiente a Enero 2026.'
                ],
                [
                    'id' => 2,
                    'contrato_id' => 1,
                    'apartamento_codigo' => 'DORM-101',
                    'inquilino_nombre' => 'Carlos Mendoza (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-03',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-102941',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026.'
                ],
                [
                    'id' => 3,
                    'contrato_id' => 1,
                    'apartamento_codigo' => 'DORM-101',
                    'inquilino_nombre' => 'Carlos Mendoza (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-05',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-901824',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Marzo 2026.'
                ],
                [
                    'id' => 4,
                    'contrato_id' => 1,
                    'apartamento_codigo' => 'DORM-101',
                    'inquilino_nombre' => 'Carlos Mendoza (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 4,
                    'fecha_pago' => '2026-04-02',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-144901',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Abril 2026.'
                ],

                // DORM-102 (Sofia Morales - Q2,200) - Enero y Febrero pagados, Marzo con mora registrada aparte
                [
                    'id' => 5,
                    'contrato_id' => 2,
                    'apartamento_codigo' => 'DORM-102',
                    'inquilino_nombre' => 'Sofia Morales (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-05',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-773412',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Enero 2026.'
                ],
                [
                    'id' => 6,
                    'contrato_id' => 2,
                    'apartamento_codigo' => 'DORM-102',
                    'inquilino_nombre' => 'Sofia Morales (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-04',
                    'monto' => 2200.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-802194',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026.'
                ],
                [
                    'id' => 7,
                    'contrato_id' => 2,
                    'apartamento_codigo' => 'DORM-102',
                    'inquilino_nombre' => 'Sofia Morales (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-12',
                    'monto' => 2200.00,
                    'mora' => 100.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-190241',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Marzo 2026 recibida con 7 días de mora. Mora de Q100 desglosada.'
                ],

                // DORM-103 (Javier Quintana - Q3,100) - Enero y Febrero pagados, Marzo PAGO PARCIAL (Q1,550 de Q3,100)
                [
                    'id' => 8,
                    'contrato_id' => 3,
                    'apartamento_codigo' => 'DORM-103',
                    'inquilino_nombre' => 'Javier Quintana (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-05',
                    'monto' => 3100.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-883100',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Enero 2026.'
                ],
                [
                    'id' => 9,
                    'contrato_id' => 3,
                    'apartamento_codigo' => 'DORM-103',
                    'inquilino_nombre' => 'Javier Quintana (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-05',
                    'monto' => 3100.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-904122',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026.'
                ],
                [
                    'id' => 10,
                    'contrato_id' => 3,
                    'apartamento_codigo' => 'DORM-103',
                    'inquilino_nombre' => 'Javier Quintana (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-08',
                    'monto' => 1550.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-219481',
                    'estado' => 'parcial',
                    'observaciones' => 'Primer abono de Marzo 2026 (50%). Pendiente saldo de Q1,550.00.'
                ],

                // APT-201 (Mariana Estrada - Q3,800)
                [
                    'id' => 11,
                    'contrato_id' => 4,
                    'apartamento_codigo' => 'APT-201',
                    'inquilino_nombre' => 'Mariana Estrada (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-02',
                    'monto' => 3800.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-001928',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Enero 2026 puntual.'
                ],
                [
                    'id' => 12,
                    'contrato_id' => 4,
                    'apartamento_codigo' => 'APT-201',
                    'inquilino_nombre' => 'Mariana Estrada (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-02',
                    'monto' => 3800.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-003841',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026.'
                ],
                [
                    'id' => 13,
                    'contrato_id' => 4,
                    'apartamento_codigo' => 'APT-201',
                    'inquilino_nombre' => 'Mariana Estrada (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-03',
                    'monto' => 3800.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Transferencia Bancaria',
                    'referencia' => 'TRF-005912',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Marzo 2026.'
                ],

                // APT-202 (Fernando Castillo - Q4,800)
                [
                    'id' => 14,
                    'contrato_id' => 5,
                    'apartamento_codigo' => 'APT-202',
                    'inquilino_nombre' => 'Fernando Castillo (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-05',
                    'monto' => 4800.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-480192',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Enero 2026.'
                ],
                [
                    'id' => 15,
                    'contrato_id' => 5,
                    'apartamento_codigo' => 'APT-202',
                    'inquilino_nombre' => 'Fernando Castillo (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-05',
                    'monto' => 4800.00,
                    'mora' => 0.00,
                    'moneda' => 'GTQ',
                    'metodo' => 'Depósito Bancario',
                    'referencia' => 'DEP-482019',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026.'
                ],

                // APT-301 (Valeria Ruiz - $650.00 USD)
                [
                    'id' => 16,
                    'contrato_id' => 6,
                    'apartamento_codigo' => 'APT-301',
                    'inquilino_nombre' => 'Valeria Ruiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-03',
                    'monto' => 650.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Internacional Wire',
                    'referencia' => 'WIRE-US91024',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Enero 2026 en USD.'
                ],
                [
                    'id' => 17,
                    'contrato_id' => 6,
                    'apartamento_codigo' => 'APT-301',
                    'inquilino_nombre' => 'Valeria Ruiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-03',
                    'monto' => 650.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Internacional Wire',
                    'referencia' => 'WIRE-US92831',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Febrero 2026 en USD.'
                ],
                [
                    'id' => 18,
                    'contrato_id' => 6,
                    'apartamento_codigo' => 'APT-301',
                    'inquilino_nombre' => 'Valeria Ruiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-04',
                    'monto' => 650.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Internacional Wire',
                    'referencia' => 'WIRE-US94410',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Marzo 2026 en USD.'
                ],

                // SUITE-401 (Roberto Paiz - $950.00 USD)
                [
                    'id' => 19,
                    'contrato_id' => 7,
                    'apartamento_codigo' => 'SUITE-401',
                    'inquilino_nombre' => 'Roberto Paiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 1,
                    'fecha_pago' => '2026-01-05',
                    'monto' => 950.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Bancaria USD',
                    'referencia' => 'TRF-USD-1102',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Penthouse Enero 2026.'
                ],
                [
                    'id' => 20,
                    'contrato_id' => 7,
                    'apartamento_codigo' => 'SUITE-401',
                    'inquilino_nombre' => 'Roberto Paiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 2,
                    'fecha_pago' => '2026-02-05',
                    'monto' => 950.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Bancaria USD',
                    'referencia' => 'TRF-USD-1198',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Penthouse Febrero 2026.'
                ],
                [
                    'id' => 21,
                    'contrato_id' => 7,
                    'apartamento_codigo' => 'SUITE-401',
                    'inquilino_nombre' => 'Roberto Paiz (Demo)',
                    'anio' => 2026,
                    'mes_periodo' => 3,
                    'fecha_pago' => '2026-03-06',
                    'monto' => 950.00,
                    'mora' => 0.00,
                    'moneda' => 'USD',
                    'metodo' => 'Transferencia Bancaria USD',
                    'referencia' => 'TRF-USD-1254',
                    'estado' => 'pagado',
                    'observaciones' => 'Renta Penthouse Marzo 2026.'
                ]
            ];
        }
        return self::$pagos;
    }

    public static function getGastos(): array
    {
        if (self::$gastos === null) {
            self::$gastos = [
                [
                    'id' => 1,
                    'fecha' => '2026-01-10',
                    'categoria' => 'Servicios',
                    'propiedad_ubicacion' => 'Dorms La Estanza - Módulo Central',
                    'apartamento_id' => null,
                    'apartamento_codigo' => 'General',
                    'moneda' => 'GTQ',
                    'monto' => 1450.00,
                    'descripcion' => 'Pago mensual servicio de Internet simétrico fibra óptica y agua comunal.',
                    'referencia' => 'FAC-E-88192'
                ],
                [
                    'id' => 2,
                    'fecha' => '2026-01-18',
                    'categoria' => 'Mantenimiento',
                    'propiedad_ubicacion' => 'Apartamentos La Estanza - Torre Alta',
                    'apartamento_id' => 5,
                    'apartamento_codigo' => 'APT-201',
                    'moneda' => 'GTQ',
                    'monto' => 380.00,
                    'descripcion' => 'Reemplazo de grifería de lavamanos y empaques en baño principal.',
                    'referencia' => 'FAC-PLUMB-102'
                ],
                [
                    'id' => 3,
                    'fecha' => '2026-02-12',
                    'categoria' => 'Reparaciones',
                    'propiedad_ubicacion' => 'Dorms La Estanza - Módulo Central',
                    'apartamento_id' => 4,
                    'apartamento_codigo' => 'DORM-104',
                    'moneda' => 'GTQ',
                    'monto' => 850.00,
                    'descripcion' => 'Pintura general y preparación de habitación desocupada para nuevo inquilino.',
                    'referencia' => 'REC-PINT-554'
                ],
                [
                    'id' => 4,
                    'fecha' => '2026-02-20',
                    'categoria' => 'Compras',
                    'propiedad_ubicacion' => 'Dorms La Estanza - Módulo Central',
                    'apartamento_id' => null,
                    'apartamento_codigo' => 'General',
                    'moneda' => 'GTQ',
                    'monto' => 620.00,
                    'descripcion' => 'Insumos de limpieza para áreas comunes, pasillos y lavandería comunitaria.',
                    'referencia' => 'FAC-CLEAN-9901'
                ],
                [
                    'id' => 5,
                    'fecha' => '2026-03-05',
                    'categoria' => 'Mantenimiento',
                    'propiedad_ubicacion' => 'Apartamentos La Estanza - Torre Alta',
                    'apartamento_id' => 8,
                    'apartamento_codigo' => 'SUITE-401',
                    'moneda' => 'USD',
                    'monto' => 120.00,
                    'descripcion' => 'Mantenimiento preventivo semestral de unidad central de aire acondicionado (HVAC).',
                    'referencia' => 'INV-HVAC-330'
                ]
            ];
        }
        return self::$gastos;
    }

    public static function getEstadoCuentaInquilino(int|string $inquilinoId): ?array
    {
        $id = (int) $inquilinoId;
        $inquilino = null;
        foreach (self::getInquilinos() as $inq) {
            if ($inq['id'] === $id) {
                $inquilino = $inq;
                break;
            }
        }
        if (!$inquilino) {
            return null;
        }

        // Buscar apartamento asociado
        $apartamento = null;
        foreach (self::getApartamentos() as $apto) {
            if ($apto['codigo'] === $inquilino['apartamento_codigo']) {
                $apartamento = $apto;
                break;
            }
        }

        // Buscar contrato activo
        $contrato = null;
        foreach (self::getContratos() as $con) {
            if ($con['inquilino_id'] === $id || ($apartamento && $con['apartamento_id'] === $apartamento['id'])) {
                $contrato = $con;
                break;
            }
        }

        $moneda = $apartamento['moneda'] ?? ($contrato['moneda'] ?? 'GTQ');
        $rentaBase = (float) ($contrato['monto_alquiler'] ?? ($apartamento['alquiler'] ?? 2200.00));
        $depositoGarantia = (float) ($inquilino['deposito_garantia'] ?? ($contrato['deposito_garantia'] ?? $rentaBase));
        $depositoDevuelto = $inquilino['deposito_devuelto'] ?? 'No';

        // Buscar pagos del inquilino / apartamento
        $pagosInquilino = [];
        foreach (self::getPagos() as $p) {
            if ($p['apartamento_codigo'] === $inquilino['apartamento_codigo'] || (isset($p['contrato_id']) && $contrato && $p['contrato_id'] === $contrato['id'])) {
                $pagosInquilino[] = $p;
            }
        }

        // Ordenar pagos por mes_periodo
        usort($pagosInquilino, fn($a, $b) => ($a['mes_periodo'] ?? 0) <=> ($b['mes_periodo'] ?? 0));

        // Construir libro de movimientos cronológico
        $movimientos = [];
        $runningBalance = 0.0;
        $totalCargos = 0.0;
        $totalPagos = 0.0;
        $totalMora = 0.0;

        // Movimiento inicial: Depósito de garantía en custodia (separado de pagos de renta)
        $fechaInicio = $contrato['fecha_inicio'] ?? ($inquilino['fecha_ingreso'] ?? '2026-01-01');
        $movimientos[] = [
            'fecha' => $fechaInicio,
            'concepto' => 'Depósito de Garantía (Fondo en custodia)',
            'tipo' => 'Garantía',
            'debito' => 0.00,
            'credito' => $depositoGarantia,
            'saldo' => 0.00,
            'referencia' => 'DEP-GAR-' . ($contrato['codigo'] ?? '001'),
            'metodo' => 'Depósito Bancario',
            'estado' => ($depositoDevuelto === 'Sí') ? 'Devuelto' : 'Custodiado'
        ];

        $mesesNombres = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        // Se simula la facturación hasta el mes de octubre (mes actual 2026)
        $mesMaximo = 10;
        foreach ($pagosInquilino as $p) {
            if (($p['mes_periodo'] ?? 0) > $mesMaximo) {
                $mesMaximo = (int) $p['mes_periodo'];
            }
        }

        for ($m = 1; $m <= $mesMaximo; $m++) {
            $nombreMes = $mesesNombres[$m] ?? "Mes $m";
            $fechaCargo = sprintf('2026-%02d-01', $m);

            // 1. Cargo de Renta Mensual
            $runningBalance += $rentaBase;
            $totalCargos += $rentaBase;
            $movimientos[] = [
                'fecha' => $fechaCargo,
                'concepto' => "Cargo de Renta Mensual - {$nombreMes} 2026",
                'tipo' => 'Cargo',
                'debito' => $rentaBase,
                'credito' => 0.00,
                'saldo' => $runningBalance,
                'referencia' => sprintf('FAC-RENTA-2026-%02d', $m),
                'metodo' => 'Facturación Mensual',
                'estado' => 'Facturado'
            ];

            // 2. Pagos / Abonos del mes
            $pagosMes = array_values(array_filter($pagosInquilino, fn($p) => (int) ($p['mes_periodo'] ?? 0) === $m));
            if (!empty($pagosMes)) {
                foreach ($pagosMes as $pm) {
                    $montoPagado = (float) $pm['monto'];
                    $runningBalance -= $montoPagado;
                    $totalPagos += $montoPagado;

                    $movimientos[] = [
                        'fecha' => $pm['fecha_pago'],
                        'concepto' => "Abono / Pago de Renta {$nombreMes} 2026 ({$pm['metodo']})",
                        'tipo' => 'Abono',
                        'debito' => 0.00,
                        'credito' => $montoPagado,
                        'saldo' => $runningBalance,
                        'referencia' => $pm['referencia'],
                        'metodo' => $pm['metodo'],
                        'estado' => ($runningBalance <= 0.01) ? 'Pagado' : 'Abono Parcial'
                    ];

                    // Si hubo mora registrada
                    $mora = (float) ($pm['mora'] ?? 0.0);
                    if ($mora > 0) {
                        $runningBalance += $mora;
                        $totalCargos += $mora;
                        $totalMora += $mora;
                        $movimientos[] = [
                            'fecha' => $pm['fecha_pago'],
                            'concepto' => "Recargo de Mora por Pago Extemporáneo ({$nombreMes})",
                            'tipo' => 'Cargo',
                            'debito' => $mora,
                            'credito' => 0.00,
                            'saldo' => $runningBalance,
                            'referencia' => 'MORA-' . $pm['referencia'],
                            'metodo' => 'Recargo Administrativo',
                            'estado' => 'Aplicado'
                        ];

                        $runningBalance -= $mora;
                        $totalPagos += $mora;
                        $movimientos[] = [
                            'fecha' => $pm['fecha_pago'],
                            'concepto' => "Pago de Recargo de Mora ({$nombreMes})",
                            'tipo' => 'Abono',
                            'debito' => 0.00,
                            'credito' => $mora,
                            'saldo' => $runningBalance,
                            'referencia' => $pm['referencia'] . '-M',
                            'metodo' => $pm['metodo'],
                            'estado' => 'Pagado'
                        ];
                    }
                }
            }
        }

        return [
            'inquilino' => $inquilino,
            'apartamento' => $apartamento,
            'contrato' => $contrato,
            'resumen' => [
                'moneda' => $moneda,
                'renta_mensual' => $rentaBase,
                'total_cargos' => round($totalCargos, 2),
                'total_pagos' => round($totalPagos, 2),
                'total_mora' => round($totalMora, 2),
                'saldo_pendiente' => round($runningBalance, 2),
                'deposito_garantia' => round($depositoGarantia, 2),
                'deposito_devuelto' => $depositoDevuelto,
                'estado_cuenta' => ($runningBalance <= 0.01) ? 'Al Día' : 'Saldo Pendiente',
                'estado_cuenta_clase' => ($runningBalance <= 0.01) ? 'success' : 'danger'
            ],
            'movimientos' => $movimientos
        ];
    }

    public static function getContratosPorVencer(int $dias = 30): array
    {
        // Contratos confirmados por los usuarios en la imagen img6.png
        $contratos = [
            [
                'apartamento_codigo' => 'Adamant 204',
                'inquilino_nombre' => 'none',
                'fecha_vence' => '2026-10-03',
                'dias_restantes' => 1,
                'urgencia' => 'critico'
            ],
            [
                'apartamento_codigo' => 'El Dorm 227',
                'inquilino_nombre' => 'MOISES PARDO',
                'fecha_vence' => '2026-11-02',
                'dias_restantes' => 31,
                'urgencia' => 'aviso'
            ]
        ];

        return array_values(array_filter($contratos, fn(array $c): bool => $c['dias_restantes'] <= max($dias, 31)));
    }
}
