<?php

namespace App\Venta\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Venta\Application\DTOs\VentaDTO;
use App\Venta\Application\UseCases\Venta\ListVentasUseCase;
use App\Venta\Application\UseCases\Venta\GetVentaUseCase;
use App\Venta\Application\UseCases\Venta\CreateVentaUseCase;
use App\Venta\Application\UseCases\Venta\UpdateVentaUseCase;
use App\Venta\Application\UseCases\Venta\CancelVentaUseCase;
use App\Venta\Application\UseCases\Venta\EnviarVentaUseCase;
use App\Venta\Application\UseCases\Venta\GetIngredientesDictionaryUseCase;
use App\Venta\Application\UseCases\Venta\ListMenusActivosUseCase;

class VentaController extends Controller
{
    public function __construct(
        private ListVentasUseCase $listVentas,
        private GetVentaUseCase $getVenta,
        private CreateVentaUseCase $createVenta,
        private UpdateVentaUseCase $updateVenta,
        private CancelVentaUseCase $cancelVenta,
        private EnviarVentaUseCase $enviarVenta,
        private GetIngredientesDictionaryUseCase $getIngredientesDictionary,
        private ListMenusActivosUseCase $listMenusActivos
    ) {}

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'id_sucursal' => 'nullable|integer|exists:sucursal,id_sucursal'
        ]);

        $ventas = $this->listVentas->execute(
            $request->fecha_inicio,
            $request->fecha_fin,
            $request->id_sucursal ? (int) $request->id_sucursal : null
        );

        return response()->json([
            'success' => true,
            'data' => $ventas
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $venta = $this->getVenta->execute($id);

        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $venta->load('detalles.personalizaciones')
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            'fecha' => 'required|date',
            'id_sucursal' => 'required|integer|exists:sucursal,id_sucursal',
            'monto_efectivo' => 'required|numeric|min:0',
            'monto_qr' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado' => 'nullable|string|in:ENTREGADO,PENDIENTE,ENVIADO,CANCELADO',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_menu' => 'required|integer|exists:menu,id_menu',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
            'detalles.*.sub_total' => 'required|numeric|min:0',
            'detalles.*.personalizaciones' => 'nullable|array',
            'detalles.*.personalizaciones.*.id_ingrediente' => 'required_with:detalles.*.personalizaciones|integer|exists:ingrediente,id_ingrediente',
            'detalles.*.personalizaciones.*.cantidad' => 'required_with:detalles.*.personalizaciones|integer|min:0'
        ]);

        // Validar rol del usuario
        if (!$this->createVenta->validateUserRole($request->id_usuario, $request->detalles)) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no válido o rol bot requiere personalizaciones en todos los detalles'
            ], 400);
        }

        $dto = VentaDTO::fromRequest($request->all());
        $venta = $this->createVenta->execute($dto, $request->id_usuario);

        return response()->json([
            'success' => true,
            'message' => 'Venta creada exitosamente',
            'data' => $venta
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|integer|exists:usuario,id_usuario',
            'fecha' => 'required|date',
            'id_sucursal' => 'required|integer|exists:sucursal,id_sucursal',
            'monto_efectivo' => 'required|numeric|min:0',
            'monto_qr' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'estado' => 'nullable|string|in:ENTREGADO,PENDIENTE,ENVIADO,CANCELADO',
            'detalles' => 'required|array|min:1',
            'detalles.*.id_menu' => 'required|integer|exists:menu,id_menu',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio' => 'required|numeric|min:0',
            'detalles.*.sub_total' => 'required|numeric|min:0',
            'detalles.*.personalizaciones' => 'nullable|array',
            'detalles.*.personalizaciones.*.id_ingrediente' => 'required_with:detalles.*.personalizaciones|integer|exists:ingrediente,id_ingrediente',
            'detalles.*.personalizaciones.*.cantidad' => 'required_with:detalles.*.personalizaciones|integer|min:0'
        ]);

        // Validar que sea admin
        if (!$this->updateVenta->isAdmin($request->id_usuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para actualizar ventas'
            ], 403);
        }

        $dto = VentaDTO::fromRequest($request->all());
        $venta = $this->updateVenta->execute($id, $dto, $request->id_usuario);

        if (!$venta) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Venta actualizada exitosamente',
            'data' => $venta
        ]);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|integer|exists:usuario,id_usuario'
        ]);

        // Validar que sea admin
        if (!$this->cancelVenta->isAdmin($request->id_usuario)) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permisos para cancelar ventas'
            ], 403);
        }

        $result = $this->cancelVenta->execute($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Venta cancelada exitosamente'
        ]);
    }

    public function getIngredientes(): JsonResponse
    {
        $data = $this->getIngredientesDictionary->execute();

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function getMenus(): JsonResponse
    {
        $menus = $this->listMenusActivos->execute();

        return response()->json([
            'success' => true,
            'data' => $menus
        ]);
    }

    public function enviar(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|integer|exists:usuario,id_usuario'
        ]);

        $result = $this->enviarVenta->execute($id);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Venta no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Venta enviada exitosamente'
        ]);
    }
}
