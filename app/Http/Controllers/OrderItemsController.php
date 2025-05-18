<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrderItemsController extends Controller
{
    /**
     * Listar todos los items de una orden.
     */
    public function index(Request $request): JsonResponse
    {
        $orderId = $request->query('order_id');
        $query = OrderItem::with(['order', 'product']);

        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        $orderItems = $query->get();

        return response()->json([
            'success' => true,
            'data' => $orderItems,
        ]);
    }

    /**
     * Crear un nuevo item de orden.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'specifications' => 'nullable|string',
        ]);

        $orderItem = OrderItem::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $orderItem,
        ], 201);
    }

    /**
     * Mostrar un item de orden específico.
     */
    public function show(OrderItem $orderItem): JsonResponse
    {
        $orderItem->load(['order', 'product']);

        return response()->json([
            'success' => true,
            'data' => $orderItem,
        ]);
    }

    /**
     * Actualizar un item de orden.
     */
    public function update(Request $request, OrderItem $orderItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'sometimes|required|numeric|min:0',
            'unit_price' => 'sometimes|required|numeric|min:0',
            'total_price' => 'sometimes|required|numeric|min:0',
            'specifications' => 'nullable|string',
        ]);

        $orderItem->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $orderItem,
        ]);
    }

    /**
     * Eliminar un item de orden.
     */
    public function destroy(OrderItem $orderItem): JsonResponse
    {
        $orderItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item de orden eliminado correctamente.',
        ]);
    }
}