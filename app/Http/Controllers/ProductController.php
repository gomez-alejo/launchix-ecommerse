<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lista productos
    public function index()
    {
        $products = Product::all();
        return view('productos.index', compact('products'));
    }

    // Muestra producto
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('productos.show', compact('product'));
    }

    // Mostrar formulario edición
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('productos.edit', compact('product'));
    }

    // Actualizar producto
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return redirect()->route('productos.index')->with('success', 'Producto actualizado');
    }

    // Eliminar producto
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('productos.index')->with('success', 'Producto eliminado');
    }

    
}
