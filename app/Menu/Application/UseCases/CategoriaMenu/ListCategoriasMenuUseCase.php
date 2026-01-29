<?php

namespace App\Menu\Application\UseCases\CategoriaMenu;

use App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListCategoriasMenuUseCase
{
    public function __construct(
        private CategoriaMenuRepositoryInterface $repository
    ) {}

    public function execute(): Collection
    {
        return $this->repository->findAll();
    }
}
