<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WarehousesController extends Controller
{
       /**
     * Listar todos los almacenes.
     */
    public function index(): JsonResponse
    {
        $warehouses = Warehouse::all(); // Obtiene todos los almacenes

        return response()->json([
            'success' => true,
            'data' => $warehouses,
        ]);
    }

    /**
     * Crear un nuevo almacén.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',         // Validación del campo 'name'
            'location' => 'nullable|string|max:255',    // Validación del campo 'location'
            'is_active' => 'boolean',                   // Validación del campo 'is_active'
        ]);

        $Warehouses = Warehouse::create($request->all()); // Crea el almacén

        return response()->json([
            'success' => true,
            'data' => $Warehouses,
        ], 201); // Respuesta con código HTTP 201 (creado)
    }

    /**
     * Mostrar un almacén específico.
     */
    public function show(Warehouse $warehouse): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $warehouse,
        ]);
    }

    /**
     * Actualizar un almacén existente.
     */
    public function update(Request $request, Warehouse $warehouse): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',         // Validación del campo 'name'
            'location' => 'nullable|string|max:255',    // Validación del campo 'location'
            'is_active' => 'boolean',                   // Validación del campo 'is_active'
        ]);

        $warehouse->update($request->all()); // Actualiza el almacén

        return response()->json([
            'success' => true,
            'data' => $warehouse,
        ]);
    }

    /**
     * Eliminar un almacén.
     */
    public function destroy(Warehouse $warehouse): JsonResponse
    {
        $warehouse->delete(); // Elimina el almacén

        return response()->json([
            'success' => true,
            'message' => 'Almacén eliminado correctamente',
        ]);
    }
}
