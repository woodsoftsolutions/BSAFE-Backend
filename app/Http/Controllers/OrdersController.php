<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    /**
     * Listar todas las órdenes.
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['supplier', 'customer', 'employee'])->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Crear una nueva orden.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'order_number' => 'required|string|unique:orders,order_number',
            'order_type' => 'required|in:quotation,purchase',
            'status' => 'required|in:draft,pending_approval,approved,cancelled',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'required|exists:employees,id',
            'order_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0',
        ]);

        $order = Order::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $order,
        ], 201);
    }

    /**
     * Mostrar una orden específica.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['supplier', 'customer', 'employee']);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Actualizar una orden.
     */
    public function update(Request $request, Order $order): JsonResponse
    {
        $request->validate([
            'order_number' => 'sometimes|required|string|unique:orders,order_number,' . $order->id,
            'order_type' => 'sometimes|required|in:quotation,purchase',
            'status' => 'sometimes|required|in:draft,pending_approval,approved,cancelled',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'sometimes|required|exists:employees,id',
            'order_date' => 'sometimes|required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_amount' => 'sometimes|required|numeric|min:0',
        ]);

        $order->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Eliminar una orden.
     */
    public function destroy(Order $order): JsonResponse
    {
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orden eliminada correctamente.',
        ]);
    }
}