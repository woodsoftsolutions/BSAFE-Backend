<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class CustomersController extends Controller
{
     /**
     * Listar todos los clientes.
     */
    public function index(): JsonResponse
    {
        $customers = Customers::all(); // Obtiene todos los clientes

        return response()->json([
            'success' => true,
            'data' => $customers,
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

        $customers = Customers::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $customers,
        ], 201);
    }

    /**
     * Mostrar un cliente específico.
     */
    public function show(Customers $customers): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Actualizar un cliente existente.
     */


    /**
     * Eliminar un cliente.
     */
    public function destroy(Customers $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cliente eliminado correctamente',
        ]);
    }
}
