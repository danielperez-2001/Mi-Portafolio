<?php

namespace App\Models;

class PagoModel
{
    public function todos(): array
    {
        return DataDemo::getPagos();
    }

    public function buscarPorId(int|string $id): ?array
    {
        $id = (int) $id;
        foreach (DataDemo::getPagos() as $pago) {
            if ($pago['id'] === $id) {
                return $pago;
            }
        }
        return null;
    }

    public function buscarPorReferenciaDemostracion(string $referencia): ?array
    {
        $refBuscada = strtoupper(trim($referencia));
        foreach (DataDemo::getPagos() as $pago) {
            if (strtoupper($pago['referencia']) === $refBuscada) {
                return $pago;
            }
        }
        return null;
    }

    public function filtrar(array $criterios = []): array
    {
        $lista = DataDemo::getPagos();

        return array_values(array_filter($lista, function ($pago) use ($criterios) {
            if (!empty($criterios['moneda']) && $pago['moneda'] !== $criterios['moneda']) {
                return false;
            }
            if (!empty($criterios['estado']) && $pago['estado'] !== $criterios['estado']) {
                return false;
            }
            if (!empty($criterios['mes']) && (int) $pago['mes_periodo'] !== (int) $criterios['mes']) {
                return false;
            }
            if (!empty($criterios['anio']) && (int) $pago['anio'] !== (int) $criterios['anio']) {
                return false;
            }
            if (!empty($criterios['apartamento_codigo']) && strtoupper($pago['apartamento_codigo']) !== strtoupper($criterios['apartamento_codigo'])) {
                return false;
            }
            if (!empty($criterios['busqueda'])) {
                $q = strtolower(trim($criterios['busqueda']));
                $matchApto = str_contains(strtolower($pago['apartamento_codigo']), $q);
                $matchInq = str_contains(strtolower($pago['inquilino_nombre']), $q);
                $matchRef = str_contains(strtolower($pago['referencia']), $q);
                if (!$matchApto && !$matchInq && !$matchRef) {
                    return false;
                }
            }
            return true;
        }));
    }
}
