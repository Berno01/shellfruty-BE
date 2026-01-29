<?php

namespace App\Menu\Application\UseCases\Regla;

use App\Menu\Application\Repositories\ReglaRepositoryInterface;

class DeleteReglaUseCase
{
    public function __construct(
        private ReglaRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
