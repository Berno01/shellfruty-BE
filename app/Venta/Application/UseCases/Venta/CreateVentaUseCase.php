<?php

namespace App\Venta\Application\UseCases\Venta;

use App\Venta\Application\DTOs\VentaDTO;
use App\Venta\Domain\Models\Venta;
use App\Venta\Application\Repositories\VentaRepositoryInterface;

class CreateVentaUseCase
{
    public function __construct(
        private VentaRepositoryInterface $repository
    ) {}

    public function execute(VentaDTO $dto, int $userId): Venta
    {
        return $this->repository->create($dto->toArray(), $dto->getDetalles(), $userId);
    }

    public function validateUserRole(int $userId, array $detalles): bool
    {
        $idRol = $this->repository->getUserRole($userId);
        
        if (!$idRol) {
            return false;
        }

        // Si es bot (id_rol = 3), ya no es obligatorio tener personalizaciones

        return true;
    }
}
