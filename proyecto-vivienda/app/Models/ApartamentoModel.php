<?php

namespace App\Models;

class ApartamentoModel
{
    public function todos(): array
    {
        return DataDemo::getApartamentos();
    }

    public function buscarPorId(int|string $id): ?array
    {
        $id = (int) $id;
        foreach (DataDemo::getApartamentos() as $apto) {
            if ($apto['id'] === $id) {
                return $apto;
            }
        }
        return null;
    }

    public function buscarPorCodigoDemostracion(string $codigo): ?array
    {
        $codigoBuscado = strtoupper(trim($codigo));
        foreach (DataDemo::getApartamentos() as $apto) {
            if (strtoupper($apto['codigo']) === $codigoBuscado) {
                return $apto;
            }
        }
        return null;
    }

    public function filtrar(array $criterios = []): array
    {
        $lista = DataDemo::getApartamentos();

        return array_values(array_filter($lista, function ($apto) use ($criterios) {
            if (!empty($criterios['propiedad_id']) && $apto['propiedad_id'] != $criterios['propiedad_id']) {
                return false;
            }
            if (!empty($criterios['moneda']) && $apto['moneda'] !== $criterios['moneda']) {
                return false;
            }
            if (!empty($criterios['estado']) && $apto['estado'] !== $criterios['estado']) {
                return false;
            }
            if (!empty($criterios['busqueda'])) {
                $q = strtolower(trim($criterios['busqueda']));
                $matchCodigo = str_contains(strtolower($apto['codigo']), $q);
                $matchTipo = str_contains(strtolower($apto['tipo']), $q);
                $matchInquilino = !empty($apto['inquilino_actual']) && str_contains(strtolower($apto['inquilino_actual']), $q);
                if (!$matchCodigo && !$matchTipo && !$matchInquilino) {
                    return false;
                }
            }
            return true;
        }));
    }
}
