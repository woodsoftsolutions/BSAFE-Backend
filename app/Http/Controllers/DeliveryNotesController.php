<?php

namespace App\Http\Controllers;

use App\Models\DeliveryNote;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DeliveryNotesController extends Controller
{
    /**
     * Listar todas las notas de entrega.
     */
    public function index(): JsonResponse
    {
        $deliveryNotes = DeliveryNote::with(['order', 'deliveredBy'])->get();

        return response()->json([
            'success' => true,
            'data' => $deliveryNotes,
        ]);
    }

    /**
     * Crear una nueva nota de entrega.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'note_number' => 'required|string|unique:delivery_notes,note_number',
            'order_id' => 'required|exists:orders,id',
            'delivery_date' => 'required|date',
            'delivery_address' => 'required|string|max:255',
            'receiver_name' => 'required|string|max:255',
            'receiver_dni' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'delivered_by' => 'required|exists:employees,id',
        ]);

        $deliveryNote = DeliveryNote::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $deliveryNote,
        ], 201);
    }

    /**
     * Mostrar una nota de entrega específica.
     */
    public function show(DeliveryNote $deliveryNote): JsonResponse
    {
        $deliveryNote->load(['order', 'deliveredBy']);

        return response()->json([
            'success' => true,
            'data' => $deliveryNote,
        ]);
    }

    /**
     * Actualizar una nota de entrega.
     */
    public function update(Request $request, DeliveryNote $deliveryNote): JsonResponse
    {
        $request->validate([
            'note_number' => 'sometimes|required|string|unique:delivery_notes,note_number,' . $deliveryNote->id,
            'order_id' => 'sometimes|required|exists:orders,id',
            'delivery_date' => 'sometimes|required|date',
            'delivery_address' => 'sometimes|required|string|max:255',
            'receiver_name' => 'sometimes|required|string|max:255',
            'receiver_dni' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'delivered_by' => 'sometimes|required|exists:employees,id',
        ]);

        $deliveryNote->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $deliveryNote,
        ]);
    }

    /**
     * Eliminar una nota de entrega.
     */
    public function destroy(DeliveryNote $deliveryNote): JsonResponse
    {
        $deliveryNote->delete();

        return response()->json([
            'success' => true,
            'message' => 'Nota de entrega eliminada correctamente.',
        ]);
    }
}