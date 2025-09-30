<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Entrepreneur;

class EntrepreneurProfileController extends Controller
{
    public function show()
    {
        return view('profiles.entrepreneur');
    }

    /**
     * Obtener datos del emprendedor autenticado
     */
    public function getEntrepreneurData()
    {
        // Usar el guard 'entrepreneur' explícitamente
        $entrepreneur = Auth::guard('entrepreneur')->user();
        
        // Verificación adicional por seguridad
        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $entrepreneur->id,
                'first_name' => $entrepreneur->first_name,
                'last_name' => $entrepreneur->last_name,
                'full_name' => $entrepreneur->full_name,
                'email' => $entrepreneur->email,
                'phone' => $entrepreneur->phone ?? '',
                'city' => $entrepreneur->city ?? '',
                'address' => $entrepreneur->address ?? '',
                'description' => $entrepreneur->profile_description ?? '',
                'avatar' => $entrepreneur->profile_photo_url,
                'created_at' => $entrepreneur->registered_at->format('d/m/Y'),
                'email_verified' => $entrepreneur->email_verified_at !== null,
            ]
        ]);
    }

    /**
     * Actualizar perfil del emprendedor
     */
    public function updateEntrepreneurProfile(Request $request)
    {
        $entrepreneur = Auth::guard('entrepreneur')->user();

        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('entrepreneurs')->ignore($entrepreneur->id)
            ],
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        // Actualizar campos
        $entrepreneur->first_name = $validated['first_name'];
        $entrepreneur->last_name = $validated['last_name'];
        $entrepreneur->email = $validated['email'];
        $entrepreneur->phone = $validated['phone'];
        $entrepreneur->city = $validated['city'];
        $entrepreneur->address = $validated['address'];
        $entrepreneur->profile_description = $validated['description'];
        
        $entrepreneur->save();

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',
            'data' => [
                'id' => $entrepreneur->id,
                'first_name' => $entrepreneur->first_name,
                'last_name' => $entrepreneur->last_name,
                'full_name' => $entrepreneur->full_name,
                'email' => $entrepreneur->email,
                'phone' => $entrepreneur->phone,
                'city' => $entrepreneur->city,
                'address' => $entrepreneur->address,
                'description' => $entrepreneur->profile_description,
                'avatar' => $entrepreneur->profile_photo_url,
            ]
        ]);
    }

    /**
     * Actualizar foto de perfil del emprendedor
     */
    public function updateEntrepreneurAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $entrepreneur = Auth::guard('entrepreneur')->user();

        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        try {
            // Eliminar foto anterior si existe
            if ($entrepreneur->profile_photo && Storage::disk('public')->exists($entrepreneur->profile_photo)) {
                Storage::disk('public')->delete($entrepreneur->profile_photo);
            }

            // Guardar nueva foto
            $path = $request->file('avatar')->store('entrepreneur_photos', 'public');
            
            $entrepreneur->profile_photo = $path;
            $entrepreneur->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil actualizada correctamente',
                'avatar_url' => Storage::url($path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al subir la imagen: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Eliminar foto de perfil del emprendedor
     */
    public function deleteEntrepreneurAvatar()
    {
        $entrepreneur = Auth::guard('entrepreneur')->user();

        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        if ($entrepreneur->profile_photo) {
            if (Storage::disk('public')->exists($entrepreneur->profile_photo)) {
                Storage::disk('public')->delete($entrepreneur->profile_photo);
            }
            
            $entrepreneur->profile_photo = null;
            $entrepreneur->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto de perfil eliminada'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No hay foto de perfil para eliminar'
        ], 400);
    }
}