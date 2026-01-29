<?php

namespace App\Dashboard\Application\UseCases\Dashboard;

use App\Dashboard\Application\Repositories\DashboardRepositoryInterface;

class GetDashboardGraphsUseCase
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function execute(string $fechaInicio, string $fechaFin, ?int $idSucursal): array
    {
        return [
            'ingredientes_por_categoria' => $this->dashboardRepository->getIngredientesPorCategoria($fechaInicio, $fechaFin, $idSucursal),
            'menus_mas_vendidos' => $this->dashboardRepository->getMenusMasVendidos($fechaInicio, $fechaFin, $idSucursal),
            'horas_concurridas' => $this->dashboardRepository->getHorasConcurridas($fechaInicio, $fechaFin, $idSucursal)
        ];
    }
}
