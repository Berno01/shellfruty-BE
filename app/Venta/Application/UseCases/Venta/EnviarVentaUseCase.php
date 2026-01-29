<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\Repositories\VentaRepositoryInterface;

class EnviarVentaUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->enviar($id);
    }

    public function isAdmin(int $userId): bool
    {
        $idRol = $this->repository->getUserRole($userId);
        return $idRol === 1;
    }
}
