<?php

namespace App\Menu\Application\UseCases\Menu;

use App\Menu\Application\Repositories\MenuRepositoryInterface;

class DeleteMenuUseCase
{
    public function __construct(
        private MenuRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
