<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\Repositories\VentaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListVentasUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(string $fechaInicio, string $fechaFin, ?int $idSucursal = null): Collection
    {
        return $this->repository->findByDateRange($fechaInicio, $fechaFin, $idSucursal);
    }
}
