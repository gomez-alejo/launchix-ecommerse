<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(Request $request, \App\Models\Product $product)
    {
        // ...lógica de compra, validaciones, creación de la orden, etc...

        // Incrementar el contador de ventas del producto
        $product->increment('sales');

        // ...resto de la lógica, redirección o respuesta...
        return redirect()->back()->with('success', 'Compra realizada y ventas actualizadas.');
    }
}
