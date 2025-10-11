<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\EntrepreneurAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EntrepreneurAddressController extends Controller
{
    /**
     * Eliminar dirección
     */
    public function destroy($id)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Buscar dirección
            $address = EntrepreneurAddress::where('id', $id)
                ->where('entrepreneur_id', $entrepreneur->id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada'
                ], 404);
            }

            // Verificar si es la única dirección
            $totalAddresses = EntrepreneurAddress::where('entrepreneur_id', $entrepreneur->id)->count();

            if ($totalAddresses === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu única dirección'
                ], 400);
            }

            // Eliminar
            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dirección eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar dirección: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la dirección'
            ], 500);
        }
    }

    /**
     * Establecer dirección como principal
     */
    public function setAsMain($id)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Buscar dirección
            $address = EntrepreneurAddress::where('id', $id)
                ->where('entrepreneur_id', $entrepreneur->id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada'
                ], 404);
            }

            // Establecer como principal
            $address->setAsMain();

            return response()->json([
                'success' => true,
                'message' => 'Dirección establecida como principal',
                'data' => [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'department' => $address->department,
                    'postal_code' => $address->postal_code,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'reference' => $address->reference,
                    'is_main' => $address->is_main,
                    'full_address' => $address->full_address,
                    'has_coordinates' => $address->hasCoordinates(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al establecer dirección principal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al establecer la dirección como principal'
            ], 500);
        }
    }
}

     * Obtener la dirección principal del emprendedor autenticado
     */
    public function getMain()
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $address = $entrepreneur->mainAddress;

            if (!$address) {
                return response()->json([
                    'success' => true,
                    'data' => null,
                    'message' => 'No tienes una dirección registrada'
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'department' => $address->department,
                    'postal_code' => $address->postal_code,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'reference' => $address->reference,
                    'is_main' => $address->is_main,
                    'full_address' => $address->full_address,
                    'has_coordinates' => $address->hasCoordinates(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al obtener dirección principal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar la dirección'
            ], 500);
        }
    }

    /**
     * Obtener todas las direcciones del emprendedor
     */
    public function index()
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            $addresses = $entrepreneur->addresses()
                ->orderBy('is_main', 'desc')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($address) {
                    return [
                        'id' => $address->id,
                        'address' => $address->address,
                        'city' => $address->city,
                        'department' => $address->department,
                        'postal_code' => $address->postal_code,
                        'latitude' => $address->latitude,
                        'longitude' => $address->longitude,
                        'reference' => $address->reference,
                        'is_main' => $address->is_main,
                        'full_address' => $address->full_address,
                        'has_coordinates' => $address->hasCoordinates(),
                        'created_at' => $address->created_at->format('d/m/Y'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $addresses
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al listar direcciones: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las direcciones'
            ], 500);
        }
    }

    /**
     * Crear o actualizar dirección
     */
    public function store(Request $request)
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
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'department' => ['required', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'reference' => ['nullable', 'string', 'max:500'],
                'is_main' => ['boolean'],
            ], [
                'address.required' => 'La dirección es obligatoria',
                'address.max' => 'La dirección no puede superar los 255 caracteres',
                'city.required' => 'La ciudad es obligatoria',
                'city.max' => 'La ciudad no puede superar los 100 caracteres',
                'department.required' => 'El departamento es obligatorio',
                'department.max' => 'El departamento no puede superar los 100 caracteres',
                'postal_code.max' => 'El código postal no puede superar los 20 caracteres',
                'latitude.between' => 'La latitud debe estar entre -90 y 90',
                'longitude.between' => 'La longitud debe estar entre -180 y 180',
                'reference.max' => 'Las referencias no pueden superar los 500 caracteres',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Buscar si ya tiene una dirección principal
            $mainAddress = $entrepreneur->mainAddress;

            if ($mainAddress) {
                // Actualizar dirección existente
                $mainAddress->update([
                    'address' => $request->address,
                    'city' => $request->city,
                    'department' => $request->department,
                    'postal_code' => $request->postal_code,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'reference' => $request->reference,
                    'is_main' => $request->is_main ?? true,
                ]);

                $address = $mainAddress;
                $message = 'Dirección actualizada correctamente';
            } else {
                // Crear nueva dirección
                $address = EntrepreneurAddress::create([
                    'entrepreneur_id' => $entrepreneur->id,
                    'address' => $request->address,
                    'city' => $request->city,
                    'department' => $request->department,
                    'postal_code' => $request->postal_code,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'reference' => $request->reference,
                    'is_main' => true, // Primera dirección siempre es principal
                ]);

                $message = 'Dirección creada correctamente';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'department' => $address->department,
                    'postal_code' => $address->postal_code,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'reference' => $address->reference,
                    'is_main' => $address->is_main,
                    'full_address' => $address->full_address,
                    'has_coordinates' => $address->hasCoordinates(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al guardar dirección: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la dirección'
            ], 500);
        }
    }

    /**
     * Actualizar dirección específica
     */
    public function update(Request $request, $id)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Buscar dirección
            $address = EntrepreneurAddress::where('id', $id)
                ->where('entrepreneur_id', $entrepreneur->id)
                ->first();

            if (!$address) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dirección no encontrada'
                ], 404);
            }

            // Validación
            $validator = Validator::make($request->all(), [
                'address' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:100'],
                'department' => ['required', 'string', 'max:100'],
                'postal_code' => ['nullable', 'string', 'max:20'],
                'latitude' => ['nullable', 'numeric', 'between:-90,90'],
                'longitude' => ['nullable', 'numeric', 'between:-180,180'],
                'reference' => ['nullable', 'string', 'max:500'],
                'is_main' => ['boolean'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Actualizar
            $address->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Dirección actualizada correctamente',
                'data' => [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'department' => $address->department,
                    'postal_code' => $address->postal_code,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                    'reference' => $address->reference,
                    'is_main' => $address->is_main,
                    'full_address' => $address->full_address,
                    'has_coordinates' => $address->hasCoordinates(),
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar dirección: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la dirección'
            ], 500);
        }
    }

    /**