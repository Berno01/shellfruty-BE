<?php

namespace App\Menu\Application\UseCases\Categoria;

use App\Menu\Application\DTOs\CategoriaDTO;
use App\Menu\Domain\Models\Categoria;
use App\Menu\Application\Repositories\CategoriaRepositoryInterface;

class CreateCategoriaUseCase
{
    public function __construct(
        private CategoriaRepositoryInterface $repository
    ) {}

    public function execute(CategoriaDTO $dto): Categoria
    {
        return $this->repository->create($dto->toArray());
    }
}
