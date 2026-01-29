<?php

namespace App\Menu\Application\UseCases\Ingrediente;

use App\Menu\Application\DTOs\IngredienteDTO;
use App\Menu\Domain\Models\Ingrediente;
use App\Menu\Application\Repositories\IngredienteRepositoryInterface;

class UpdateIngredienteUseCase
{
    public function __construct(
        private IngredienteRepositoryInterface $repository
    ) {}

    public function execute(int $id, IngredienteDTO $dto): ?Ingrediente
    {
        return $this->repository->update($id, $dto->toArray());
    }
}
