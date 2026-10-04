<?php

namespace App\Models;

class InquilinoModel
{
    public function todos(): array
    {
        return DataDemo::getInquilinos();
    }

    public function buscarPorId(int|string $id): ?array
    {
        $id = (int) $id;
        foreach (DataDemo::getInquilinos() as $inq) {
            if ($inq['id'] === $id) {
                return $inq;
            }
        }
        return null;
    }

    public function buscarPorTerminoDemostracion(string $termino): array
    {
        $q = strtolower(trim($termino));
        if ($q === '') {
            return [];
        }

        return array_values(array_filter(DataDemo::getInquilinos(), function ($inq) use ($q) {
            return str_contains(strtolower($inq['nombre']), $q) ||
                   str_contains(strtolower($inq['documento']), $q) ||
                   str_contains(strtolower($inq['apartamento_codigo']), $q);
        }));
    }

    public function obtenerEstadoCuenta(int|string $inquilinoId): ?array
    {
        return DataDemo::getEstadoCuentaInquilino($inquilinoId);
    }
}
