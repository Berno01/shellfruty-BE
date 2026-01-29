<?php

namespace App\Menu\Application\UseCases\Regla;

use App\Menu\Application\Repositories\ReglaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListReglasByCategoriaUseCase
{
    public function __construct(
        private ReglaRepositoryInterface $repository
    ) {}

    public function execute(int $idCategoria): Collection
    {
        return $this->repository->findByCategoria($idCategoria);
    }
}
