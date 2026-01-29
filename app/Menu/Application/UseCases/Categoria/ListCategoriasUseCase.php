<?php

namespace App\Menu\Application\UseCases\Categoria;

use App\Menu\Application\Repositories\CategoriaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListCategoriasUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $repository
    ) {}

    public function execute(): Collection
    {
        return $this->repository->findAll();
    }
}
