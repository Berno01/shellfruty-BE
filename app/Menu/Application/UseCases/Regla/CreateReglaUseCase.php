<?php

namespace App\Menu\Application\UseCases\Regla;

use App\Menu\Application\DTOs\ReglaDTO;
use App\Menu\Domain\Models\Regla;
use App\Menu\Application\Repositories\ReglaRepositoryInterface;

class CreateReglaUseCase
{
    public function __construct(
        private ReglaRepositoryInterface $repository
    ) {}

    public function execute(ReglaDTO $dto): Regla
    {
        return $this->repository->create($dto->toArray(), $dto->getDetalles());
    }
}
