<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index(): JsonResponse
    {
        $users = User::all(); // Obtiene todos los usuarios

        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    /**
     * Crear un nuevo usuario.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')), // Encripta la contraseña
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 201);
    }

    /**
     * Mostrar un usuario específico.
     */
    public function show(User $user): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Actualizar un usuario existente.
     */
    public function update(Request $request, User $user): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
        ]);

        $user->update([
            'name' => $request->input('name', $user->name),
            'email' => $request->input('email', $user->email),
            'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Usuario eliminado correctamente',
        ]);
    }

    public function login(Request $request): JsonResponse
{
    
    $request->validate([
        'email' => 'required|email',       // Validar que el email sea válido
        'password' => 'required|string',   // Validar que la contraseña no esté vacía
    ]);

    // Buscar el usuario por correo electrónico
    $user = User::where('email', $request->input('email'))->first();

    // Verificar si el usuario existe y la contraseña es correcta
    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Usuario no encontrado',
        ], 401); // Código HTTP 401: No autorizado
    }
      // Verificar si el empleado asociado está inactivo
      if ($user->employee && !$user->employee->active) {
        return response()->json([
            'success' => false,
            'message' => 'El empleado asociado a este usuario está inactivo',
        ], 403); // Código HTTP 403: Prohibido
    }

    if ($user && !Hash::check($request->input('password'), $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Credenciales incorrectas',
        ], 401); // Código HTTP 401: No autorizado
    }

    // Generar un token de autenticación (opcional, si usas Laravel Sanctum o Passport)
    // $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Inicio de sesión exitoso',
        'data' => $user,
        //'token' => $token,  Devuelve el token si estás usando autenticación basada en tokens
    ]);
}
}