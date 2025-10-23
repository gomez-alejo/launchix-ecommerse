<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Entrepreneur;
use App\Models\Servicio;
use Exception;
use Laravel\Sanctum\PersonalAccessToken;

class ServicioController extends Controller
{
    /**
     * Resuelve el ID del emprendedor dueño a partir del usuario autenticado
     * (soporta tokens emitidos por Entrepreneur o por User con relación entrepreneur)
     */
    private function resolveOwnerId(Request $request): ?int
    {
        $user = $request->user();

        Log::info('resolveOwnerId', [
            'request_user_type' => $user ? get_class($user) : null,
            'request_user_id' => $user->id ?? null,
            'guard_web' => Auth::id(),
            'guard_entrepreneur' => Auth::guard('entrepreneur')->id(),
            'entrepreneur_id_param' => $request->input('entrepreneur_id'),
        ]);
        if (!$user) {
            if ($token = $request->bearerToken()) {
                $accessToken = PersonalAccessToken::findToken($token);
                if ($accessToken && $accessToken->tokenable instanceof Entrepreneur) {
                    return (int) $accessToken->tokenable_id;
                }
            }

            return null;
        }

        if ($user instanceof Entrepreneur) {
            return $user->id;
        }

        if (method_exists($user, 'entrepreneur')) {
            $entrepreneur = $user->entrepreneur;
            if ($entrepreneur) {
                return $entrepreneur->id;
            }
        }

        if (property_exists($user, 'entrepreneur_id') && $user->entrepreneur_id) {
            return $user->entrepreneur_id;
        }

        return null;
    }
    /**
     * Obtener solo los servicios del usuario autenticado (emprendedor)
     * Retorna JSON para AJAX o la vista para uso tradicional.
     */
    public function misServicios(Request $request)
    {
        $ownerId = $this->resolveOwnerId($request);
        if (!$ownerId) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            } else {
                return redirect()->route('login.entrepreneur');
            }
        }

        $servicios = Servicio::where('user_id', $ownerId)->latest()->get();
        $entrepreneur = Entrepreneur::find($ownerId);
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $servicios->map(fn(Servicio $servicio) => $this->formatService($servicio))
            ]);
        } else {
            return view('modals.login-items.entrepreneur.ServicesSection', [
                'servicios' => $servicios,
                'entrepreneur' => $entrepreneur
            ]);
        }
    }

    /**
     * Obtener todos los servicios
     */
    public function index(Request $request)
    {
        $ownerId = $this->resolveOwnerId($request);

        if ($request->ajax() || $request->wantsJson()) {
            if (!$ownerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            $servicios = Servicio::with('entrepreneur')
                ->where('user_id', $ownerId)
                ->latest()
                ->get()
                ->map(fn(Servicio $servicio) => $this->formatService($servicio));

            return response()->json([
                'success' => true,
                'data' => $servicios
            ]);
        }

        return view('services');
    }

    /**
     * Obtener un servicio específico con información del emprendedor (público)
     */
    public function getServiceDetails($id)
    {
        try {
            $servicio = Servicio::with('entrepreneur')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatService($servicio)
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener detalles del servicio', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ], 404);
        }
    }

    /**
     * Obtener un servicio específico (formato compatible para JS)
     */
    public function show(Request $request, $id)
    {
        try {
            $servicio = Servicio::with('entrepreneur')->findOrFail($id);
            $ownerId = $this->resolveOwnerId($request);
            if (!$ownerId || $servicio->user_id !== $ownerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }
            return response()->json([
                'success' => true,
                'data' => $this->formatService($servicio)
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener servicio', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 404);
        }
    }

    /**
     * Crear un nuevo servicio
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre_servicio' => 'required|string|max:255',
                'categoria' => 'required|string',
                'descripcion' => 'required|string|min:10',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                // Limite máximo ajustado según frontend (999999999)
                'precio_base' => 'nullable|numeric|min:0|max:999999999',
                'horario_atencion' => 'nullable|string|max:255',
                'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $ownerId = $this->resolveOwnerId($request);
            if (!$ownerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado'
                ], 401);
            }

            if (!Entrepreneur::whereKey($ownerId)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El emprendedor especificado no existe',
                    'errors' => ['entrepreneur_id' => ['El emprendedor especificado no existe']]
                ], 422);
            }

            // Procesar imágenes
            $imagenPrincipal = null;
            if ($request->hasFile('imagen_principal')) {
                $imagenPrincipal = $request->file('imagen_principal')->store('servicios/principales', 'public');
            }
            $galeriaImagenes = [];
            if ($request->hasFile('galeria_imagenes')) {
                foreach ($request->file('galeria_imagenes') as $imagen) {
                    $galeriaImagenes[] = $imagen->store('servicios/galeria', 'public');
                }
            }

            $servicio = Servicio::create([
                'nombre_servicio' => $request->nombre_servicio,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'precio_base' => $request->precio_base,
                'horario_atencion' => $request->horario_atencion,
                'imagen_principal' => $imagenPrincipal,
                'galeria_imagenes' => empty($galeriaImagenes) ? null : $galeriaImagenes,
                'user_id' => $ownerId,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Servicio guardado exitosamente',
                'data' => $this->formatService($servicio->fresh('entrepreneur'))
            ], 201);
        } catch (Exception $e) {
            Log::error('Error al crear servicio', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno al guardar servicio',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Actualizar un servicio existente
     */
    public function update(Request $request, $id)
    {
        try {
            $servicio = Servicio::with('entrepreneur')->findOrFail($id);
            $ownerId = $this->resolveOwnerId($request);
            if (!$ownerId || $servicio->user_id !== $ownerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'nombre_servicio' => 'required|string|max:255',
                'categoria' => 'required|string',
                'descripcion' => 'required|string|min:10',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'precio_base' => 'nullable|numeric|min:0',
                'horario_atencion' => 'nullable|string|max:255',
                'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $datosActualizar = [
                'nombre_servicio' => $request->nombre_servicio,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'precio_base' => $request->precio_base,
                'horario_atencion' => $request->horario_atencion,
            ];

            // Imagen principal
            if ($request->hasFile('imagen_principal')) {
                if ($servicio->imagen_principal && Storage::disk('public')->exists($servicio->imagen_principal)) {
                    Storage::disk('public')->delete($servicio->imagen_principal);
                }
                $datosActualizar['imagen_principal'] = $request->file('imagen_principal')->store('servicios/principales', 'public');
            }

            // Galería
            if ($request->hasFile('galeria_imagenes')) {
                if ($servicio->galeria_imagenes) {
                    foreach ($servicio->galeria_imagenes as $img) {
                        if (Storage::disk('public')->exists($img)) {
                            Storage::disk('public')->delete($img);
                        }
                    }
                }
                $galeria = [];
                foreach ($request->file('galeria_imagenes') as $imagen) {
                    $galeria[] = $imagen->store('servicios/galeria', 'public');
                }
                $datosActualizar['galeria_imagenes'] = $galeria;
            }

            $servicio->update($datosActualizar);
            $servicio->refresh()->load('entrepreneur');

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado exitosamente',
                'data' => $this->formatService($servicio)
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Servicio no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
                'errors' => ['general' => ['El servicio con ID ' . $id . ' no existe']]
            ], 404);
        } catch (Exception $e) {
            Log::error('Error general al actualizar servicio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'errors' => ['general' => ['Error inesperado: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Eliminar un servicio
     */
    public function destroy(Request $request, $id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            $ownerId = $this->resolveOwnerId($request);
            if (!$ownerId || $servicio->user_id !== $ownerId) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }

            if ($servicio->imagen_principal && Storage::disk('public')->exists($servicio->imagen_principal)) {
                Storage::disk('public')->delete($servicio->imagen_principal);
            }

            if ($servicio->galeria_imagenes) {
                foreach ($servicio->galeria_imagenes as $imagen) {
                    if (Storage::disk('public')->exists($imagen)) {
                        Storage::disk('public')->delete($imagen);
                    }
                }
            }

            $servicio->delete();

            Log::info('Servicio eliminado', ['id' => $id]);
            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado exitosamente'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Servicio no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Servicio no encontrado',
                'errors' => ['general' => ['El servicio con ID ' . $id . ' no existe']]
            ], 404);
        } catch (Exception $e) {
            Log::error('Error al eliminar servicio', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el servicio',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Formatea la respuesta estándar de un servicio para la API
     */
    private function formatService(Servicio $servicio): array
    {
        $servicio->loadMissing('entrepreneur');

        return [
            'id' => $servicio->id,
            'entrepreneur_id' => $servicio->user_id,
            'nombre_servicio' => $servicio->nombre_servicio,
            'categoria' => $servicio->categoria,
            'descripcion' => $servicio->descripcion,
            'direccion' => $servicio->direccion,
            'telefono' => $servicio->telefono,
            'precio_base' => $servicio->precio_base,
            'horario_atencion' => $servicio->horario_atencion,
            'imagen_principal' => $servicio->imagen_principal,
            'galeria_imagenes' => $servicio->galeria_imagenes ?: [],
            'status' => $servicio->status,
            'created_at' => $servicio->created_at,
            'updated_at' => $servicio->updated_at,
            'entrepreneur' => $servicio->entrepreneur ? [
                'id' => $servicio->entrepreneur->id,
                'full_name' => $servicio->entrepreneur->full_name,
                'first_name' => $servicio->entrepreneur->first_name,
                'last_name' => $servicio->entrepreneur->last_name,
                'profile_photo_url' => $servicio->entrepreneur->profile_photo_url,
                'city' => $servicio->entrepreneur->city,
                'profile_description' => $servicio->entrepreneur->profile_description,
            ] : null,
        ];
    }
}
