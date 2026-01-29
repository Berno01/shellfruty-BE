<?php

namespace App\Venta\Application\Repositories;

use App\Venta\Domain\Models\Venta;
use Illuminate\Database\Eloquent\Collection;

interface VentaRepositoryInterface
{
    public function findByDateRange(string $fechaInicio, string $fechaFin, ?int $idSucursal = null): Collection;
    
    public function findById(int $id): ?Venta;
    
    public function create(array $ventaData, array $detalles, int $userId): Venta;
    
    public function update(int $id, array $ventaData, array $detalles, int $userId): ?Venta;
    
    public function cancel(int $id): bool;
    
    public function enviar(int $id): bool;
    
    public function getUserRole(int $userId): ?int;
}
