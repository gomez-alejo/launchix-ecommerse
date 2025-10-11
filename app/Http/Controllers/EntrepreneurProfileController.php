<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\Entrepreneur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EntrepreneurController extends Controller
{
    /**
     * Obtener datos completos del emprendedor autenticado
     */
    public function getData()
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Cargar la dirección principal
            $entrepreneur->load('mainAddress');

            $data = [
                // Datos del negocio
                'business' => [
                    'id' => $entrepreneur->id,
                    'business_name' => $entrepreneur->business_name,
                    'email' => $entrepreneur->email,
                    'phone' => $entrepreneur->phone,
                    'description' => $entrepreneur->description,
                    'logo' => $entrepreneur->logo_url,
                    'average_rating' => $entrepreneur->average_rating,
                    'formatted_rating' => $entrepreneur->formatted_rating,
                    'verified' => $entrepreneur->verified,
                    'active' => $entrepreneur->active,
                    'email_verified' => $entrepreneur->hasVerifiedEmail(),
                    'registered_at' => $entrepreneur->formatted_registered_date,
                    'has_custom_logo' => $entrepreneur->hasCustomLogo(),
                ],
                
                // Datos de dirección
                'address' => $entrepreneur->mainAddress ? [
                    'id' => $entrepreneur->mainAddress->id,
                    'address' => $entrepreneur->mainAddress->address,
                    'city' => $entrepreneur->mainAddress->city,
                    'department' => $entrepreneur->mainAddress->department,
                    'postal_code' => $entrepreneur->mainAddress->postal_code,
                    'latitude' => $entrepreneur->mainAddress->latitude,
                    'longitude' => $entrepreneur->mainAddress->longitude,
                    'reference' => $entrepreneur->mainAddress->reference,
                    'is_main' => $entrepreneur->mainAddress->is_main,
                    'full_address' => $entrepreneur->mainAddress->full_address,
                    'has_coordinates' => $entrepreneur->mainAddress->hasCoordinates(),
                ] : null,
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al cargar datos del emprendedor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos del perfil'
            ], 500);
        }
    }

    /**
     * Actualizar información del negocio
     */
    public function update(Request $request)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'business_name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('entrepreneurs')->ignore($entrepreneur->id)
                ],
                'phone' => ['nullable', 'string', 'max:20'],
                'description' => ['nullable', 'string', 'max:1000'],
            ], [
                'business_name.required' => 'El nombre del negocio es obligatorio',
                'business_name.max' => 'El nombre del negocio no puede superar los 255 caracteres',
                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'El formato del correo electrónico no es válido',
                'email.unique' => 'Este correo electrónico ya está registrado',
                'phone.max' => 'El teléfono no puede superar los 20 caracteres',
                'description.max' => 'La descripción no puede superar los 1000 caracteres',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar datos
            $entrepreneur->update([
                'business_name' => $request->business_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Información del negocio actualizada correctamente',
                'data' => [
                    'business_name' => $entrepreneur->business_name,
                    'email' => $entrepreneur->email,
                    'phone' => $entrepreneur->phone,
                    'description' => $entrepreneur->description,
                    'logo' => $entrepreneur->logo_url,
                    'average_rating' => $entrepreneur->average_rating,
                    'formatted_rating' => $entrepreneur->formatted_rating,
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar emprendedor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la información del negocio'
            ], 500);
        }
    }

    /**
     * Subir/actualizar logo del negocio
     */
    public function uploadLogo(Request $request)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ], [
                'logo.required' => 'Debes seleccionar una imagen',
                'logo.image' => 'El archivo debe ser una imagen válida',
                'logo.mimes' => 'Solo se permiten imágenes en formato JPG, JPEG o PNG',
                'logo.max' => 'La imagen no debe superar los 2MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Eliminar logo anterior si existe
            if ($entrepreneur->hasCustomLogo()) {
                Storage::disk('public')->delete($entrepreneur->logo);
            }

            // Subir nueva imagen
            $path = $request->file('logo')->store('logos/entrepreneurs', 'public');

            // Actualizar en base de datos
            $entrepreneur->logo = $path;
            $entrepreneur->save();

            return response()->json([
                'success' => true,
                'message' => 'Logo actualizado correctamente',
                'logo_url' => $entrepreneur->logo_url,
                'has_custom_logo' => true
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al subir logo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al subir el logo'
            ], 500);
        }
    }

    /**
     * Eliminar logo del negocio
     */
    public function deleteLogo()
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            if (!$entrepreneur->hasCustomLogo()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes un logo personalizado para eliminar'
                ], 400);
            }

            // Eliminar logo
            $entrepreneur->deleteLogo();

            return response()->json([
                'success' => true,
                'message' => 'Logo eliminado correctamente',
                'logo_url' => $entrepreneur->logo_url,
                'has_custom_logo' => false
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar logo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logo'
            ], 500);
        }
    }
}