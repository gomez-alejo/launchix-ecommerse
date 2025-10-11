<?php

namespace App\Http\Controllers;

use App\Models\Entrepreneur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EntrepreneurController extends Controller
{

    public function getData(Request $request)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Cargar dirección principal
            $mainAddress = $entrepreneur->mainAddress;

            return response()->json([
                'success' => true,
                'data' => [
                    'entrepreneur' => [
                        'id' => $entrepreneur->id,
                        'business_name' => $entrepreneur->business_name,
                        'email' => $entrepreneur->email,
                        'phone' => $entrepreneur->phone,
                        'description' => $entrepreneur->description,
                        'logo' => $entrepreneur->logo,
                        'logo_url' => $entrepreneur->logo_url,
                        'average_rating' => $entrepreneur->average_rating,
                        'verified' => $entrepreneur->verified,
                        'active' => $entrepreneur->active,
                        'registered_at' => $entrepreneur->registered_at,
                        'formatted_registered_date' => $entrepreneur->formatted_registered_date,
                    ],
                    'address' => $mainAddress ? [
                        'id' => $mainAddress->id,
                        'address' => $mainAddress->address,
                        'city' => $mainAddress->city,
                        'department' => $mainAddress->department,
                        'postal_code' => $mainAddress->postal_code,
                        'latitude' => $mainAddress->latitude,
                        'longitude' => $mainAddress->longitude,
                        'reference' => $mainAddress->reference,
                        'is_main' => $mainAddress->is_main,
                        'full_address' => $mainAddress->full_address,
                        'has_coordinates' => $mainAddress->hasCoordinates(),
                    ] : null
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al obtener datos del emprendedor: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los datos'
            ], 500);
        }
    }

    /**
     * Actualizar perfil del emprendedor (AJAX)
     */
public function updateProfile(Request $request)
{
    try {
        $entrepreneur = Auth::guard('entrepreneur')->user();

        if (!$entrepreneur) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        // Validación - solo validar los campos que vienen en el request
        $rules = [];
        $messages = [];

        if ($request->has('business_name')) {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $messages['business_name.required'] = 'El nombre del negocio es obligatorio';
        }

        if ($request->has('email')) {
            $rules['email'] = [
                'required', 
                'email', 
                Rule::unique('entrepreneurs')->ignore($entrepreneur->id)
            ];
            $messages['email.required'] = 'El correo electrónico es obligatorio';
            $messages['email.unique'] = 'Este correo ya está registrado';
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('description')) {
            $rules['description'] = ['nullable', 'string', 'max:1000'];
            $messages['description.max'] = 'La descripción no puede superar los 1000 caracteres';
        }

        // Validar solo si hay reglas
        if (!empty($rules)) {
            $validated = $request->validate($rules, $messages);
            
            // Actualizar solo los campos que vienen en el request
            $entrepreneur->update(array_filter($validated, function($value) {
                return $value !== null;
            }));
        }

        return response()->json([
            'success' => true,
            'message' => 'Perfil actualizado correctamente',
            'data' => [
                'business_name' => $entrepreneur->business_name,
                'email' => $entrepreneur->email,
                'phone' => $entrepreneur->phone,
                'description' => $entrepreneur->description,
            ]
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        \Log::error('Error al actualizar perfil: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Actualizar logo del emprendedor (AJAX)
     */
    public function updateLogo(Request $request)
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
            $request->validate([
                'logo' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            ], [
                'logo.required' => 'Debes seleccionar una imagen',
                'logo.image' => 'El archivo debe ser una imagen',
                'logo.mimes' => 'Solo se permiten imágenes JPG, JPEG y PNG',
                'logo.max' => 'La imagen no puede superar los 2MB',
            ]);

            // Eliminar logo anterior si existe
            if ($entrepreneur->logo) {
                Storage::disk('public')->delete($entrepreneur->logo);
            }

            // Guardar nuevo logo
            $logoPath = $request->file('logo')->store('logos', 'public');
            $entrepreneur->update(['logo' => $logoPath]);

            return response()->json([
                'success' => true,
                'message' => 'Logo actualizado correctamente',
                'data' => [
                    'logo' => $entrepreneur->logo,
                    'logo_url' => $entrepreneur->logo_url,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error al actualizar logo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el logo'
            ], 500);
        }
    }

    /**
     * Eliminar logo del emprendedor (AJAX)
     */
    public function deleteLogo(Request $request)
    {
        try {
            $entrepreneur = Auth::guard('entrepreneur')->user();

            if (!$entrepreneur) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }

            // Eliminar logo del storage
            if ($entrepreneur->logo) {
                Storage::disk('public')->delete($entrepreneur->logo);
                $entrepreneur->update(['logo' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Logo eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al eliminar logo: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el logo'
            ], 500);
        }
    }
    
    public function index()
    {
        $entrepreneurs = Entrepreneur::active()
            ->verified()
            ->with(['addresses', 'products', 'services'])
            ->paginate(12);

        return view('entrepreneurs.index', compact('entrepreneurs'));
    }

    public function create()
    {
        return view('entrepreneurs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:entrepreneurs,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $entrepreneur = Entrepreneur::create($validated);

        return redirect()
            ->route('entrepreneurs.show', $entrepreneur)
            ->with('success', 'Emprendedor creado exitosamente');
    }

    public function show(Entrepreneur $entrepreneur)
    {
        $entrepreneur->load([
            'products' => fn($q) => $q->where('disponible', true),
            'services' => fn($q) => $q->where('disponible', true),
            'addresses',
            'reviews' => fn($q) => $q->latest()->limit(10)
        ]);

        return view('entrepreneurs.show', compact('entrepreneur'));
    }

    public function edit(Entrepreneur $entrepreneur)
    {
        return view('entrepreneurs.edit', compact('entrepreneur'));
    }

    public function update(Request $request, Entrepreneur $entrepreneur)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'email' => 'required|email|unique:entrepreneurs,email,' . $entrepreneur->id,
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            if ($entrepreneur->logo) {
                Storage::disk('public')->delete($entrepreneur->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $entrepreneur->update($validated);

        return redirect()
            ->route('entrepreneurs.show', $entrepreneur)
            ->with('success', 'Emprendedor actualizado exitosamente');
    }

    public function destroy(Entrepreneur $entrepreneur)
    {
        if ($entrepreneur->logo) {
            Storage::disk('public')->delete($entrepreneur->logo);
        }

        $entrepreneur->delete();

        return redirect()
            ->route('entrepreneurs.index')
            ->with('success', 'Emprendedor eliminado exitosamente');
    }

    public function apiIndex(Request $request)
    {
        $entrepreneurs = Entrepreneur::active()
            ->verified()
            ->when($request->search, function($q) use ($request) {
                $q->where('business_name', 'like', '%' . $request->search . '%');
            })
            ->select(['id', 'business_name', 'email', 'phone', 'logo', 'average_rating'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $entrepreneurs
        ]);
    }
}