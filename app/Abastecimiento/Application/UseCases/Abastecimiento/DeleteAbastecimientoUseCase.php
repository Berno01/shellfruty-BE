<?php

namespace App\Abastecimiento\Application\UseCases\Abastecimiento;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;

class DeleteAbastecimientoUseCase
{
    private AbastecimientoRepositoryInterface $repository;

    public function __construct(AbastecimientoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
