<?php

namespace App\Menu\Application\UseCases\Ingrediente;

use App\Menu\Application\Repositories\IngredienteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListIngredientesByCategoriaUseCase
{
    public function __construct(
        private IngredienteRepositoryInterface $repository
    ) {}

    public function execute(int $idCategoria): Collection
    {
        return $this->repository->findByCategoria($idCategoria);
    }
}
