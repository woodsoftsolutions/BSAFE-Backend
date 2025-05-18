<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        // Aquí puedes implementar la lógica para obtener los productos
        return response()->json([
            'success' => true,
            'data' => [
                'categorias' => $categories 
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $Categories = Category::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $Categories,
        ]);
    }
   /**
     * Mostrar una categoría específica.
     */
    public function show($id)
    {
        $Categories = Category::find($id);

        if (!$Categories) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $Categories,
        ]);
    }

    /**
     * Actualizar una categoría existente.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $Categories = Category::find($id);

        if (!$Categories) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        $Categories->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $Categories,
        ]);
    }

    /**
     * Eliminar una categoría.
     */
    public function destroy($id)
    {
        $Categories = Category::find($id);

        if (!$Categories) {
            return response()->json([
                'success' => false,
                'message' => 'Categoría no encontrada',
            ], 404);
        }

        $Categories->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada correctamente',
        ]);
    }
}
