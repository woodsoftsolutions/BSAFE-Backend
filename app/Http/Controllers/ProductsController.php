<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductsController extends Controller
{
    /**
     * Listar todos los productos.
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['category', 'unit'])->get();

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Listar productos paginados.
     */
    public function paginated(Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $products = Product::with(['category', 'unit'])->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * Crear un nuevo producto.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string|unique:products,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'unit_id' => 'required|exists:units,id',
            'current_stock' => 'required|numeric|min:0',
            'minimum_stock' => 'required|numeric|min:0',
            'active' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $product,
        ], 201);
    }

    /**
     * Mostrar un producto específico.
     */
    public function show(Product $product): JsonResponse
    {
        $product->load(['category', 'unit']);

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Actualizar un producto.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $request->validate([
            'code' => 'sometimes|required|string|unique:products,code,' . $product->id,
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'unit_id' => 'sometimes|required|exists:units,id',
            'current_stock' => 'sometimes|required|numeric|min:0',
            'minimum_stock' => 'sometimes|required|numeric|min:0',
            'active' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
        ]);

        $product->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Eliminar un producto.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Producto eliminado correctamente.',
        ]);
    }
}