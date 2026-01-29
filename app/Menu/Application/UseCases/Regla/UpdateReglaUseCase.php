<?php

namespace App\Menu\Application\UseCases\Regla;

use App\Menu\Application\DTOs\ReglaDTO;
use App\Menu\Domain\Models\Regla;
use App\Menu\Application\Repositories\ReglaRepositoryInterface;

class UpdateReglaUseCase
{
    public function __construct(
        private ReglaRepositoryInterface $repository
    ) {}

    public function execute(int $id, ReglaDTO $dto): ?Regla
    {
        return $this->repository->update($id, $dto->toArray(), $dto->getDetalles());
    }
}
