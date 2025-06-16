<?php

namespace App\Http\Controllers;

use App\Models\InventoryBalance;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryBalancesController extends Controller
{
    /**
     * Listar todos los balances de inventario.
     */
    public function index(): JsonResponse
    {
        $inventoryBalances = InventoryBalance::with(['product', 'warehouse'])->get();

        return response()->json([
            'success' => true,
            'data' => $inventoryBalances,
        ]);
    }

    /**
     * Crear un nuevo balance de inventario.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'unit_cost' => 'required|numeric|min:0',
            'date' => 'required|date',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $inventoryBalance = InventoryBalance::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventoryBalance,
        ], 201);
    }

    /**
     * Mostrar un balance de inventario específico.
     */
    public function show(InventoryBalance $inventoryBalance): JsonResponse
    {
        $inventoryBalance->load(['product', 'warehouse']);

        return response()->json([
            'success' => true,
            'data' => $inventoryBalance,
        ]);
    }

    /**
     * Actualizar un balance de inventario.
     */
    public function update(Request $request, InventoryBalance $inventoryBalance): JsonResponse
    {
        $request->validate([
            'quantity' => 'sometimes|required|numeric|min:0',
            'unit_cost' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'warehouse_id' => 'sometimes|required|exists:warehouses,id',
        ]);

        $inventoryBalance->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventoryBalance,
        ]);
    }

    /**
     * Eliminar un balance de inventario.
     */
    public function destroy(InventoryBalance $inventoryBalance): JsonResponse
    {
        $inventoryBalance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Balance de inventario eliminado correctamente.',
        ]);
    }

    /**
     * Listar balances de inventario paginados.
     */
    public function paginated(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $balances = InventoryBalance::with(['product', 'warehouse'])->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $balances,
        ]);
    }
}