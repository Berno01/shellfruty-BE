<?php

namespace App\Menu\Application\UseCases\CategoriaMenu;

use App\Menu\Application\DTOs\CategoriaMenuDTO;
use App\Menu\Domain\Models\CategoriaMenu;
use App\Menu\Application\Repositories\CategoriaMenuRepositoryInterface;

class UpdateCategoriaMenuUseCase
{
    public function __construct(
        private CategoriaMenuRepositoryInterface $repository
    ) {}

    public function execute(int $id, CategoriaMenuDTO $dto): ?CategoriaMenu
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
