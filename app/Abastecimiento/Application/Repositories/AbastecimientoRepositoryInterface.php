<?php

namespace App\Abastecimiento\Application\Repositories;

use App\Abastecimiento\Domain\Models\Abastecimiento;

interface AbastecimientoRepositoryInterface
{
    public function findAll(string $fecha, ?int $idSucursal);
    
    public function findById(int $id): ?Abastecimiento;
    
    public function create(array $data, int $userId): Abastecimiento;
    
    public function update(int $id, array $data, int $userId): ?Abastecimiento;
    
    public function delete(int $id): bool;
    
    public function getResumen(string $fecha, ?int $idSucursal): array;

    public function getSaldo(string $fecha, ?int $idSucursal): array;
}
