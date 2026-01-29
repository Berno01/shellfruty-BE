<?php

namespace App\Menu\Application\UseCases\Menu;

use App\Menu\Domain\Models\Menu;
use App\Menu\Application\Repositories\MenuRepositoryInterface;

class GetMenuWithReglasUseCase
{
    public function __construct(
        private MenuRepositoryInterface $repository
    ) {}

    public function execute(int $id): ?Menu
    {
        return $this->repository->findByIdWithReglas($id);
    }
}
