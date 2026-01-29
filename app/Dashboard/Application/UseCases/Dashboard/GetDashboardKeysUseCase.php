<?php

namespace App\Dashboard\Application\UseCases\Dashboard;

use App\Dashboard\Application\Repositories\DashboardRepositoryInterface;

class GetDashboardKeysUseCase
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function execute(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        return $this->dashboardRepository->getKeys($fechaInicio, $fechaFin, $idSucursal);
    }
}
