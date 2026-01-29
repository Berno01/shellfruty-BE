<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Domain\Models\Venta;
use App\Venta\Application\Repositories\VentaRepositoryInterface;

class GetVentaUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?Venta
    {
        return $this->repository->findById($id);
    }
}
