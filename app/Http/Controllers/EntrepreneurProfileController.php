<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // ← AGREGAR ESTA LÍNEA
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Entrepreneur;
use Illuminate\Support\Facades\Log;

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
        /** @var \App\Models\Entrepreneur $entrepreneur */
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
    /**
 * Actualizar perfil del emprendedor
 */
    public function updateEntrepreneurProfile(Request $request)
    {
        try {
            /** @var \App\Models\Entrepreneur $entrepreneur */
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error en updateEntrepreneurProfile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error en el servidor: ' . $e->getMessage()
            ], 500);
        }
    }



    /**
     * Actualizar foto de perfil del emprendedor
     */
    public function updateEntrepreneurAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        /** @var \App\Models\Entrepreneur $entrepreneur */
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
        /** @var \App\Models\Entrepreneur $entrepreneur */
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

    /**
     * Cambiar contraseña del emprendedor
     */
    public function updatePassword(Request $request)
    {
        /** @var \App\Models\Entrepreneur $entrepreneur */
        $entrepreneur = Auth::guard('entrepreneur')->user();

        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'La contraseña actual es requerida',
            'new_password.required' => 'La nueva contraseña es requerida',
            'new_password.min' => 'La nueva contraseña debe tener al menos 6 caracteres',
            'new_password_confirmation.required' => 'Debes confirmar la nueva contraseña',
            'new_password_confirmation.same' => 'Las contraseñas no coinciden',
        ]);

        // Verificar contraseña actual
        if (!Hash::check($validated['current_password'], $entrepreneur->password)) {
            return response()->json([
                'success' => false,
                'message' => 'La contraseña actual es incorrecta'
            ], 422);
        }

        // Actualizar contraseña
        $entrepreneur->password = Hash::make($validated['new_password']);
        $entrepreneur->save();

        return response()->json([
            'success' => true,
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }



    private function transformProductData($product)
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'stock' => (int) $product->stock,
            'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
            'gallery_images' => $product->gallery_images ? array_map(fn($image) => asset('storage/' . $image), $product->gallery_images) : [],
            'category' => [
                'name' => is_string($product->category) ? $product->category : 'General',
                'slug' => is_string($product->category) ?
                    strtolower(str_replace([' ', 'ó', 'é', 'í', 'ú', 'ñ'], ['', 'o', 'e', 'i', 'u', 'n'], $product->category)) :
                    'general'
            ],
            'entrepreneur' => [
                'id' => $product->entrepreneur->id,
                'name' => $product->entrepreneur->name,
                'business_name' => $product->entrepreneur->business_name ?? $product->entrepreneur->name,
                'avatar' => $product->entrepreneur->avatar ?
                    asset('storage/' . $product->entrepreneur->avatar) :
                    'https://ui-avatars.com/api/?name=' . urlencode($product->entrepreneur->name) . '&background=F77786&color=fff'
            ],
            'created_at' => $product->created_at
        ];
    }

    /**
 * Perfil público del emprendedor (sin autenticación)
 */
    public function publicProfile($id)
    {
        try {
            $entrepreneur = \App\Models\Entrepreneur::findOrFail($id);

            // Obtener productos del emprendedor
            $products = \App\Models\Product::where('entrepreneur_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            // Obtener servicios del emprendedor
            $services = \App\Models\Servicio::where('user_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            $transformedProducts = [];
            foreach ($products as $product) {
                $transformedProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description ?? '',
                    'price' => (float) $product->price,
                    'stock' => (int) $product->stock,
                    'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Producto',
                    'category' => is_string($product->category) ? $product->category : 'General',
                ];
            }

            // Transformar servicios para la vista
            $transformedServices = [];
            foreach ($services as $service) {
                $serviceImage = 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio';
                if ($service->imagen_principal) {
                    if (str_starts_with($service->imagen_principal, 'images/')) {
                        $serviceImage = asset($service->imagen_principal);
                    } else {
                        $serviceImage = asset('storage/' . $service->imagen_principal);
                    }
                }

                $transformedServices[] = [
                    'id' => $service->id,
                    'nombre_servicio' => $service->nombre_servicio,
                    'descripcion' => $service->descripcion ?? '',
                    'precio_base' => (float) ($service->precio_base ?? 0),
                    'categoria' => $service->categoria ?? 'General',
                    'direccion' => $service->direccion ?? '',
                    'telefono' => $service->telefono ?? '',
                    'horario_atencion' => $service->horario_atencion ?? '',
                    'imagen_principal' => $serviceImage,
                    'created_at' => $service->created_at,
                ];
            }

            $avatarUrl = $entrepreneur->profile_photo ?
                asset('storage/' . $entrepreneur->profile_photo) :
                'https://ui-avatars.com/api/?name=' . urlencode($entrepreneur->first_name . ' ' . $entrepreneur->last_name) . '&background=F77786&color=fff';

            return view('entrepreneur.public-profile', [
                'entrepreneur' => $entrepreneur,
                'transformedProducts' => $transformedProducts,
                'transformedServices' => $transformedServices,
                'avatarUrl' => $avatarUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Error en perfil público: ' . $e->getMessage());
            abort(500, $e->getMessage());
        }
    }
}
