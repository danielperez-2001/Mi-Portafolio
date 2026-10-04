<?php

namespace App\Models;

class ContratoModel
{
    public function todos(): array
    {
        return DataDemo::getContratos();
    }

    public function buscarPorId(int|string $id): ?array
    {
        $id = (int) $id;
        foreach (DataDemo::getContratos() as $con) {
            if ($con['id'] === $id) {
                return $con;
            }
        }
        return null;
    }

    public function buscarPorCodigoDemostracion(string $codigo): ?array
    {
        $codigoBuscado = strtoupper(trim($codigo));
        foreach (DataDemo::getContratos() as $con) {
            if (strtoupper($con['codigo']) === $codigoBuscado) {
                return $con;
            }
        }
        return null;
    }

    public function buscarPorApartamento(int|string $apartamentoId): array
    {
        $apartamentoId = (int) $apartamentoId;
        return array_values(array_filter(DataDemo::getContratos(), function ($con) use ($apartamentoId) {
            return $con['apartamento_id'] === $apartamentoId;
        }));
    }
}
