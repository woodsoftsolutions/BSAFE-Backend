<?php

namespace App\Http\Controllers;

use App\Models\Warehouses;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WarehousesController extends Controller
{
       /**
     * Listar todos los almacenes.
     */
    public function index(): JsonResponse
    {
        $Warehouses = Warehouses::all(); // Obtiene todos los almacenes

        return response()->json([
            'success' => true,
            'data' => $Warehouses,
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

        $Warehouses = Warehouses::create($request->all()); // Crea el almacén

        return response()->json([
            'success' => true,
            'data' => $Warehouses,
        ], 201); // Respuesta con código HTTP 201 (creado)
    }

    /**
     * Mostrar un almacén específico.
     */
    public function show(Warehouses $Warehouses): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $Warehouses,
        ]);
    }

    /**
     * Actualizar un almacén existente.
     */
    public function update(Request $request, Warehouses $Warehouses): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',         // Validación del campo 'name'
            'location' => 'nullable|string|max:255',    // Validación del campo 'location'
            'is_active' => 'boolean',                   // Validación del campo 'is_active'
        ]);

        $Warehouses->update($request->all()); // Actualiza el almacén

        return response()->json([
            'success' => true,
            'data' => $Warehouses,
        ]);
    }

    /**
     * Eliminar un almacén.
     */
    public function destroy(Warehouses $Warehouses): JsonResponse
    {
        $Warehouses->delete(); // Elimina el almacén

        return response()->json([
            'success' => true,
            'message' => 'Almacén eliminado correctamente',
        ]);
    }
}
