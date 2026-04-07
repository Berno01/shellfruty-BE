<?php

namespace App\BotCatalog\Application\UseCases\BotCatalog;

use App\BotCatalog\Application\Repositories\BotCatalogRepositoryInterface;

class GetBotCatalogUseCase
{
    private BotCatalogRepositoryInterface $repository;

    public function __construct(BotCatalogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(): array
    {
        return $this->repository->getCatalog();
    }
}
