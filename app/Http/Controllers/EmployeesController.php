<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeesController extends Controller
{
    /**
     * Listar todos los empleados.
     */
    public function index(): JsonResponse
    {
        $employee = Employee::with('user')->get(); // Obtiene todos los empleados con su usuario relacionado

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    /**
     * Listar empleados paginados.
     */
    public function paginated(Request $request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        $employees = \App\Models\Employee::paginate($perPage);
        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }

    /**
     * Crear un nuevo empleado.
     */
    public function store(Request $request): JsonResponse
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'dni' => 'required|string|max:50|unique:employees,dni',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255|unique:employees,email|unique:users,email',
        'position' => 'required|string|max:255',
        'hire_date' => 'required|date',
        'can_manage_inventory' => 'boolean',
        'active' => 'boolean',
        'password' => 'required|string|min:8',
    ]);

    try {
        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        $employee = Employee::create([
            'user_id' => $user->id,
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'dni' => $request->input('dni'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'position' => $request->input('position'),
            'hire_date' => $request->input('hire_date'),
            'can_manage_inventory' => $request->input('can_manage_inventory', false),
            'active' => $request->input('active', true),
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'employee' => $employee,
                'user' => $user,
            ],
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al crear el empleado: ' . $e->getMessage(),
        ], 500);
    }
}

    /**
     * Mostrar un empleado específico.
     */
    public function show(Employee $employee): JsonResponse
    {
        $employee->load('user'); // Carga la relación con el usuario

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    /**
     * Actualizar un empleado existente.
     */
    public function update(Request $request, Employee $employee): JsonResponse
    {
        $request->validate([
            'user_id' => 'sometimes|nullable|exists:users,id',
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'dni' => 'sometimes|required|string|max:50|unique:employees,dni,' . $employee->id,
            'phone' => 'sometimes|required|string|max:20',
            'email' => 'sometimes|required|email|max:255|unique:employees,email,' . $employee->id,
            'position' => 'sometimes|required|string|max:255',
            'hire_date' => 'sometimes|required|date',
            'can_manage_inventory' => 'boolean',
            'active' => 'boolean',
            'password' => 'nullable|string|min:8',
        ]);

        $employee->update($request->all());

        $user = $employee->user;
        if ($user) {
            $user->update([
                'name' => $request->input('first_name', $employee->first_name) . ' ' . $request->input('last_name', $employee->last_name),
                'email' => $request->input('email', $user->email),
                'password' => $request->filled('password') ? Hash::make($request->input('password')) : $user->password,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $employee,
        ]);
    }

    /**
     * Eliminar un empleado.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Empleado eliminado correctamente',
        ]);
    }
}
