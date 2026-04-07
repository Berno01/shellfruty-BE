<?php

namespace App\Abastecimiento\Application\UseCases\Abastecimiento;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;
use App\Abastecimiento\Domain\Models\Abastecimiento;

class GetAbastecimientoUseCase
{
    private AbastecimientoRepositoryInterface $repository;

    public function __construct(AbastecimientoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): ?Abastecimiento
    {
        return $this->repository->findById($id);
    }
}
