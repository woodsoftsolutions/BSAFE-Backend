<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function index()
    {
        $orders = Order::with(['supplier', 'customer', 'employee', 'items.product'])
            ->filter()
            ->paginate(10);
            
        return response()->json($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        $order = DB::transaction(function () use ($request) {
            $order = Order::create($request->only([
                'order_type', 'supplier_id', 'customer_id', 'employee_id',
                'order_date', 'expected_delivery_date', 'notes'
            ]));
            
            $total = 0;
            
            foreach ($request->items as $item) {
                $orderItem = $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price']
                ]);
                
                $total += $orderItem->total_price;
            }
            
            $order->update(['total_amount' => $total]);
            
            return $order;
        });
        
        return response()->json($order->load('items'), 201);
    }

    public function approve(Order $order)
    {
        $order->update(['status' => 'approved']);
        return response()->json($order);
    }

    public function process(Order $order)
    {
        if ($order->status !== 'approved') {
            return response()->json(['message' => 'Order must be approved first'], 400);
        }
        
        if ($order->order_type === 'purchase') {
            $this->processPurchaseOrder($order);
        } else {
            $this->processSaleOrder($order);
        }
        
        return response()->json($order->fresh()->load('inventoryMovements'));
    }
    
    protected function processPurchaseOrder(Order $order)
    {
        foreach ($order->items as $item) {
            $item->product->increment('current_stock', $item->quantity);
            
            $order->inventoryMovements()->create([
                'product_id' => $item->product_id,
                'movement_type' => 'entry',
                'quantity' => $item->quantity,
                'unit_cost' => $item->unit_price,
                'total_cost' => $item->total_price,
                'warehouse_id' => 1, // Almacén principal
                'employee_id' => auth()->id()
            ]);
        }
        
        $order->update(['status' => 'fulfilled']);
    }
    
    protected function processSaleOrder(Order $order)
    {
        $deliveryNote = $order->deliveryNote()->create([
            'delivery_date' => now(),
            'delivery_address' => $order->customer->address,
            'receiver_name' => request('receiver_name'),
            'delivered_by' => auth()->id()
        ]);
        
        foreach ($order->items as $item) {
            if ($item->product->current_stock < $item->quantity) {
                throw new \Exception("Insufficient stock for product {$item->product->name}");
            }
            
            $item->product->decrement('current_stock', $item->quantity);
            
            $order->inventoryMovements()->create([
                'product_id' => $item->product_id,
                'movement_type' => 'exit',
                'quantity' => $item->quantity,
                'unit_cost' => $item->product->last_cost_price,
                'total_cost' => $item->quantity * $item->product->last_cost_price,
                'warehouse_id' => 1,
                'delivery_note_id' => $deliveryNote->id,
                'employee_id' => auth()->id()
            ]);
        }
        
        $order->update(['status' => 'fulfilled']);
    }
}
