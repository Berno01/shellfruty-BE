<?php

namespace App\Menu\Application\UseCases\Menu;

use App\Menu\Application\DTOs\MenuDTO;
use App\Menu\Domain\Models\Menu;
use App\Menu\Application\Repositories\MenuRepositoryInterface;

class CreateMenuUseCase
{
    public function __construct(
        private MenuRepositoryInterface $repository
    ) {}

    public function execute(MenuDTO $dto): Menu
    {
        return $this->repository->create($dto->toArray());
    }
}
