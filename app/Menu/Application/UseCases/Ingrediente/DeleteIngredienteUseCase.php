<?php

namespace App\Menu\Application\UseCases\Ingrediente;

use App\Menu\Application\Repositories\IngredienteRepositoryInterface;

class DeleteIngredienteUseCase
{
    public function __construct(
        private IngredienteRepositoryInterface $repository
    ) {}

    public function execute(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
