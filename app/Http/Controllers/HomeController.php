<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Servicio;

class HomeController extends Controller
{
    public function index()
    {
        // Datos de ejemplo para el home dinámico
        $carouselItems = [
            ['image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1200&h=400&fit=crop', 'title' => '¡Nuevos Smartphones!'],
            ['image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1200&h=400&fit=crop', 'title' => 'Cocina con Estilo'],
            ['image' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?w=1200&h=400&fit=crop', 'title' => 'Servicios para tu Auto'],
        ];
        $promos = [
            ['bg' => 'bg-yellow-100', 'icon' => 'fas fa-tags', 'iconColor' => 'text-yellow-500', 'title' => 'Hasta 40% OFF', 'desc' => 'En productos seleccionados de tecnología.'],
            ['bg' => 'bg-blue-100', 'icon' => 'fas fa-truck', 'iconColor' => 'text-blue-500', 'title' => 'Envío Gratis', 'desc' => 'En compras superiores a $200.000.'],
            ['bg' => 'bg-green-100', 'icon' => 'fas fa-gift', 'iconColor' => 'text-green-500', 'title' => 'Bonos y Regalos', 'desc' => 'Por cada compra en servicios seleccionados.'],
        ];
        // Muestra los primeros 4 productos y servicios (sin filtro is_featured)
        $featuredProducts = Product::take(4)->get();
        $featuredServices = Servicio::take(4)->get();

        return view('home', compact('carouselItems', 'promos', 'featuredProducts', 'featuredServices'));
    }
}
    