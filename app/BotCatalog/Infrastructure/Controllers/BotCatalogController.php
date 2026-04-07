<?php

namespace App\BotCatalog\Infrastructure\Controllers;

use App\BotCatalog\Application\UseCases\BotCatalog\GetBotCatalogUseCase;
use Illuminate\Http\JsonResponse;

class BotCatalogController
{
    private GetBotCatalogUseCase $getBotCatalogUseCase;

    public function __construct(GetBotCatalogUseCase $getBotCatalogUseCase)
    {
        $this->getBotCatalogUseCase = $getBotCatalogUseCase;
    }

    public function getCatalog(): JsonResponse
    {
        $data = $this->getBotCatalogUseCase->execute();
        return response()->json($data);
    }
}
