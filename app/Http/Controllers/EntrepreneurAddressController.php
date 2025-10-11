<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrepreneurAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class EntrepreneurAddressController extends Controller
{
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
                        'full_address' => $address->full_address ?? "{$address->address}, {$address->city}",
                        'has_coordinates' => !empty($address->latitude) && !empty($address->longitude),
                        'created_at' => $address->created_at->format('d/m/Y'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $addresses
            ]);

        } catch (\Exception $e) {
            Log::error('Error al listar direcciones: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar las direcciones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear nueva dirección
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
                'is_main' => ['nullable', 'boolean'],
            ], [
                'address.required' => 'La dirección es obligatoria',
                'city.required' => 'La ciudad es obligatoria',
                'department.required' => 'El departamento es obligatorio',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Si se marca como principal, desmarcar las demás
            $isMain = $request->is_main ?? false;
            if ($isMain) {
                $entrepreneur->addresses()->update(['is_main' => false]);
            } else {
                // Si es la primera dirección, marcarla como principal automáticamente
                if ($entrepreneur->addresses()->count() === 0) {
                    $isMain = true;
                }
            }

            // Crear dirección
            $address = EntrepreneurAddress::create([
                'entrepreneur_id' => $entrepreneur->id,
                'address' => $request->address,
                'city' => $request->city,
                'department' => $request->department,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'reference' => $request->reference,
                'is_main' => $isMain,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dirección creada correctamente',
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
                    'full_address' => $address->full_address ?? "{$address->address}, {$address->city}",
                    'has_coordinates' => !empty($address->latitude) && !empty($address->longitude),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al crear dirección: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar la dirección: ' . $e->getMessage()
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
                'is_main' => ['nullable', 'boolean'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Si se marca como principal, desmarcar las demás
            if ($request->is_main ?? false) {
                $entrepreneur->addresses()
                    ->where('id', '!=', $id)
                    ->update(['is_main' => false]);
            }

            // Actualizar
            $address->update([
                'address' => $request->address,
                'city' => $request->city,
                'department' => $request->department,
                'postal_code' => $request->postal_code,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'reference' => $request->reference,
                'is_main' => $request->is_main ?? $address->is_main,
            ]);

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
                    'full_address' => $address->full_address ?? "{$address->address}, {$address->city}",
                    'has_coordinates' => !empty($address->latitude) && !empty($address->longitude),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al actualizar dirección: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la dirección: ' . $e->getMessage()
            ], 500);
        }
    }

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

            // No permitir eliminar si es la única dirección y está marcada como principal
            if ($address->is_main && $entrepreneur->addresses()->count() === 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'No puedes eliminar tu única dirección'
                ], 422);
            }

            // Si se elimina la dirección principal y hay más direcciones,
            // marcar la siguiente como principal
            if ($address->is_main && $entrepreneur->addresses()->count() > 1) {
                $nextAddress = $entrepreneur->addresses()
                    ->where('id', '!=', $id)
                    ->first();
                
                if ($nextAddress) {
                    $nextAddress->update(['is_main' => true]);
                }
            }

            // Eliminar
            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dirección eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            Log::error('Error al eliminar dirección: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la dirección: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Establecer una dirección como principal
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

            // Desmarcar todas las direcciones como principales
            $entrepreneur->addresses()->update(['is_main' => false]);

            // Marcar esta como principal
            $address->update(['is_main' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Dirección principal actualizada correctamente',
                'data' => [
                    'id' => $address->id,
                    'address' => $address->address,
                    'city' => $address->city,
                    'department' => $address->department,
                    'is_main' => $address->is_main,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error al establecer dirección principal: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar: ' . $e->getMessage()
            ], 500);
        }
    }
}