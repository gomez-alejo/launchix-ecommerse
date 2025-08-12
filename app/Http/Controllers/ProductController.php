<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    // Elimina o ajusta el constructor si existe:
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index', 'show']);
    // }

    // Mostrar todos los productos (público)
    public function index()
    {
        // Si no tienes productos reales, solo retorna la vista Blade
        return view('products');
    }

    // Mostrar detalle de producto (público)
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Mostrar formulario de creación de productos
    public function create()
    {
        return view('products.create');
    }

    // Almacenar un nuevo producto
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:150',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category' => 'required|string',
            ]);

            $product = Product::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'price' => $validatedData['price'],
                'stock' => $validatedData['stock'],
                'category' => $validatedData['category'],
                'entrepreneur_id' => Auth::guard('entrepreneur')->id(),
                'user_id' => Auth::id(),
            ]);

            Log::info('Producto creado con éxito: ' . json_encode($product));

            return response()->json([
                'success' => true, 
                'message' => 'Producto publicado con éxito.',
                'product' => $product
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación en creación: ' . json_encode($e->errors()));
            
            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error al crear el producto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false, 
                'message' => 'Hubo un error al publicar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mostrar formulario de edición
    public function edit(Product $product)
    {
        return response()->json($product);
    }

    // Actualizar un producto
    public function update(Request $request, Product $product)
    {
        try {
            // Log de los datos de la solicitud
            Log::info('Datos recibidos para actualizar el producto: ' . json_encode($request->all()));
            Log::info('Content-Type: ' . $request->header('Content-Type'));

            // Validar los datos
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'category' => 'required|string',
            ]);

            // Log de los datos validados
            Log::info('Datos validados: ' . json_encode($validatedData));

            // Actualizar el producto
            $product->update($validatedData);

            // Log de éxito
            Log::info('Producto actualizado con éxito.');

            return response()->json([
                'success' => true,
                'message' => 'Producto actualizado con éxito.',
                'product' => $product->fresh()
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log específico para errores de validación
            Log::error('Errores de validación: ' . json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Error de validación: ' . $e->getMessage(),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Log del error general
            Log::error('Error al actualizar el producto: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    // Eliminar un producto
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            
            Log::info('Producto eliminado con éxito: ' . $product->id);

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado con éxito.'
            ]);
        } catch (\Exception $e) {
            Log::error('Error al eliminar el producto: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    // Mostrar productos más vendidos (solo muestra algunos productos de ejemplo)
    public function bestSellers()
    {
        $products = Product::take(6)->get();
        return view('bestsellers', compact('products'));
    }
}

