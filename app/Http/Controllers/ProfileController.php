<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    protected $addressController;

    public function __construct(UserAddressController $addressController)
    {
        $this->addressController = $addressController;
    }

    /**
     * Mostrar la vista del perfil
     */
    public function index()
    {
        return view('modals.login-items.user.ProfileSection');
    }

    /**
     * Obtener datos del usuario autenticado (API)
     */
public function getUserData()
{
    $user = Auth::user();
    
    // Obtener la dirección principal
    $mainAddress = $user->addresses()->where('is_main', true)->first();
    
    return response()->json([
        'success' => true,
        'data' => [
            'name' => $user->name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'username' => $user->username,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'birthdate' => $user->birthdate ? $user->birthdate->format('Y-m-d') : '',
            'profile_photo_url' => $user->profile_photo_url,
            // Datos de dirección principal
            'address' => $mainAddress->address ?? '',
            'city' => $mainAddress->city ?? '',
            'postal_code' => $mainAddress->postal_code ?? '',
            'department' => $mainAddress->department ?? '',
            'reference' => $mainAddress->reference ?? '',
            'address_id' => $mainAddress->id ?? null,
            // Metadata - CAMBIO AQUÍ
            'last_updated' => $user->updated_at ? $user->updated_at->format('d M Y') : '-',
            'registered_at' => $user->created_at ? $user->created_at->format('d M Y') : '-', // Usar created_at
        ]
    ]);
}

    /**
     * Actualizar perfil del usuario
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validar datos personales
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'birthdate' => 'nullable|date|before:today',
        ]);

        // Actualizar datos del usuario
        $user->update($validated);

        // Validar datos de dirección
        $addressData = $request->validate([
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:100',
            'reference' => 'nullable|string|max:500',
        ]);

        // Delegar la gestión de dirección al AddressController
        if (!empty(array_filter($addressData))) {
            $this->addressController->createOrUpdateMain($addressData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado exitosamente'
        ]);
    }

    /**
     * Cambiar contraseña
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'La contraseña actual es requerida',
            'new_password.required' => 'La nueva contraseña es requerida',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'new_password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['La contraseña actual es incorrecta']
            ]);
        }

        // Actualizar contraseña
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada exitosamente'
        ]);
    }
}