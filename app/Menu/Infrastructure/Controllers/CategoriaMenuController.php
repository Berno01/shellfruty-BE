<?php

namespace App\Menu\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Menu\Application\DTOs\CategoriaMenuDTO;
use App\Menu\Application\UseCases\CategoriaMenu\CreateCategoriaMenuUseCase;
use App\Menu\Application\UseCases\CategoriaMenu\ListCategoriasMenuUseCase;
use App\Menu\Application\UseCases\CategoriaMenu\UpdateCategoriaMenuUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoriaMenuController extends Controller
{
    public function __construct(
        private CreateCategoriaMenuUseCase $createUseCase,
        private ListCategoriasMenuUseCase $listUseCase,
        private UpdateCategoriaMenuUseCase $updateUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $categoriasMenu = $this->listUseCase->execute();
            
            return response()->json([
                'success' => true,
                'data' => $categoriasMenu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar categorías de menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_categoria_menu' => 'required|string|max:255'
            ]);

            $dto = CategoriaMenuDTO::fromRequest($validated);
            $categoriaMenu = $this->createUseCase->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Categoría de menú creada exitosamente',
                'data' => $categoriaMenu
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
                'message' => 'Error al crear categoría de menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_categoria_menu' => 'required|string|max:255'
            ]);

            $dto = CategoriaMenuDTO::fromRequest($validated);
            $categoriaMenu = $this->updateUseCase->execute($id, $dto);

            if (!$categoriaMenu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Categoría de menú no encontrada'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Categoría de menú actualizada exitosamente',
                'data' => $categoriaMenu
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
                'message' => 'Error al actualizar categoría de menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
