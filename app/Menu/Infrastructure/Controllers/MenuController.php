<?php

namespace App\Menu\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Menu\Application\DTOs\MenuDTO;
use App\Menu\Application\UseCases\Menu\ActivateMenuUseCase;
use App\Menu\Application\UseCases\Menu\CreateMenuUseCase;
use App\Menu\Application\UseCases\Menu\DeleteMenuUseCase;
use App\Menu\Application\UseCases\Menu\GetMenuWithReglasUseCase;
use App\Menu\Application\UseCases\Menu\ListMenusUseCase;
use App\Menu\Application\UseCases\Menu\UpdateMenuUseCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function __construct(
        private CreateMenuUseCase $createUseCase,
        private ListMenusUseCase $listUseCase,
        private GetMenuWithReglasUseCase $getMenuWithReglasUseCase,
        private UpdateMenuUseCase $updateUseCase,
        private DeleteMenuUseCase $deleteUseCase,
        private ActivateMenuUseCase $activateUseCase
    ) {}

    public function index(): JsonResponse
    {
        try {
            $menus = $this->listUseCase->execute();
            
            return response()->json([
                'success' => true,
                'data' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al listar menús',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $menu = $this->getMenuWithReglasUseCase->execute($id);

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menú no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $menu
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_menu' => 'required|string|max:255',
                'precio_menu' => 'required|numeric|min:0',
                'id_categoria_menu' => 'required|integer|exists:categoria_menu,id_categoria_menu',
                'url_foto' => 'nullable|string|max:255'
            ]);

            $dto = MenuDTO::fromRequest($validated);
            $menu = $this->createUseCase->execute($dto);

            return response()->json([
                'success' => true,
                'message' => 'Menú creado exitosamente',
                'data' => $menu
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
                'message' => 'Error al crear menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre_menu' => 'required|string|max:255',
                'precio_menu' => 'required|numeric|min:0',
                'id_categoria_menu' => 'required|integer|exists:categoria_menu,id_categoria_menu',
                'url_foto' => 'nullable|string|max:255'
            ]);

            $dto = MenuDTO::fromRequest($validated);
            $menu = $this->updateUseCase->execute($id, $dto);

            if (!$menu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menú no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menú actualizado exitosamente',
                'data' => $menu
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
                'message' => 'Error al actualizar menú',
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
                    'message' => 'Menú no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menú eliminado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function activate(int $id): JsonResponse
    {
        try {
            $activated = $this->activateUseCase->execute($id);

            if (!$activated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Menú no encontrado'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Menú activado exitosamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al activar menú',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
