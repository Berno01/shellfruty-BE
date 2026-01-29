<?php

namespace App\Menu\Application\UseCases\Categoria;

use App\Menu\Application\Repositories\CategoriaRepositoryInterface;

class DeleteCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
