<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAddressController extends Controller
{
    /**
     * Obtener todas las direcciones del usuario autenticado
     */
    public function index()
    {
        $user = Auth::user();
        $addresses = $user->addresses()
            ->orderBy('is_main', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    /**
     * Obtener la dirección principal del usuario
     */
    public function getMain()
    {
        $user = Auth::user();
        $mainAddress = $user->addresses()->where('is_main', true)->first();

        return response()->json([
            'success' => true,
            'data' => $mainAddress
        ]);
    }

    /**
     * Crear una nueva dirección
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'reference' => 'nullable|string|max:500',
            'is_main' => 'boolean',
        ], [
            'address.required' => 'La dirección es requerida',
            'city.required' => 'La ciudad es requerida',
            'department.required' => 'El departamento es requerido',
        ]);

        // Si se marca como principal, desmarcar las demás
        if ($validated['is_main'] ?? false) {
            $user->addresses()->update(['is_main' => false]);
        } else {
            // Si es la primera dirección, marcarla como principal automáticamente
            if ($user->addresses()->count() === 0) {
                $validated['is_main'] = true;
            }
        }

        $address = $user->addresses()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dirección agregada exitosamente',
            'data' => $address
        ], 201);
    }

    /**
     * Mostrar una dirección específica
     */
    public function show($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    /**
     * Actualizar una dirección específica
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        $validated = $request->validate([
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'department' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'reference' => 'nullable|string|max:500',
            'is_main' => 'boolean',
        ], [
            'address.required' => 'La dirección es requerida',
            'city.required' => 'La ciudad es requerida',
            'department.required' => 'El departamento es requerido',
        ]);

        // Si se marca como principal, desmarcar las demás
        if ($validated['is_main'] ?? false) {
            $user->addresses()->where('id', '!=', $id)->update(['is_main' => false]);
        }

        $address->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dirección actualizada exitosamente',
            'data' => $address
        ]);
    }

    /**
     * Eliminar una dirección
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // No permitir eliminar si es la única dirección y está marcada como principal
        if ($address->is_main && $user->addresses()->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes eliminar tu única dirección.'
            ], 422);
        }

        // Si se elimina la dirección principal y hay más direcciones, 
        // marcar la siguiente como principal
        if ($address->is_main && $user->addresses()->count() > 1) {
            $nextAddress = $user->addresses()
                ->where('id', '!=', $id)
                ->first();
            
            if ($nextAddress) {
                $nextAddress->update(['is_main' => true]);
            }
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dirección eliminada exitosamente'
        ]);
    }

    /**
     * Establecer una dirección como principal
     */
    public function setMain($id)
    {
        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // Desmarcar todas las direcciones como principales
        $user->addresses()->update(['is_main' => false]);

        // Marcar esta como principal
        $address->update(['is_main' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Dirección principal actualizada exitosamente',
            'data' => $address
        ]);
    }

    /**
     * Crear o actualizar la dirección principal desde el perfil
     * Método auxiliar usado por ProfileController
     */
    public function createOrUpdateMain(array $data)
    {
        $user = Auth::user();

        // Si no hay datos de dirección, no hacer nada
        if (empty(array_filter($data))) {
            return null;
        }

        // Buscar dirección principal existente
        $mainAddress = $user->addresses()->where('is_main', true)->first();

        if ($mainAddress) {
            // Actualizar dirección existente
            $mainAddress->update($data);
        } else {
            // Crear nueva dirección principal
            $mainAddress = $user->addresses()->create(array_merge($data, [
                'is_main' => true
            ]));
        }

        return $mainAddress;
    }
}