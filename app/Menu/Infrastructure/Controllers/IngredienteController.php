<?php

namespace App\Menu\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Menu\Application\DTOs\IngredienteDTO;
use App\Menu\Application\UseCases\Ingrediente\CreateIngredienteUseCase;
use App\Menu\Application\UseCases\Ingrediente\DeleteIngredienteUseCase;
use App\Menu\Application\UseCases\Ingrediente\ListIngredientesByCategoriaUseCase;
use App\Menu\Application\UseCases\Ingrediente\UpdateIngredienteUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IngredienteController extends Controller
{
    public function __construct(
        private CreateIngredienteUseCase $createUseCase,
        private ListIngredientesByCategoriaUseCase $listByCategoriaUseCase,
        private UpdateIngredienteUseCase $updateUseCase,
        private DeleteIngredienteUseCase $deleteUseCase
    ) {}

    public function index(int $idCategoria): JsonResponse
    {
        try {
            $ingredientes = $this->listByCategoriaUseCase->execute($idCategoria);
            
            return response()->json([
                'success' => true,
                'data' => $ingredientes
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar ingredientes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_ingrediente' => 'required|string|max:255',
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
                'url_foto' => 'nullable|string|max:255'
            ]);

            $dto = IngredienteDTO::fromRequest($validated);
            $ingrediente = $this->createUseCase->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Ingrediente creado exitosamente',
                'data' => $ingrediente
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
                'message' => 'Error al crear ingrediente',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_ingrediente' => 'required|string|max:255',
                'id_categoria' => 'required|integer|exists:categoria,id_categoria',
                'url_foto' => 'nullable|string|max:255'
            ]);

            $dto = IngredienteDTO::fromRequest($validated);
            $ingrediente = $this->updateUseCase->execute($id, $dto);

            if (!$ingrediente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ingrediente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ingrediente actualizado exitosamente',
                'data' => $ingrediente
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
                'message' => 'Error al actualizar ingrediente',
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
                    'message' => 'Ingrediente no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Ingrediente eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar ingrediente',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
