<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\CreateProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Entrepreneur;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class ProductController extends Controller
{
    /**
     * Lista todos los productos con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        try {
            $query = Product::with(['entrepreneur', 'user', 'categories', 'images', 'reviews']);

            if ($entrepreneurId = $this->resolveEntrepreneurId($request)) {
                $query->where('entrepreneur_id', $entrepreneurId);
            }

        // Aplicar scopes del modelo
        if ($request->boolean('in_stock')) {
            $query->inStock();
        }

        if ($request->boolean('popular')) {
            $query->popular();
        }

        if ($request->boolean('recent')) {
            $query->recent();
        }

        // Filtros adicionales
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->category . '%');
            });
        }

        if ($request->filled('entrepreneur_id')) {
            $query->where('entrepreneur_id', $request->entrepreneur_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por stock mínimo
        if ($request->filled('min_stock')) {
            $query->where('stock', '>=', $request->min_stock);
        }

        // Búsqueda
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Ordenamiento
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['name', 'price', 'created_at', 'stock', 'sales'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

            $products = $query->paginate($request->get('per_page', 15));
            return ProductResource::collection($products);
        } catch (\Throwable $e) {
            // Log del error para debugging
            Log::error('Error en ProductController@index', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ProductResource::collection(collect([]))->additional([
                'error' => 'Error al cargar productos',
                'detail' => app()->environment('local') ? $e->getMessage() : 'Contacte soporte',
            ]);
        }
    }

    /**
     * Crear un nuevo producto
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        // Determinar emprendedor autenticado
        $entrepreneurId = $this->resolveEntrepreneurId($request);
        if (!$entrepreneurId) {
            return response()->json([
                'message' => 'No se pudo identificar al emprendedor autenticado'
            ], 401);
        }

        // Datos validados (incluye 'category' tras prepareForValidation)
        $data = $request->validated();
        $user = $request->user();

        // Merge de user_id y limpieza de archivos antes de persistir
        $data['user_id'] = $user instanceof Entrepreneur ? null : ($user->id ?? null);
        $data['entrepreneur_id'] = $entrepreneurId;
        unset($data['main_image'], $data['gallery_images']);

        // Procesar imagen principal si viene adjunta
        if ($request->hasFile('main_image')) {
            $data['main_image'] = $request->file('main_image')->store('products/main', 'public');
        }

        // Procesar galería si viene adjunta
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                if ($image->isValid()) {
                    $galleryPaths[] = $image->store('products/gallery', 'public');
                }
            }

            if (!empty($galleryPaths)) {
                $data['gallery_images'] = $galleryPaths;
            }
        }

    $product = Product::create($data);

        // Cargar relaciones para la respuesta
        $product->load(['entrepreneur', 'user']);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'data' => new ProductResource($product)
        ], 201);
    }

    /**
     * Mostrar un producto específico
     */
    public function show(Product $product): JsonResponse
    {
        // Cargar relaciones disponibles
        $product->load(['entrepreneur', 'user']);

        return response()->json([
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Actualizar un producto
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $user = $request->user();

        // Verificar que el usuario puede actualizar este producto
        if (!$this->userOwnsProduct($user, $product) && !$this->userIsAdmin($user)) {
            return response()->json([
                'message' => 'No tienes permisos para actualizar este producto'
            ], 403);
        }

        $product->update($request->validated());

        // Cargar relaciones para la respuesta
        $product->load(['entrepreneur', 'user']);

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Eliminar un producto (soft delete)
     */
    public function destroy(Product $product, Request $request): JsonResponse
    {
        // Verificar que el usuario puede eliminar este producto
            $user = $request->user();

            // Verificar que el usuario puede eliminar este producto
            if (!$this->userOwnsProduct($user, $product) && !$this->userIsAdmin($user)) {
            return response()->json([
                'message' => 'No tienes permisos para eliminar este producto'
            ], 403);
        }

        $product->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente'
        ]);
    }

    /**
     * Cambiar el estado de un producto
     */
    public function toggleStatus(Product $product, Request $request): JsonResponse
    {
        // Verificar permisos
            $user = $request->user();

            // Verificar permisos
            if (!$this->userOwnsProduct($user, $product) && !$this->userIsAdmin($user)) {
            return response()->json([
                'message' => 'No tienes permisos para cambiar el estado de este producto'
            ], 403);
        }

        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);

        $statusMessage = $newStatus === 'active' ? 'activado' : 'desactivado';

        return response()->json([
            'message' => "Producto {$statusMessage} exitosamente",
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Cambiar si un producto está destacado
     */
    public function toggleFeatured(Product $product, Request $request): JsonResponse
    {
        // Solo administradores pueden destacar productos
        if (!$this->userIsAdmin($request->user())) {
            return response()->json([
                'message' => 'Solo los administradores pueden destacar productos'
            ], 403);
        }

        $product->update(['featured' => !$product->featured]);

        return response()->json([
            'message' => $product->featured ? 'Producto destacado' : 'Producto ya no está destacado',
            'data' => new ProductResource($product)
        ]);
    }

    /**
     * Obtener productos relacionados/similares
     */
    public function related(Product $product): AnonymousResourceCollection
    {
        $relatedProducts = Product::with(['entrepreneur', 'user'])
            ->where('id', '!=', $product->id)
            ->where('entrepreneur_id', $product->entrepreneur_id)
            ->inStock()
            ->limit(6)
            ->get();

        return ProductResource::collection($relatedProducts);
    }

    /**
     * Productos más populares (por vistas)
     */
    public function popular(): AnonymousResourceCollection
    {
        $products = Product::with(['entrepreneur', 'user'])
            ->popular()
            ->inStock()
            ->limit(10)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Productos destacados
     */
    public function featured(): AnonymousResourceCollection
    {
        $products = Product::with(['entrepreneur', 'user'])
            ->orderBy('sales', 'desc') // Los más vendidos como "destacados"
            ->inStock()
            ->limit(8)
            ->get();

        return ProductResource::collection($products);
    }

    /**
     * Determina si el usuario autenticado es propietario del producto
     */
    private function userOwnsProduct($user, Product $product): bool
    {
        if (!$user) {
            return false;
        }

        if ($user instanceof Entrepreneur) {
            return (int) $product->entrepreneur_id === (int) $user->id;
        }

        return (int) $product->user_id === (int) $user->id;
    }

    /**
     * Determina si el usuario autenticado tiene rol de administrador
     */
    private function userIsAdmin($user): bool
    {
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole('admin');
        }

        if (method_exists($user, 'roles')) {
            return $user->roles()->where('name', 'admin')->exists();
        }

        return false;
    }

    /**
     * Obtiene el ID del emprendedor asociado al usuario autenticado
     */
    private function resolveEntrepreneurId(Request $request): ?int
    {
        $user = $request->user();
        if ($user instanceof Entrepreneur) {
            return (int) $user->id;
        }

        if ($user && method_exists($user, 'entrepreneur')) {
            $entrepreneur = $user->entrepreneur;
            if ($entrepreneur instanceof Entrepreneur) {
                return (int) $entrepreneur->id;
            }
        }

        if ($user && property_exists($user, 'entrepreneur_id') && $user->entrepreneur_id) {
            return (int) $user->entrepreneur_id;
        }

        if (!$user && $token = $request->bearerToken()) {
            $accessToken = PersonalAccessToken::findToken($token);
            if ($accessToken && $accessToken->tokenable instanceof Entrepreneur) {
                return (int) $accessToken->tokenable_id;
            }
        }

        return null;
    }
}
