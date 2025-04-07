<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    productosController,
    ProductController,
    CategoriesController,
    SuppliersController,
    CustomerController,
    EmployeeController,
    WarehousesController,
    OrderController,
    InventoryMovementController,
    DeliveryNoteController,
    UnitsController
};


Route::get('/productos', [productosController::class, 'index']);
Route::get('/productos/filtrar', function () {
    return response()->json([
        'success' => true,
        'data' => [
            'productos' => [] 
        ]
    ]);
});
// Productos
Route::apiResource('products', ProductController::class);
    
// Categorías
Route::apiResource('categories', CategoriesController::class);

// Unidades
Route::apiResource('units', UnitsController::class);

// Proveedores
Route::apiResource('suppliers', SuppliersController::class);

// Clientes
Route::apiResource('customers', CustomerController::class);

// Empleados
Route::apiResource('employees', EmployeeController::class);

// Almacenes
Route::apiResource('warehouses', WarehousesController::class);

// Pedidos
Route::apiResource('orders', OrderController::class);
Route::post('orders/{order}/approve', [OrderController::class, 'approve']);
Route::post('orders/{order}/process', [OrderController::class, 'process']);

// Movimientos de Inventario
Route::apiResource('inventory-movements', InventoryMovementController::class)->only(['index', 'show']);

// Notas de Entrega
Route::apiResource('delivery-notes', DeliveryNoteController::class)->only(['index', 'show', 'store']);

// Reportes
Route::get('reports/inventory', [InventoryMovementController::class, 'inventoryReport']);
Route::get('reports/stock-alerts', [ProductController::class, 'stockAlerts']);

// Ruta pública para autenticación
Route::post('/login', [AuthController::class, 'login']);