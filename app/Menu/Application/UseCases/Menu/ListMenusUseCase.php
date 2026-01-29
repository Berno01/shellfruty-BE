<?php

namespace App\Menu\Application\UseCases\Menu;

use App\Menu\Application\Repositories\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ListMenusUseCase
{
    public function __construct(
        private MenuRepositoryInterface $repository
    ) {}

    public function execute(): Collection
    {
        return $this->repository->findAll();
    }
}
