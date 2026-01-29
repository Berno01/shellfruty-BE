<?php

namespace App\Menu\Application\UseCases\CategoriaMenu;

use App\Menu\Application\DTOs\CategoriaMenuDTO;
use App\Menu\Domain\Models\CategoriaMenu;
use App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface;

class CreateCategoriaMenuUseCase
{
    public function __construct(
        private CategoriaMenuRepositoryInterface $repository
    ) {}

    public function execute(CategoriaMenuDTO $dto): CategoriaMenu
    {
        return $this->repository->create($dto->toArray());
    }
}
