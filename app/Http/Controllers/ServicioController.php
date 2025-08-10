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
    public function store(Request $request)
    {
        try {
            // Log de datos recibidos para debugging
            Log::info('=== INICIO GUARDAR SERVICIO ===');
            Log::info('Datos recibidos:', $request->all());
            Log::info('Archivos recibidos:', array_keys($request->allFiles()));
            
            // Validación personalizada con mensajes específicos
            $validator = Validator::make($request->all(), [
                'nombre_servicio' => 'required|string|max:255',
                'categoria' => 'required|string|in:consultoria,reparacion,educacion,salud,belleza,limpieza',
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
                Log::warning('Validación falló:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Verificar si el usuario está autenticado (si usas autenticación)
            /* if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado',
                    'errors' => ['auth' => ['Debes iniciar sesión para crear un servicio']]
                ], 401);
            } */

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
                    Log::info('Procesando imagen principal...');
                    $file = $request->file('imagen_principal');
                    Log::info('Archivo imagen principal:', [
                        'nombre' => $file->getClientOriginalName(),
                        'tamaño' => $file->getSize(),
                        'tipo' => $file->getMimeType()
                    ]);
                    
                    $imagenPrincipal = $file->store('servicios/principales', 'public');
                    Log::info('Imagen principal guardada en:', $imagenPrincipal);
                } catch (Exception $e) {
                    Log::error('Error al procesar imagen principal:', ['error' => $e->getMessage()]);
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
                    Log::info('Procesando galería de imágenes...');
                    foreach ($request->file('galeria_imagenes') as $index => $imagen) {
                        Log::info("Procesando imagen de galería {$index}:", [
                            'nombre' => $imagen->getClientOriginalName(),
                            'tamaño' => $imagen->getSize(),
                            'tipo' => $imagen->getMimeType()
                        ]);
                        
                        $rutaImagen = $imagen->store('servicios/galeria', 'public');
                        $galeriaImagenes[] = $rutaImagen;
                        Log::info("Imagen de galería {$index} guardada en:", $rutaImagen);
                    }
                } catch (Exception $e) {
                    Log::error('Error al procesar galería de imágenes:', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar las imágenes de la galería',
                        'errors' => ['galeria_imagenes' => ['Error al guardar las imágenes: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Crear servicio
            try {
                Log::info('Creando servicio en base de datos...');
                $datosServicio = [
                    'nombre_servicio' => $request->nombre_servicio,
                    'categoria' => $request->categoria,
                    'descripcion' => $request->descripcion,
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'precio_base' => $request->precio_base,
                    'horario_atencion' => $request->horario_atencion,
                    'imagen_principal' => $imagenPrincipal,
                    'galeria_imagenes' => empty($galeriaImagenes) ? null : json_encode($galeriaImagenes),
                    // 'user_id' => auth()->id(), // Descomenta si usas autenticación
                ];
                
                Log::info('Datos a guardar:', $datosServicio);
                
                $servicio = Servicio::create($datosServicio);
                
                Log::info('Servicio creado exitosamente:', ['id' => $servicio->id]);
                Log::info('=== FIN GUARDAR SERVICIO ===');

                return response()->json([
                    'success' => true,
                    'message' => 'Servicio guardado exitosamente',
                    'data' => $servicio
                ], 201);
                
            } catch (Exception $e) {
                Log::error('Error al crear servicio en base de datos:', [
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
            Log::error('Error general al guardar servicio:', [
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
}