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
     * Obtener todos los servicios
     */
    public function index()
    {
        try {
            $servicios = Servicio::orderBy('created_at', 'desc')->get();
            
            // Formatear los datos para el frontend
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
                    'imagen_principal' => $servicio->imagen_principal ? asset('storage/' . $servicio->imagen_principal) : null,
                    'galeria_imagenes' => $servicio->galeria_imagenes ? array_map(function($imagen) {
                        return asset('storage/' . $imagen);
                    }, $servicio->galeria_imagenes) : [],
                    'created_at' => $servicio->created_at->format('d/m/Y H:i'),
                    'updated_at' => $servicio->updated_at->format('d/m/Y H:i')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $serviciosFormateados
            ]);
        } catch (Exception $e) {
            Log::error('Error al obtener servicios', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los servicios',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Obtener un servicio específico
     */
    public function show($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            
            $servicioFormateado = [
                'id' => $servicio->id,
                'nombre_servicio' => $servicio->nombre_servicio,
                'categoria' => $servicio->categoria,
                'descripcion' => $servicio->descripcion,
                'direccion' => $servicio->direccion,
                'telefono' => $servicio->telefono,
                'precio_base' => $servicio->precio_base,
                'horario_atencion' => $servicio->horario_atencion,
                'imagen_principal' => $servicio->imagen_principal ? asset('storage/' . $servicio->imagen_principal) : null,
                'galeria_imagenes' => $servicio->galeria_imagenes ? array_map(function($imagen) {
                    return asset('storage/' . $imagen);
                }, $servicio->galeria_imagenes) : []
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
            // Log de datos recibidos para debugging
            Log::info('=== INICIO GUARDAR SERVICIO ===');
            Log::info('Datos recibidos', ['data' => $request->all()]);
            Log::info('Archivos recibidos', ['files' => array_keys($request->allFiles())]);
            
            // Validación personalizada con mensajes específicos
            $validator = Validator::make($request->all(), [
                'nombre_servicio' => 'required|string|max:255',
                'categoria' => 'required|string|in:Tecnología,Hogar y Jardinería,Automotriz,Belleza y Cuidado Personal,Educación y Tutorías,Salud y Bienestar,Eventos y Entretenimiento,Construcción y Reformas,Transporte y Logística,Servicios Profesionales,Otros',
                'descripcion' => 'required|string|min:10',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'precio_base' => 'nullable|numeric|min:0',
                'horario_atencion' => 'nullable|string|max:255',
                'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'nombre_servicio.required' => 'El nombre del servicio es obligatorio',
                'nombre_servicio.max' => 'El nombre del servicio no debe exceder 255 caracteres',
                'categoria.required' => 'La categoría es obligatoria',
                'categoria.in' => 'La categoría seleccionada no es válida',
                'descripcion.required' => 'La descripción es obligatoria',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
                'direccion.required' => 'La dirección es obligatoria',
                'direccion.max' => 'La dirección no debe exceder 255 caracteres',
                'telefono.required' => 'El teléfono es obligatorio',
                'telefono.max' => 'El teléfono no debe exceder 20 caracteres',
                'precio_base.numeric' => 'El precio base debe ser un número',
                'precio_base.min' => 'El precio base debe ser mayor o igual a 0',
                'imagen_principal.image' => 'El archivo debe ser una imagen',
                'imagen_principal.mimes' => 'La imagen principal debe ser de tipo: jpeg, png, jpg, gif, webp',
                'imagen_principal.max' => 'La imagen principal no debe superar los 2MB',
                'galeria_imagenes.*.image' => 'Todos los archivos de la galería deben ser imágenes',
                'galeria_imagenes.*.mimes' => 'Las imágenes de la galería deben ser de tipo: jpeg, png, jpg, gif, webp',
                'galeria_imagenes.*.max' => 'Cada imagen de la galería no debe superar los 2MB'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación falló', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar que el directorio de almacenamiento exista
            if (!Storage::disk('public')->exists('servicios')) {
                Storage::disk('public')->makeDirectory('servicios');
                Storage::disk('public')->makeDirectory('servicios/principales');
                Storage::disk('public')->makeDirectory('servicios/galeria');
            }

            // Procesar imagen principal
            $imagenPrincipal = null;
            if ($request->hasFile('imagen_principal')) {
                try {
                    Log::info('Procesando imagen principal');
                    $file = $request->file('imagen_principal');
                    $imagenPrincipal = $file->store('servicios/principales', 'public');
                    Log::info('Imagen principal guardada', ['path' => $imagenPrincipal]);
                } catch (Exception $e) {
                    Log::error('Error al procesar imagen principal', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la imagen principal',
                        'errors' => ['imagen_principal' => ['Error al guardar la imagen: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Procesar galería de imágenes
            $galeriaImagenes = [];
            if ($request->hasFile('galeria_imagenes')) {
                try {
                    Log::info('Procesando galería de imágenes');
                    foreach ($request->file('galeria_imagenes') as $index => $imagen) {
                        $rutaImagen = $imagen->store('servicios/galeria', 'public');
                        $galeriaImagenes[] = $rutaImagen;
                    }
                    Log::info('Galería procesada', ['count' => count($galeriaImagenes)]);
                } catch (Exception $e) {
                    Log::error('Error al procesar galería de imágenes', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar las imágenes de la galería',
                        'errors' => ['galeria_imagenes' => ['Error al guardar las imágenes: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Crear servicio
            try {
                $datosServicio = [
                    'nombre_servicio' => $request->nombre_servicio,
                    'categoria' => $request->categoria,
                    'descripcion' => $request->descripcion,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'precio_base' => $request->precio_base,
                    'horario_atencion' => $request->horario_atencion,
                    'imagen_principal' => $imagenPrincipal,
                    'galeria_imagenes' => empty($galeriaImagenes) ? null : $galeriaImagenes,
                    // 'user_id' => auth()->id(), // Descomenta si usas autenticación
                ];
                
                $servicio = Servicio::create($datosServicio);
                
                Log::info('Servicio creado exitosamente', ['id' => $servicio->id]);
                Log::info('=== FIN GUARDAR SERVICIO ===');

                return response()->json([
                    'success' => true,
                    'message' => 'Servicio guardado exitosamente',
                    'data' => $servicio
                ], 201);
                
            } catch (Exception $e) {
                Log::error('Error al crear servicio en base de datos', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                // Si hay error, eliminar las imágenes que se subieron
                if ($imagenPrincipal && Storage::disk('public')->exists($imagenPrincipal)) {
                    Storage::disk('public')->delete($imagenPrincipal);
                }
                foreach ($galeriaImagenes as $imagen) {
                    if (Storage::disk('public')->exists($imagen)) {
                        Storage::disk('public')->delete($imagen);
                    }
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar en la base de datos',
                    'errors' => ['database' => ['Error de base de datos: ' . $e->getMessage()]]
                ], 500);
            }

        } catch (Exception $e) {
            Log::error('Error general al guardar servicio', [
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
     * Actualizar un servicio existente
     */
    public function update(Request $request, $id)
    {
        try {
            Log::info('=== INICIO ACTUALIZAR SERVICIO ===');
            Log::info('ID del servicio', ['id' => $id]);
            Log::info('Datos recibidos', ['data' => $request->all()]);
            Log::info('Archivos recibidos', ['files' => array_keys($request->allFiles())]);

            // Buscar el servicio
            $servicio = Servicio::findOrFail($id);
            
            // Validación personalizada con mensajes específicos
            $validator = Validator::make($request->all(), [
                'nombre_servicio' => 'required|string|max:255',
                'categoria' => 'required|string|in:Tecnología,Hogar y Jardinería,Automotriz,Belleza y Cuidado Personal,Educación y Tutorías,Salud y Bienestar,Eventos y Entretenimiento,Construcción y Reformas,Transporte y Logística,Servicios Profesionales,Otros',
                'descripcion' => 'required|string|min:10',
                'direccion' => 'required|string|max:255',
                'telefono' => 'required|string|max:20',
                'precio_base' => 'nullable|numeric|min:0',
                'horario_atencion' => 'nullable|string|max:255',
                'imagen_principal' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'galeria_imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'nombre_servicio.required' => 'El nombre del servicio es obligatorio',
                'nombre_servicio.max' => 'El nombre del servicio no debe exceder 255 caracteres',
                'categoria.required' => 'La categoría es obligatoria',
                'categoria.in' => 'La categoría seleccionada no es válida',
                'descripcion.required' => 'La descripción es obligatoria',
                'descripcion.min' => 'La descripción debe tener al menos 10 caracteres',
                'direccion.required' => 'La dirección es obligatoria',
                'direccion.max' => 'La dirección no debe exceder 255 caracteres',
                'telefono.required' => 'El teléfono es obligatorio',
                'telefono.max' => 'El teléfono no debe exceder 20 caracteres',
                'precio_base.numeric' => 'El precio base debe ser un número',
                'precio_base.min' => 'El precio base debe ser mayor o igual a 0',
                'imagen_principal.image' => 'El archivo debe ser una imagen',
                'imagen_principal.mimes' => 'La imagen principal debe ser de tipo: jpeg, png, jpg, gif, webp',
                'imagen_principal.max' => 'La imagen principal no debe superar los 2MB',
                'galeria_imagenes.*.image' => 'Todos los archivos de la galería deben ser imágenes',
                'galeria_imagenes.*.mimes' => 'Las imágenes de la galería deben ser de tipo: jpeg, png, jpg, gif, webp',
                'galeria_imagenes.*.max' => 'Cada imagen de la galería no debe superar los 2MB'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación falló', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Preparar datos para actualizar
            $datosActualizar = [
                'nombre_servicio' => $request->nombre_servicio,
                'categoria' => $request->categoria,
                'descripcion' => $request->descripcion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'precio_base' => $request->precio_base,
                'horario_atencion' => $request->horario_atencion,
            ];

            // Procesar nueva imagen principal si se proporciona
            if ($request->hasFile('imagen_principal')) {
                try {
                    Log::info('Procesando nueva imagen principal');
                    
                    // Eliminar imagen principal anterior si existe
                    if ($servicio->imagen_principal && Storage::disk('public')->exists($servicio->imagen_principal)) {
                        Storage::disk('public')->delete($servicio->imagen_principal);
                        Log::info('Imagen principal anterior eliminada', ['path' => $servicio->imagen_principal]);
                    }
                    
                    // Guardar nueva imagen principal
                    $file = $request->file('imagen_principal');
                    $nuevaImagenPrincipal = $file->store('servicios/principales', 'public');
                    $datosActualizar['imagen_principal'] = $nuevaImagenPrincipal;
                    
                    Log::info('Nueva imagen principal guardada', ['path' => $nuevaImagenPrincipal]);
                } catch (Exception $e) {
                    Log::error('Error al procesar nueva imagen principal', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la nueva imagen principal',
                        'errors' => ['imagen_principal' => ['Error al guardar la imagen: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Procesar galería de imágenes si se proporciona
            if ($request->hasFile('galeria_imagenes')) {
                try {
                    Log::info('Procesando nueva galería de imágenes');
                    
                    // Eliminar galería anterior si existe
                    if ($servicio->galeria_imagenes) {
                        foreach ($servicio->galeria_imagenes as $imagen) {
                            if (Storage::disk('public')->exists($imagen)) {
                                Storage::disk('public')->delete($imagen);
                            }
                        }
                        Log::info('Galería anterior eliminada');
                    }
                    
                    // Guardar nuevas imágenes de la galería
                    $nuevaGaleria = [];
                    foreach ($request->file('galeria_imagenes') as $index => $imagen) {
                        $rutaImagen = $imagen->store('servicios/galeria', 'public');
                        $nuevaGaleria[] = $rutaImagen;
                    }
                    $datosActualizar['galeria_imagenes'] = $nuevaGaleria;
                    
                    Log::info('Nueva galería guardada', ['count' => count($nuevaGaleria)]);
                } catch (Exception $e) {
                    Log::error('Error al procesar nueva galería', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar las nuevas imágenes de la galería',
                        'errors' => ['galeria_imagenes' => ['Error al guardar las imágenes: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Actualizar el servicio en la base de datos
            try {
                $servicio->update($datosActualizar);
                
                Log::info('Servicio actualizado exitosamente', ['id' => $servicio->id]);
                Log::info('=== FIN ACTUALIZAR SERVICIO ===');

                // Formatear respuesta
                $servicioFormateado = [
                    'id' => $servicio->id,
                    'nombre_servicio' => $servicio->nombre_servicio,
                    'categoria' => $servicio->categoria,
                    'descripcion' => $servicio->descripcion,
                    'direccion' => $servicio->direccion,
                    'telefono' => $servicio->telefono,
                    'precio_base' => $servicio->precio_base,
                    'horario_atencion' => $servicio->horario_atencion,
                    'imagen_principal' => $servicio->imagen_principal ? asset('storage/' . $servicio->imagen_principal) : null,
                    'galeria_imagenes' => $servicio->galeria_imagenes ? array_map(function($imagen) {
                        return asset('storage/' . $imagen);
                    }, $servicio->galeria_imagenes) : []
                ];

                return response()->json([
                    'success' => true,
                    'message' => 'Servicio actualizado exitosamente',
                    'data' => $servicioFormateado
                ]);
                
            } catch (Exception $e) {
                Log::error('Error al actualizar servicio en base de datos', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar en la base de datos',
                    'errors' => ['database' => ['Error de base de datos: ' . $e->getMessage()]]
                ], 500);
            }

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
            
            // Eliminar imágenes del almacenamiento
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
            
            // Eliminar el servicio
            $servicio->delete();
            
            Log::info('Servicio eliminado', ['id' => $id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Servicio eliminado exitosamente'
            ]);
            
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