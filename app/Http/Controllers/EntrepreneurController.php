<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EntrepreneurController extends Controller
{
    // Método para mostrar la vista principal del emprendedor
    public function index()
    {
        if (Auth::guard('entrepreneur')->check()) {
            $entrepreneur = Auth::guard('entrepreneur')->user();
            $products = Product::where('entrepreneur_id', $entrepreneur->id)->get();
            return view('profiles.entrepreneur', compact('products'));
        }

        return redirect()->route('login.entrepreneur')->with('error', 'Debes iniciar sesión para acceder al dashboard.');
    }
}