<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\Repositories\VentaRepositoryInterface;

class CancelVentaUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->cancel($id);
    }

    public function isAdmin(int $userId): bool
    {
        $idRol = $this->repository->getUserRole($userId);
        return $idRol === 1;
    }
}
