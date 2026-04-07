<?php

namespace App\Abastecimiento\Application\UseCases\Abastecimiento;

use App\Abastecimiento\Application\Repositories\AbastecimientoRepositoryInterface;

class ListAbastecimientosUseCase
{
    private AbastecimientoRepositoryInterface $repository;

    public function __construct(AbastecimientoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $fecha, ?int $idSucursal)
    {
        return $this->repository->findAll($fecha, $idSucursal);
    }
}
