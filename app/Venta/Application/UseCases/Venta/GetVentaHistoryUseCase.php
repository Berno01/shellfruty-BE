<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\Repositories\VentaRepositoryInterface;

class GetVentaHistoryUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?array
    {
        return $this->repository->getHistoryByVentaId($id);
    }
}
