<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Product\CreateProductRequest;
use App\Http\Requests\Api\V1\Product\UpdateProductRequest;
use App\Http\Resources\Api\V1\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Lista todos los productos con filtros y paginación
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::with(['entrepreneur', 'user', 'categories', 'images', 'reviews']);

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
    }

    /**
     * Crear un nuevo producto
     */
    public function store(CreateProductRequest $request): JsonResponse
    {
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'entrepreneur_id' => $request->entrepreneur_id,
            'user_id' => $request->user()->id,
        ]);

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
        // Verificar que el usuario puede actualizar este producto
        if ($product->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
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
        if ($product->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
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
        if ($product->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
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
        if (!$request->user()->hasRole('admin')) {
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
}
