<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Obtener todos los productos
     */
    public function index()
    {
        try {
            $products = Product::where('entrepreneur_id', Auth::guard('entrepreneur')->id())
                              ->orderBy('created_at', 'desc')
                              ->get();

            $productsFormatted = $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => is_string($product->category) ? $product->category : 'General',
                    'description' => $product->description,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                    'gallery_images' => $product->gallery_images ? array_map(function($image) {
                        return asset('storage/' . $image);
                    }, $product->gallery_images) : [],
                    'created_at' => $product->created_at->format('d/m/Y H:i'),
                    'updated_at' => $product->updated_at->format('d/m/Y H:i')
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $productsFormatted
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener productos', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los productos',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Obtener un producto específico
     */
    public function show($id)
    {
        try {
            $product = Product::findOrFail($id);
            $productFormatted = [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                'gallery_images' => $product->gallery_images ? array_map(function($image) {
                    return asset('storage/' . $image);
                }, $product->gallery_images) : []
            ];

            return response()->json([
                'success' => true,
                'data' => $productFormatted
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener producto', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 404);
        }
    }

    /**
     * Crear un nuevo producto
     */
    public function store(Request $request)
    {
        // Alias: si llega 'categoria', úsala como 'category'
        if (!$request->has('category') && $request->has('categoria')) {
            $request->merge(['category' => $request->input('categoria')]);
        }

        // Manejo de categoría como array
        $cat = $request->input('category');
        if (is_array($cat)) {
            $cat = $cat['slug'] ?? $cat['name'] ?? reset($cat) ?? null;
        }
        $request->merge(['category' => $cat]);

        try {
            Log::info('=== INICIO GUARDAR PRODUCTO ===');
            Log::info('Datos recibidos', ['data' => $request->all()]);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:ropa,calzado,accesorios,hogar,electronica,deportes,belleza,juguetes,libros,otros',
                'description' => 'required|string|min:10',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'name.required' => 'El nombre del producto es obligatorio',
                'name.max' => 'El nombre del producto no debe exceder 255 caracteres',
                'category.required' => 'La categoría es obligatoria',
                'category.in' => 'La categoría seleccionada no es válida',
                'description.required' => 'La descripción es obligatoria',
                'description.min' => 'La descripción debe tener al menos 10 caracteres',
                'price.required' => 'El precio es obligatorio',
                'price.numeric' => 'El precio debe ser un número',
                'price.min' => 'El precio debe ser mayor o igual a 0',
                'stock.required' => 'El stock es obligatorio',
                'stock.integer' => 'El stock debe ser un número entero',
                'stock.min' => 'El stock debe ser mayor o igual a 0',
                'main_image.image' => 'El archivo debe ser una imagen',
                'main_image.mimes' => 'La imagen principal debe ser de tipo: jpeg, png, jpg, gif, webp',
                'main_image.max' => 'La imagen principal no debe superar los 2MB',
                'gallery_images.*.image' => 'Todos los archivos de la galería deben ser imágenes',
                'gallery_images.*.mimes' => 'Las imágenes de la galería deben ser de tipo: jpeg, png, jpg, gif, webp',
                'gallery_images.*.max' => 'Cada imagen de la galería no debe superar los 2MB'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación falló', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            if (!Storage::disk('public')->exists('productos')) {
                Storage::disk('public')->makeDirectory('productos');
                Storage::disk('public')->makeDirectory('productos/principales');
                Storage::disk('public')->makeDirectory('productos/galeria');
            }

            // Procesar imagen principal
            $mainImage = null;
            if ($request->hasFile('main_image')) {
                try {
                    Log::info('Procesando imagen principal');
                    $file = $request->file('main_image');
                    $mainImage = $file->store('productos/principales', 'public');
                    Log::info('Imagen principal guardada', ['path' => $mainImage]);
                } catch (\Exception $e) {
                    Log::error('Error al procesar imagen principal', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la imagen principal',
                        'errors' => ['main_image' => ['Error al guardar la imagen: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Procesar galería de imágenes
            $galleryImages = [];
            if ($request->hasFile('gallery_images')) {
                try {
                    Log::info('Procesando galería de imágenes');
                    foreach ($request->file('gallery_images') as $index => $image) {
                        $imagePath = $image->store('productos/galeria', 'public');
                        $galleryImages[] = $imagePath;
                    }
                    Log::info('Galería procesada', ['count' => count($galleryImages)]);
                } catch (\Exception $e) {
                    Log::error('Error al procesar galería de imágenes', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar las imágenes de la galería',
                        'errors' => ['gallery_images' => ['Error al guardar las imágenes: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Crear producto
            $productData = [
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'main_image' => $mainImage,
                'gallery_images' => empty($galleryImages) ? null : $galleryImages,
                'entrepreneur_id' => Auth::guard('entrepreneur')->id(),
                'user_id' => Auth::id(),
            ];

            $product = new Product($productData);
            $product->save();

            Log::info('Producto creado exitosamente', ['id' => $product->id]);
            Log::info('=== FIN GUARDAR PRODUCTO ===');

            return response()->json([
                'success' => true,
                'message' => 'Producto guardado exitosamente',
                'data' => $product
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error general al guardar producto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (isset($mainImage) && $mainImage && Storage::disk('public')->exists($mainImage)) {
                Storage::disk('public')->delete($mainImage);
            }
            if (!empty($galleryImages)) {
                foreach ($galleryImages as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            return response()->json([
                'success' => false,
                'message' => 'Error interno del servidor',
                'errors' => ['general' => ['Error inesperado: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, $id)
    {
        // Alias: si llega 'categoria', úsala como 'category'
        if (!$request->has('category') && $request->has('categoria')) {
            $request->merge(['category' => $request->input('categoria')]);
        }

        // Manejo de categoría como array
        $cat = $request->input('category');
        if (is_array($cat)) {
            $cat = $cat['slug'] ?? $cat['name'] ?? reset($cat) ?? null;
        }
        $request->merge(['category' => $cat]);

        try {
            Log::info('=== INICIO ACTUALIZAR PRODUCTO ===');
            Log::info('ID del producto', ['id' => $id]);
            Log::info('Datos recibidos', ['data' => $request->all()]);

            $product = Product::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'category' => 'required|string|in:ropa,calzado,accesorios,hogar,electronica,deportes,belleza,juguetes,libros,otros',
                'description' => 'required|string|min:10',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ], [
                'name.required' => 'El nombre del producto es obligatorio',
                'name.max' => 'El nombre del producto no debe exceder 255 caracteres',
                'category.required' => 'La categoría es obligatoria',
                'category.in' => 'La categoría seleccionada no es válida',
                'description.required' => 'La descripción es obligatoria',
                'description.min' => 'La descripción debe tener al menos 10 caracteres',
                'price.required' => 'El precio es obligatorio',
                'price.numeric' => 'El precio debe ser un número',
                'price.min' => 'El precio debe ser mayor o igual a 0',
                'stock.required' => 'El stock es obligatorio',
                'stock.integer' => 'El stock debe ser un número entero',
                'stock.min' => 'El stock debe ser mayor o igual a 0',
                'main_image.image' => 'El archivo debe ser una imagen',
                'main_image.mimes' => 'La imagen principal debe ser de tipo: jpeg, png, jpg, gif, webp',
                'main_image.max' => 'La imagen principal no debe superar los 2MB',
                'gallery_images.*.image' => 'Todos los archivos de la galería deben ser imágenes',
                'gallery_images.*.mimes' => 'Las imágenes de la galería deben ser de tipo: jpeg, png, jpg, gif, webp',
                'gallery_images.*.max' => 'Cada imagen de la galería no debe superar los 2MB'
            ]);

            if ($validator->fails()) {
                Log::warning('Validación falló', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [
                'name' => $request->name,
                'category' => $request->category,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
            ];

            // Procesar nueva imagen principal si se proporciona
            if ($request->hasFile('main_image')) {
                try {
                    Log::info('Procesando nueva imagen principal');
                    if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                        Storage::disk('public')->delete($product->main_image);
                    }
                    $file = $request->file('main_image');
                    $newMainImage = $file->store('productos/principales', 'public');
                    $updateData['main_image'] = $newMainImage;
                } catch (\Exception $e) {
                    Log::error('Error al procesar nueva imagen principal', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar la nueva imagen principal',
                        'errors' => ['main_image' => ['Error al guardar la imagen: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Procesar galería de imágenes si se proporciona
            if ($request->hasFile('gallery_images')) {
                try {
                    Log::info('Procesando nueva galería de imágenes');
                    if ($product->gallery_images) {
                        foreach ($product->gallery_images as $image) {
                            if (Storage::disk('public')->exists($image)) {
                                Storage::disk('public')->delete($image);
                            }
                        }
                    }
                    $newGallery = [];
                    foreach ($request->file('gallery_images') as $index => $image) {
                        $imagePath = $image->store('productos/galeria', 'public');
                        $newGallery[] = $imagePath;
                    }
                    $updateData['gallery_images'] = $newGallery;
                } catch (\Exception $e) {
                    Log::error('Error al procesar nueva galería', ['error' => $e->getMessage()]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Error al procesar las nuevas imágenes de la galería',
                        'errors' => ['gallery_images' => ['Error al guardar las imágenes: ' . $e->getMessage()]]
                    ], 500);
                }
            }

            // Actualizar producto
            $product->fill($updateData);
            $product->save();

            $productFormatted = [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
                'gallery_images' => $product->gallery_images ? array_map(function($image) {
                    return asset('storage/' . $image);
                }, $product->gallery_images) : []
            ];

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado exitosamente',
                'data' => $productFormatted
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Producto no encontrado', ['id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado',
                'errors' => ['general' => ['El producto con ID ' . $id . ' no existe']]
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error general al actualizar producto', [
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
     * Eliminar un producto
     */
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    if (Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }
            $product->delete();
            Log::info('Producto eliminado', ['id' => $id]);
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar producto', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto',
                'errors' => ['general' => ['Error: ' . $e->getMessage()]]
            ], 500);
        }
    }

    /**
     * API específica para obtener productos (siempre devuelve JSON)
     */
    public function apiIndex(Request $request)
    {
        try {
            Log::info('apiIndex called', [
                'is_ajax' => $request->ajax(),
                'expects_json' => $request->expectsJson(),
                'accept_header' => $request->header('Accept'),
                'x_requested_with' => $request->header('X-Requested-With')
            ]);

            $products = Product::with('entrepreneur')
                ->orderBy('created_at', 'desc')
                ->get();

            $transformedProducts = $products->map(function ($product) {
                return $this->transformProductForPublic($product);
            });

            return response()->json([
                'success' => true,
                'data' => $transformedProducts,
                'total' => $products->count()
            ], 200, [
                'Content-Type' => 'application/json'
            ]);

        } catch (\Exception $e) {
            Log::error('Error en apiIndex: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los productos',
                'error' => config('app.debug') ? $e->getMessage() : 'Error interno'
            ], 500, [
                'Content-Type' => 'application/json'
            ]);
        }
    }

    /**
     * Transformar producto para la vista pública
     */
    private function transformProductForPublic($product)
    {
        if (!$product->relationLoaded('entrepreneur')) {
            $product->load('entrepreneur');
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'original_price' => $product->original_price ? (float) $product->original_price : null,
            'stock' => (int) $product->stock,
            'sku' => $product->sku,
            'brand' => $product->brand,
            'is_active' => true,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
            'main_image' => $product->main_image ? asset('storage/' . $product->main_image) : null,
            'gallery_images' => $product->gallery_images ? array_map(fn($image) => asset('storage/' . $image), $product->gallery_images) : [],
            'category' => [
                'id' => null,
                'name' => is_string($product->category) ? $product->category : 'General',
                'slug' => is_string($product->category) ?
                    strtolower(str_replace([' ', 'ó', 'é', 'í', 'ú', 'ñ'], ['', 'o', 'e', 'i', 'u', 'n'], $product->category)) :
                    'general'
            ],
            'entrepreneur' => $product->entrepreneur ? [
                'id' => $product->entrepreneur->id,
                'name' => $product->entrepreneur->first_name . ' ' . $product->entrepreneur->last_name,
                'first_name' => $product->entrepreneur->first_name,
                'last_name' => $product->entrepreneur->last_name,
                'business_name' => $product->entrepreneur->business_name ??
                                ($product->entrepreneur->first_name . ' ' . $product->entrepreneur->last_name),
                'avatar' => $product->entrepreneur->avatar ?
                    asset('storage/' . $product->entrepreneur->avatar) :
                    'https://ui-avatars.com/api/?name=' . urlencode($product->entrepreneur->first_name . ' ' . $product->entrepreneur->last_name) . '&background=F77786&color=fff'
            ] : null,
            'rating' => 4.0,
            'reviews_count' => 0,
            'discount_percentage' => $this->calculateDiscountPercentage($product),
            'is_new' => $this->isProductNew($product->created_at),
            'in_stock' => $product->stock > 0,
        ];
    }

    /**
     * Calcular porcentaje de descuento
     */
    private function calculateDiscountPercentage($product)
    {
        if (!$product->original_price || $product->original_price <= $product->price) {
            return 0;
        }
        return round((($product->original_price - $product->price) / $product->original_price) * 100);
    }

    /**
     * Verificar si el producto es nuevo (menos de 30 días)
     */
    private function isProductNew($createdAt)
    {
        return $createdAt && $createdAt->diffInDays(now()) <= 30;
    }

    /**
     * Obtener un producto específico para la vista pública
     */
    public function publicShow($id)
    {
        try {
            $product = Product::with(['entrepreneur'])
                ->findOrFail($id);

            $transformedProduct = $this->transformProductForPublic($product);

            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $transformedProduct
                ]);
            }

            return view('productos.show', compact('product', 'transformedProduct'));

        } catch (\Exception $e) {
            Log::error('Error en show product: ' . $e->getMessage());
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Producto no encontrado'
                ], 404);
            }
            return abort(404);
        }
    }

    /**
     * Buscar productos
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $category = $request->get('category', '');
            $minPrice = $request->get('min_price', 0);
            $maxPrice = $request->get('max_price', PHP_INT_MAX);
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');

            $products = Product::with(['entrepreneur'])
                ->when($query, function ($queryBuilder) use ($query) {
                    return $queryBuilder->where(function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                          ->orWhere('description', 'like', "%{$query}%")
                          ->orWhere('brand', 'like', "%{$query}%");
                    });
                })
                ->when($category, function ($queryBuilder) use ($category) {
                    return $queryBuilder->where('category', $category);
                })
                ->whereBetween('price', [$minPrice, $maxPrice])
                ->orderBy($sortBy, $sortOrder)
                ->get();

            $transformedProducts = $products->map(function ($product) {
                return $this->transformProductForPublic($product);
            });

            return response()->json([
                'success' => true,
                'data' => $transformedProducts,
                'total' => $products->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error en search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error en la búsqueda'
            ], 500);
        }
    }
}
