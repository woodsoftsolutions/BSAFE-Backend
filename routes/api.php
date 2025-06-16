<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
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
    UserController,
    InventoryBalancesController
};



// Productos
Route::get('products/paginated', [ProductsController::class, 'paginated']);
Route::apiResource('products', ProductsController::class);
  
// Categorías
Route::apiResource('categories', CategoriesController::class);

// Unidades
Route::apiResource('units', UnitsController::class);

// Proveedores
Route::get('suppliers/paginated', [SuppliersController::class, 'paginated']);
Route::apiResource('suppliers', SuppliersController::class);

// Clientes
Route::get('customers/paginated', [CustomersController::class, 'paginated']);
Route::apiResource('customers', CustomersController::class);

// Empleados
Route::get('employees/paginated', [EmployeesController::class, 'paginated']);
Route::apiResource('employees', EmployeesController::class);

// Usuarios

Route::post('login', [UserController::class, 'login']);

// Almacenes
Route::apiResource('warehouses', WarehousesController::class);

// Pedidos
Route::get('orders/paginated', [OrdersController::class, 'paginated']);
Route::get('orders/summary', [OrdersController::class, 'summary']);
Route::apiResource('orders', OrdersController::class);
Route::post('orders/{order}/approve', [OrdersController::class, 'approve']);
Route::post('orders/{order}/reject', [OrdersController::class, 'reject']);
Route::post('orders/{order}/process', [OrdersController::class, 'process']);

// Movimientos de Inventario
Route::get('inventory-movements/paginated', [InventoryMovementsController::class, 'paginated']);
Route::apiResource('inventory-movements', InventoryMovementsController::class);
Route::get('inventory-movements/product/{product_id}', [InventoryMovementsController::class, 'show_id']);

Route::get('inventory-balances/paginated', [InventoryBalancesController::class, 'paginated']);
Route::apiResource('inventory-balances', InventoryBalancesController::class);

// Notas de Entrega
Route::apiResource('delivery-notes', DeliveryNotesController::class)->only(['index', 'show', 'store']);

// Reportes
Route::get('reports/inventory', [InventoryMovementsController::class, 'inventoryReport']);
Route::get('reports/stock-alerts', [ProductsController::class, 'stockAlerts']);

// Ruta pública para autenticación
#Route::post('/login', [AuthController::class, 'login']);