<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class CustomersController extends Controller
{
     /**
     * Listar todos los clientes.
     */
    public function index(): JsonResponse
    {
        $Customer = Customer::all(); // Obtiene todos los clientes

        return response()->json([
            'success' => true,
            'data' => $Customer,
        ]);
    }

    /**
     * Crear un nuevo cliente.
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
            'customer_type' => 'required|in:regular,wholesaler,government',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $Customer = Customer::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $Customer,
        ], 201);
    }

    /**
     * Mostrar un cliente específico.
     */
    public function show(Customer $Customer): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $Customer,
        ]);
    }

    /**
     * Actualizar un cliente existente.
     */
    public function update(Request $request, Customer $customer): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:50',
            'customer_type' => 'sometimes|required|in:regular,wholesaler,government',
            'notes' => 'nullable|string',
            'active' => 'boolean',
        ]);

        $customer->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $customer,
        ]);
    }

    /**
     * Restaurar un cliente eliminado (soft delete).
     */
    public function restore($id): JsonResponse
    {
        $customer = Customer::withTrashed()->findOrFail($id);
        $customer->restore();

        return response()->json([
            'success' => true,
            'message' => 'Cliente restaurado correctamente',
            'data' => $customer,
        ]);
    }

    /**
     * Listar clientes eliminados (soft deleted).
     */
    public function trashed(): JsonResponse
    {
        $customers = Customer::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }


    /**
     * Eliminar un cliente.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente',
        ]);
    }
}
