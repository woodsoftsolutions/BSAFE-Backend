<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SuppliersController extends Controller
{
    /**
     * Listar todos los proveedores.
     */
    public function index(): JsonResponse
    {
        $Suppliers = Supplier::all(); // Obtiene todos los proveedores

        return response()->json([
            'success' => true,
            'data' => $Suppliers,
        ]);
    }

    /**
     * Crear un nuevo proveedor.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $Suppliers = Supplier::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $Suppliers,
        ], 201);
    }

    /**
     * Mostrar un proveedor específico.
     */
    public function show(Supplier $Suppliers): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $Suppliers,
        ]);
    }

    /**
     * Actualizar un proveedor existente.
     */
    public function update(Request $request, Supplier $Suppliers): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $Suppliers->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $Suppliers,
        ]);
    }

    /**
     * Eliminar un proveedor.
     */
    public function destroy(Supplier $Suppliers): JsonResponse
    {
        $Suppliers->delete();

        return response()->json([
            'success' => true,
            'message' => 'Proveedor eliminado correctamente',
        ]);
    }
}
