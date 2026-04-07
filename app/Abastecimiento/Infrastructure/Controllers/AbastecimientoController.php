<?php

namespace App\Abastecimiento\Infrastructure\Controllers;

use App\Abastecimiento\Application\UseCases\Abastecimiento\ListAbastecimientosUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\GetAbastecimientoUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\CreateAbastecimientoUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\UpdateAbastecimientoUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\DeleteAbastecimientoUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\GetResumenAbastecimientoUseCase;
use App\Abastecimiento\Application\UseCases\Abastecimiento\GetSaldoAbastecimientoUseCase;
use App\Abastecimiento\Application\DTOs\AbastecimientoDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbastecimientoController
{
    private ListAbastecimientosUseCase $listAbastecimientos;
    private GetAbastecimientoUseCase $getAbastecimiento;
    private CreateAbastecimientoUseCase $createAbastecimiento;
    private UpdateAbastecimientoUseCase $updateAbastecimiento;
    private DeleteAbastecimientoUseCase $deleteAbastecimiento;
    private GetResumenAbastecimientoUseCase $getResumen;
    private GetSaldoAbastecimientoUseCase $getSaldo;

    public function __construct(
        ListAbastecimientosUseCase $listAbastecimientos,
        GetAbastecimientoUseCase $getAbastecimiento,
        CreateAbastecimientoUseCase $createAbastecimiento,
        UpdateAbastecimientoUseCase $updateAbastecimiento,
        DeleteAbastecimientoUseCase $deleteAbastecimiento,
        GetResumenAbastecimientoUseCase $getResumen,
        GetSaldoAbastecimientoUseCase $getSaldo
    ) {
        $this->listAbastecimientos = $listAbastecimientos;
        $this->getAbastecimiento = $getAbastecimiento;
        $this->createAbastecimiento = $createAbastecimiento;
        $this->updateAbastecimiento = $updateAbastecimiento;
        $this->deleteAbastecimiento = $deleteAbastecimiento;
        $this->getResumen = $getResumen;
        $this->getSaldo = $getSaldo;
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $abastecimientos = $this->listAbastecimientos->execute(
            $request->fecha,
            $request->id_sucursal
        );

        return response()->json([
            'success' => true,
            'data' => $abastecimientos
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $abastecimiento = $this->getAbastecimiento->execute($id);

        if (!$abastecimiento) {
            return response()->json([
                'success' => false,
                'message' => 'Abastecimiento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $abastecimiento
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_sucursal' => 'required|integer|exists:sucursal,id_sucursal',
            'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_menu' => 'required|integer|exists:menu,id_menu',
            'detalles.*.cantidad' => 'required|integer|min:1'
        ]);

        $dto = AbastecimientoDTO::fromRequest($request->all());
        $abastecimiento = $this->createAbastecimiento->execute($dto, $request->id_usuario);

        return response()->json([
            'success' => true,
            'message' => 'Abastecimiento registrado exitosamente',
            'data' => $abastecimiento
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_sucursal' => 'required|integer|exists:sucursal,id_sucursal',
            'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_menu' => 'required|integer|exists:menu,id_menu',
            'detalles.*.cantidad' => 'required|integer|min:1'
        ]);

        $dto = AbastecimientoDTO::fromRequest($request->all());
        $abastecimiento = $this->updateAbastecimiento->execute($id, $dto, $request->id_usuario);

        if (!$abastecimiento) {
            return response()->json([
                'success' => false,
                'message' => 'Abastecimiento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Abastecimiento actualizado exitosamente',
            'data' => $abastecimiento
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->deleteAbastecimiento->execute($id);

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Abastecimiento no encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Abastecimiento eliminado exitosamente'
        ]);
    }

    public function resumen(Request $request): JsonResponse
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $resumen = $this->getResumen->execute(
            $request->fecha,
            $request->id_sucursal
        );

        return response()->json([
            'success' => true,
            'data' => $resumen
        ]);
    }

    public function saldo(Request $request): JsonResponse
    {
        $request->validate([
            'fecha' => 'required|date',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $saldo = $this->getSaldo->execute(
            $request->fecha,
            $request->id_sucursal ? (int)$request->id_sucursal : null
        );

        return response()->json([
            'success' => true,
            'data' => $saldo
        ]);
    }
}
