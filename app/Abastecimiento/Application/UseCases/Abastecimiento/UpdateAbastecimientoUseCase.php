<?php

namespace App\Abastecimiento\Application\UseCases\Abastecimiento;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;
use App\Abastecimiento\Application\DTOs\AbastecimientoDTO;
use App\Abastecimiento\Domain\Models\Abastecimiento;

class UpdateAbastecimientoUseCase
{
    private AbastecimientoRepositoryInterface $repository;

    public function __construct(AbastecimientoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id, AbastecimientoDTO $dto, int $userId): ?Abastecimiento
    {
        return $this->repository->update($id, $dto->toArray(), $userId);
    }
}
