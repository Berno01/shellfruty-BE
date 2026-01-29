<?php

namespace App\Sucursal\Application\UseCases;

use App\Sucursal\Application\Repositories\SucursalRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListSucursalesUseCase
{
    public function __construct(
        private SucursalRepositoryInterface $repository
    ) {}

    public function execute(): Collection
    {
        return $this->repository->getAll();
    }
}
