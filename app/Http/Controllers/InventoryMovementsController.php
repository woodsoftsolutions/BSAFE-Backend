<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class InventoryMovementsController extends Controller
{
    /**
     * Listar todos los movimientos de inventario.
     */
    public function index(): JsonResponse
    {
        $inventoryMovements = InventoryMovement::with(['product', 'warehouse', 'order', 'deliveryNote', 'employee'])->get();

        return response()->json([
            'success' => true,
            'data' => $inventoryMovements,
        ]);
    }

    /**
     * Crear un nuevo movimiento de inventario.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'movement_type' => 'required|in:entry,exit,adjustment,transfer',
            'quantity' => 'required|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'warehouse_id' => 'required|exists:warehouses,id',
            'order_id' => 'nullable|exists:orders,id',
            'delivery_note_id' => 'nullable|exists:delivery_notes,id',
            'employee_id' => 'required|exists:employees,id',
        ]);

        $inventoryMovement = InventoryMovement::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventoryMovement,
        ], 201);
    }

    /**
     * Mostrar un movimiento de inventario específico.
     */
    public function show(InventoryMovement $inventoryMovement): JsonResponse
    {
        $inventoryMovement->load(['product', 'warehouse', 'order', 'deliveryNote', 'employee']);

        return response()->json([
            'success' => true,
            'data' => $inventoryMovement,
        ]);
    }

    /**
     * Actualizar un movimiento de inventario.
     */
    public function update(Request $request, InventoryMovement $inventoryMovement): JsonResponse
    {
        $request->validate([
            'movement_type' => 'sometimes|required|in:entry,exit,adjustment,transfer',
            'quantity' => 'sometimes|required|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
            'total_cost' => 'nullable|numeric|min:0',
            'warehouse_id' => 'sometimes|required|exists:warehouses,id',
            'order_id' => 'nullable|exists:orders,id',
            'delivery_note_id' => 'nullable|exists:delivery_notes,id',
            'employee_id' => 'sometimes|required|exists:employees,id',
        ]);

        $inventoryMovement->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $inventoryMovement,
        ]);
    }

    /**
     * Eliminar un movimiento de inventario.
     */
    public function destroy(InventoryMovement $inventoryMovement): JsonResponse
    {
        $inventoryMovement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Movimiento de inventario eliminado correctamente.',
        ]);
    }
}