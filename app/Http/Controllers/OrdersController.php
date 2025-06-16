<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        // Crear la orden
        $order = Order::create($request->except('items'));

        // Crear los productos asociados
        foreach ($request->items as $item) {
            $order->orderItems()->create($item);
        }

        return response()->json([
            'success' => true,
            'data' => $order->load('orderItems'),
        ], 201);
    }

    /**
     * Mostrar una orden específica.
     */
    public function show(Order $order): JsonResponse
    {
        $order->load(['supplier', 'customer', 'employee', 'orderItems.product']);

        return response()->json([
            'success' => true,
            'data' => $order,
            'items' => $order->orderItems,
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
            'supplier_id' => 'nullable|numeric',
            'customer_id' => 'nullable|exists:customers,id',
            'employee_id' => 'sometimes|required|exists:employees,id',
            'order_date' => 'sometimes|required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'total_amount' => 'sometimes|required|numeric|min:0',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'sometimes|exists:order_items,id',
            'items.*.product_id' => 'required_with:items|exists:products,id',
            'items.*.quantity' => 'required_with:items|numeric|min:0',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
            'items.*.total_price' => 'required_with:items|numeric|min:0',
            'items.*.specifications' => 'nullable|string',
        ]);

        $order->update($request->except('items'));

        if ($request->has('items')) {
            // Eliminar items que no están en la actualización
            $itemIds = collect($request->items)->pluck('id')->filter();
            $order->orderItems()->whereNotIn('id', $itemIds)->delete();

            // Actualizar o crear los items
            foreach ($request->items as $item) {
                if (isset($item['id'])) {
                    // Actualizar item existente
                    $orderItem = $order->orderItems()->find($item['id']);
                    if ($orderItem) {
                        $orderItem->update($item);
                    }
                } else {
                    // Crear nuevo item
                    $order->orderItems()->create($item);
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $order->load('orderItems'),
        ]);
    }

    /**
     * Eliminar una orden.
     */
    public function destroy(Order $order): JsonResponse
    {
        // Eliminar los items asociados
        $order->orderItems()->delete();
        // Eliminar la orden
        $order->delete();

        return response()->json([
            'success' => true,
            'message' => 'Orden y productos asociados eliminados correctamente.',
        ]);
    }

    /**
     * Aprobar una orden.
     */
    public function approve(Order $order): JsonResponse
    {
        $order->status = 'approved';
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Orden aprobada correctamente.',
            'data' => $order
        ]);
    }

    /**
     * Rechazar una orden.
     */
    public function reject(Order $order): JsonResponse
    {
        $order->status = 'cancelled';
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Orden rechazada correctamente.',
            'data' => $order
        ]);
    }

    /**
     * Resumen de estadísticas del sistema.
     */
    public function summary(): JsonResponse
    {
        $suppliersCount = \App\Models\Supplier::count();
        $productsCount = \App\Models\Product::count();
        $pendingQuotations = \App\Models\Order::where('order_type', 'quotation')->where('status', 'pending_approval')->count();
        $activeUsers = \App\Models\Employee::where('active', 1)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'suppliers_count' => $suppliersCount,
                'products_count' => $productsCount,
                'pending_quotations' => $pendingQuotations,
                'active_users' => $activeUsers,
            ]
        ]);
    }
}