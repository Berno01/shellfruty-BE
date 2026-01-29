<?php

namespace App\Menu\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Menu\Application\DTOs\ReglaDTO;
use App\Menu\Application\UseCases\Regla\CreateReglaUseCase;
use App\Menu\Application\UseCases\Regla\DeleteReglaUseCase;
use App\Menu\Application\UseCases\Regla\ListReglasByCategoriaUseCase;
use App\Menu\Application\UseCases\Regla\UpdateReglaUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReglaController extends Controller
{
    public function __construct(
        private CreateReglaUseCase $createUseCase,
        private ListReglasByCategoriaUseCase $listByCategoriaUseCase,
        private UpdateReglaUseCase $updateUseCase,
        private DeleteReglaUseCase $deleteUseCase
    ) {}

    public function index(int $idCategoria): JsonResponse
    {
        try {
            $reglas = $this->listByCategoriaUseCase->execute($idCategoria);
            
            return response()->json([
                'success' => true,
                'data' => $reglas
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar reglas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
                'id_menu' => 'required|integer|exists:menu,id_menu',
                'cant_gratis' => 'required|integer|min:0',
                'costo_extra' => 'required|integer|min:0',
                'combinacion' => 'required|boolean',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_ingrediente' => 'required|integer|exists:ingrediente,id_ingrediente',
                'detalles.*.costo_extra' => 'required|numeric|min:0'
            ]);

            $dto = ReglaDTO::fromRequest($validated);
            $regla = $this->createUseCase->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Regla creada exitosamente',
                'data' => $regla
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear regla',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
                'id_menu' => 'required|integer|exists:menu,id_menu',
                'cant_gratis' => 'required|integer|min:0',
                'costo_extra' => 'required|integer|min:0',
                'combinacion' => 'required|boolean',
                'detalles' => 'required|array|min:1',
                'detalles.*.id_ingrediente' => 'required|integer|exists:ingrediente,id_ingrediente',
                'detalles.*.costo_extra' => 'required|numeric|min:0'
            ]);

            $dto = ReglaDTO::fromRequest($validated);
            $regla = $this->updateUseCase->execute($id, $dto);

            if (!$regla) {
                return response()->json([
                    'success' => false,
                    'message' => 'Regla no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regla actualizada exitosamente',
                'data' => $regla
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar regla',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->deleteUseCase->execute($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Regla no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Regla eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar regla',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
