<?php

namespace App\Abastecimiento\Application\UseCases\Abastecimiento;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;
use App\Abastecimiento\Application\DTOs\AbastecimientoDTO;
use App\Abastecimiento\Domain\Models\Abastecimiento;

class CreateAbastecimientoUseCase
{
    private AbastecimientoRepositoryInterface $repository;

    public function __construct(AbastecimientoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(AbastecimientoDTO $dto, int $userId): Abastecimiento
    {
        return $this->repository->create($dto->toArray(), $userId);
    }
}
