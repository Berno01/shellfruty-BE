<?php

namespace App\Dashboard\Infrastructure\Controllers;

use App\Dashboard\Application\UseCases\Dashboard\GetDashboardKeysUseCase;
use App\Dashboard\Application\UseCases\Dashboard\GetDashboardGraphsUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController
{
    private GetDashboardKeysUseCase $getDashboardKeysUseCase;
    private GetDashboardGraphsUseCase $getDashboardGraphsUseCase;

    public function __construct(
        GetDashboardKeysUseCase $getDashboardKeysUseCase,
        GetDashboardGraphsUseCase $getDashboardGraphsUseCase
    ) {
        $this->getDashboardKeysUseCase = $getDashboardKeysUseCase;
        $this->getDashboardGraphsUseCase = $getDashboardGraphsUseCase;
    }

    public function getKeys(Request $request): JsonResponse
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $data = $this->getDashboardKeysUseCase->execute(
            $request->input('fecha_inicio'),
            $request->input('fecha_fin'),
            $request->input('id_sucursal')
        );

        return response()->json($data);
    }

    public function getGraphs(Request $request): JsonResponse
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $data = $this->getDashboardGraphsUseCase->execute(
            $request->input('fecha_inicio'),
            $request->input('fecha_fin'),
            $request->input('id_sucursal')
        );

        return response()->json($data);
    }
}
