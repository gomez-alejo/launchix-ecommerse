<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Servicio;
use Exception;

class ServicioController extends Controller
{
    /**
     * Obtener solo los servicios del usuario autenticado (emprendedor)
     * Retorna JSON para AJAX o la vista para uso tradicional.
     */
    public function misServicios(Request $request)
    {
        $user = auth('entrepreneur')->user();
        if (!$user) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'No autenticado'], 401);
            } else {
                return redirect()->route('login.entrepreneur');
            }
        }
        $servicios = Servicio::where('user_id', $user->id)->latest()->get();
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $servicios
            ]);
        } else {
            return view('modals.login-items.entrepreneur.ServicesSection', [
                'servicios' => $servicios,
                'entrepreneur' => $user
            ]);
        }
    }

    /**
     * Obtener todos los servicios
     */
    public function index()
    {
        if (request()->ajax() || request()->wantsJson()) {
            $servicios = Servicio::with('entrepreneur')->latest()->get();

            // Formatear los datos para incluir información del emprendedor
            $serviciosFormateados = $servicios->map(function ($servicio) {
                return [
                    'id' => $servicio->id,
                    'nombre_servicio' => $servicio->nombre_servicio,
                    'categoria' => $servicio->categoria,
                    'descripcion' => $servicio->descripcion,
                    'direccion' => $servicio->direccion,
                    'telefono' => $servicio->telefono,
                    'precio_base' => $servicio->precio_base,
                    'horario_atencion' => $servicio->horario_atencion,
                    'imagen_principal' => $servicio->imagen_principal,
                    'galeria_imagenes' => $servicio->galeria_imagenes ?: [],
                    'created_at' => $servicio->created_at,
                    'entrepreneur' => $servicio->entrepreneur ? [
                        'id' => $servicio->entrepreneur->id,
                        'full_name' => $servicio->entrepreneur->full_name,
                        'first_name' => $servicio->entrepreneur->first_name,
                        'last_name' => $servicio->entrepreneur->last_name,
                        'profile_photo_url' => $servicio->entrepreneur->profile_photo_url,
                        'city' => $servicio->entrepreneur->city,
                        'profile_description' => $servicio->entrepreneur->profile_description,
                    ] : null
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $serviciosFormateados
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

            $servicioFormateado = [
                'id' => $servicio->id,
                'nombre_servicio' => $servicio->nombre_servicio,
                'categoria' => $servicio->categoria,
                'descripcion' => $servicio->descripcion,
                'direccion' => $servicio->direccion,
                'telefono' => $servicio->telefono,
                'precio_base' => $servicio->precio_base,
                'horario_atencion' => $servicio->horario_atencion,
                'imagen_principal' => $servicio->imagen_principal,
                'galeria_imagenes' => $servicio->galeria_imagenes ?: [],
                'created_at' => $servicio->created_at,
                'entrepreneur' => $servicio->entrepreneur ? [
                    'id' => $servicio->entrepreneur->id,
                    'full_name' => $servicio->entrepreneur->full_name,
                    'first_name' => $servicio->entrepreneur->first_name,
                    'last_name' => $servicio->entrepreneur->last_name,
                    'profile_photo_url' => $servicio->entrepreneur->profile_photo_url,
                    'city' => $servicio->entrepreneur->city,
                    'profile_description' => $servicio->entrepreneur->profile_description,
                ] : null
            ];

            return response()->json([
                'success' => true,
                'data' => $servicioFormateado
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
    public function show($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            if ($servicio->user_id !== auth('entrepreneur')->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado'
                ], 403);
            }
            // Formato consistente para JS
            $servicioFormateado = [
                'id' => $servicio->id,
                'nombre_servicio' => $servicio->nombre_servicio,
                'categoria' => $servicio->categoria,
                'descripcion' => $servicio->descripcion,
                'direccion' => $servicio->direccion,
                'telefono' => $servicio->telefono,
                'precio_base' => $servicio->precio_base,
                'horario_atencion' => $servicio->horario_atencion,
                'imagen_principal' => $servicio->imagen_principal,
                'galeria_imagenes' => $servicio->galeria_imagenes ?: [],
            ];
            return response()->json([
                'success' => true,
                'data' => $servicioFormateado
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

            $userId = auth('entrepreneur')->id();
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
                'user_id' => $userId,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Servicio guardado exitosamente',
                'data' => $servicio
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
            $servicio = Servicio::findOrFail($id);
            if ($servicio->user_id !== auth('entrepreneur')->id()) {
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

            $servicioFormateado = [
                'id' => $servicio->id,
                'nombre_servicio' => $servicio->nombre_servicio,
                'categoria' => $servicio->categoria,
                'descripcion' => $servicio->descripcion,
                'direccion' => $servicio->direccion,
                'telefono' => $servicio->telefono,
                'precio_base' => $servicio->precio_base,
                'horario_atencion' => $servicio->horario_atencion,
                'imagen_principal' => $servicio->imagen_principal,
                'galeria_imagenes' => $servicio->galeria_imagenes ?: [],
            ];

            return response()->json([
                'success' => true,
                'message' => 'Servicio actualizado exitosamente',
                'data' => $servicioFormateado
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
    public function destroy($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            if ($servicio->user_id !== auth('entrepreneur')->id()) {
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
}
