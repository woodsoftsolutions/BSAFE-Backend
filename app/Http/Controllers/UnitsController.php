<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UnitsController extends Controller
{
     /**
     * Listar todas las unidades.
     */
    public function index(): JsonResponse
    {
        $Unitss = Unit::all(); // Obtiene todas las unidades

        return response()->json([
            'success' => true,
            'data' => $Unitss,
        ]);
    }

    /**
     * Crear una nueva unidad.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',         // Validación del campo 'name'
            'abbreviation' => 'required|string|max:10', // Validación del campo 'abbreviation'
        ]);

        $Units = Unit::create($request->all()); // Crea la unidad

        return response()->json([
            'success' => true,
            'data' => $Units,
        ], 201); // Respuesta con código HTTP 201 (creado)
    }

    /**
     * Mostrar una unidad específica.
     */
    public function show(Unit $Units): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $Units,
        ]);
    }

    /**
     * Actualizar una unidad existente.
     */
    public function update(Request $request, Unit $Units): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',         // Validación del campo 'name'
            'abbreviation' => 'required|string|max:10', // Validación del campo 'abbreviation'
        ]);

        $Units->update($request->all()); // Actualiza la unidad

        return response()->json([
            'success' => true,
            'data' => $Units,
        ]);
    }

    /**
     * Eliminar una unidad.
     */
    public function destroy(Unit $Units): JsonResponse
    {
        $Units->delete(); // Elimina la unidad

        return response()->json([
            'success' => true,
            'message' => 'Unidad eliminada correctamente',
        ]);
    }
}
