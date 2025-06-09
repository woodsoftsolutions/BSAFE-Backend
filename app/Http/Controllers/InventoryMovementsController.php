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
        'unit_cost' => 'required|numeric|min:0',
        'total_cost' => 'nullable|numeric|min:0',
        'warehouse_id' => 'required|exists:warehouses,id',
        'order_id' => 'nullable|exists:orders,id',
        'delivery_note_id' => 'nullable|exists:delivery_notes,id',
        'employee_id' => 'required|exists:employees,id',
    ]);

    // 1. Registrar el movimiento
    $movement = InventoryMovement::create($request->all());

    // 2. Buscar o crear el balance actual
    $balance = \App\Models\InventoryBalance::firstOrCreate(
        [
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
        ],
        [
            'quantity' => 0,
            'unit_cost' => $request->unit_cost,
            'date' => now(),
        ]
    );

    // 3. Actualizar el balance según el tipo de movimiento
    $qty = $request->quantity;
    switch ($request->movement_type) {
        case 'entry':
            $balance->quantity += $qty;
            break;
        case 'exit':
            $balance->quantity -= $qty;
            break;
        case 'adjustment':
            $balance->quantity = $qty; // O ajusta según tu lógica de negocio
            break;
        case 'transfer':
            // Si es transferencia, deberías manejar entrada y salida en dos movimientos
            // Aquí solo se ajusta el balance del almacén origen
            $balance->quantity -= $qty;
            break;
    }
    $balance->unit_cost = $request->unit_cost;
    $balance->date = now();
    $balance->save();

    return response()->json([
        'success' => true,
        'data' => [
            'movement' => $movement,
            'balance' => $balance,
        ],
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
     * Mostrar todos los movimientos de inventario de un producto específico.
     */
    public function show_id($product_id): JsonResponse
    {
        $movements = InventoryMovement::with(['product', 'warehouse', 'order', 'deliveryNote', 'employee'])
            ->where('product_id', $product_id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $movements,
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
    // Buscar el balance correspondiente
    $balance = \App\Models\InventoryBalance::where('product_id', $inventoryMovement->product_id)
        ->where('warehouse_id', $inventoryMovement->warehouse_id)
        ->first();

    // Si existe el balance, restar la cantidad del movimiento eliminado
    if ($balance) {
        $balance->quantity -= $inventoryMovement->quantity;
        $balance->save();
    }

    $inventoryMovement->delete();

    return response()->json([
        'success' => true,
        'message' => 'Movimiento de inventario eliminado correctamente.',
        'balance' => $balance,
    ]);
}
}