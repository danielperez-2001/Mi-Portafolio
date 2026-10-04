<?php

namespace App\Models;

class GastoModel
{
    public function todos(): array
    {
        return DataDemo::getGastos();
    }

    public function buscarPorId(int|string $id): ?array
    {
        $id = (int) $id;
        foreach (DataDemo::getGastos() as $gasto) {
            if ($gasto['id'] === $id) {
                return $gasto;
            }
        }
        return null;
    }

    public function buscarPorReferenciaDemostracion(string $ref): ?array
    {
        $refBuscada = strtoupper(trim($ref));
        foreach (DataDemo::getGastos() as $gasto) {
            if (strtoupper($gasto['referencia']) === $refBuscada) {
                return $gasto;
            }
        }
        return null;
    }

    public function filtrar(array $criterios = []): array
    {
        $lista = DataDemo::getGastos();

        return array_values(array_filter($lista, function ($g) use ($criterios) {
            if (!empty($criterios['categoria']) && $g['categoria'] !== $criterios['categoria']) {
                return false;
            }
            if (!empty($criterios['moneda']) && $g['moneda'] !== $criterios['moneda']) {
                return false;
            }
            if (!empty($criterios['busqueda'])) {
                $q = strtolower(trim($criterios['busqueda']));
                $matchDesc = str_contains(strtolower($g['descripcion']), $q);
                $matchRef = str_contains(strtolower($g['referencia']), $q);
                $matchCat = str_contains(strtolower($g['categoria']), $q);
                if (!$matchDesc && !$matchRef && !$matchCat) {
                    return false;
                }
            }
            return true;
        }));
    }
}
