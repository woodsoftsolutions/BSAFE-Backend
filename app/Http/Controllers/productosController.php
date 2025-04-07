<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use Illuminate\Support\Facades\Validator;

class productosController extends Controller
{
    public function index(Request $request)
    {
        $productos = Producto::all();
        // Aquí puedes implementar la lógica para obtener los productos
        return response()->json([
            'success' => true,
            'data' => [
                'productos' => $productos // Aquí irían los productos obtenidos
            ]
        ]);
    }

    public function guardar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0'
            //'categoria_id' => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $producto = Producto::create($request->all());

        return response()->json([
            'success' => true,
            'data' => [
                'producto' => $producto
            ]
        ]);
    }


}
