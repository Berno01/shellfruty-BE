<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\DTOs\VentaDTO;
use App\Venta\Domain\Models\Venta;
use App\Venta\Application\Repositories\VentaRepositoryInterface;

class UpdateVentaUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(int $id, VentaDTO $dto, int $userId): ?Venta
    {
        return $this->repository->update($id, $dto->toArray(), $dto->getDetalles(), $userId);
    }

    public function isAdmin(int $userId): bool
    {
        $idRol = $this->repository->getUserRole($userId);
        return $idRol === 1;
    }
}
