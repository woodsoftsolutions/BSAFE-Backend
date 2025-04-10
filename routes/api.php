<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
    productosController,
    ProductsController,
    CategoriesController,
    SuppliersController,
    CustomersController,
    EmployeesController,
    WarehousesController,
    OrdersController,
    InventoryMovementsController,
    DeliveryNotesController,
    UnitsController,
    UserController
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
Route::apiResource('products', ProductsController::class);
    
// Categorías
Route::apiResource('categories', CategoriesController::class);

// Unidades
Route::apiResource('units', UnitsController::class);

// Proveedores
Route::apiResource('suppliers', SuppliersController::class);

// Clientes
Route::apiResource('customers', CustomersController::class);

// Empleados
Route::apiResource('employees', EmployeesController::class);

// Usuarios
Route::apiResource('users', UserController::class);

Route::post('login', [UserController::class, 'login']);

// Almacenes
Route::apiResource('warehouses', WarehousesController::class);

// Pedidos
Route::apiResource('orders', OrdersController::class);
Route::post('orders/{order}/approve', [OrdersController::class, 'approve']);
Route::post('orders/{order}/process', [OrdersController::class, 'process']);

// Movimientos de Inventario
Route::apiResource('inventory-movements', InventoryMovementsController::class)->only(['index', 'show']);

// Notas de Entrega
Route::apiResource('delivery-notes', DeliveryNotesController::class)->only(['index', 'show', 'store']);

// Reportes
Route::get('reports/inventory', [InventoryMovementsController::class, 'inventoryReport']);
Route::get('reports/stock-alerts', [ProductsController::class, 'stockAlerts']);

// Ruta pública para autenticación
#Route::post('/login', [AuthController::class, 'login']);