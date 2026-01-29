<?php

namespace App\Menu\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Menu\Application\DTOs\CategoriaDTO;
use App\Menu\Application\UseCases\Categoria\CreateCategoriaUseCase;
use App\Menu\Application\UseCases\Categoria\DeleteCategoriaUseCase;
use App\Menu\Application\UseCases\Categoria\ListCategoriasUseCase;
use App\Menu\Application\UseCases\Categoria\UpdateCategoriaUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function __construct(
        private CreateCategoriaUseCase $createUseCase,
        private ListCategoriasUseCase $listUseCase,
        private UpdateCategoriaUseCase $updateUseCase,
        private DeleteCategoriaUseCase $deleteUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $categorias = $this->listUseCase->execute();
            
            return response()->json([
                'success' => true,
                'data' => $categorias
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar categorías',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_categoria' => 'required|string|max:255'
            ]);

            $dto = CategoriaDTO::fromRequest($validated);
            $categoria = $this->createUseCase->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente',
                'data' => $categoria
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
                'message' => 'Error al crear categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_categoria' => 'required|string|max:255'
            ]);

            $dto = CategoriaDTO::fromRequest($validated);
            $categoria = $this->updateUseCase->execute($id, $dto);

            if (!$categoria) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría actualizada exitosamente',
                'data' => $categoria
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
                'message' => 'Error al actualizar categoría',
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
                    'message' => 'Categoría no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría eliminada exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
