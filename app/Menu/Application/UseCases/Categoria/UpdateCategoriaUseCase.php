<?php

namespace App\Menu\Application\UseCases\Categoria;

use App\Menu\Application\DTOs\CategoriaDTO;
use App\Menu\Domain\Models\Categoria;
use App\Menu\Application\Repositories\CategoriaRepositoryInterface;

class UpdateCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $repository
    ) {}

    public function execute(int $id, CategoriaDTO $dto): ?Categoria
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
