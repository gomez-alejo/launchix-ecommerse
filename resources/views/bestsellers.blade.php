@extends('layouts.app')

@section('title', 'Mas vendido')

@vite('resources/css/berstsellers.css')

@section('content')

    @include('includes.carousel')
    <!-- Contenido principal -->
    <!-- Sección de productos destacados -->
    <section class="py-16 bg-lighter">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 scroll-animate">
                <h2 class="text-4xl font-bold mb-4 text-primary">Productos Destacados</h2>
                <p class="text-xl text-accent">Descubre nuestra selección premium de productos</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="productsGrid">
                <!-- Los productos se generarán dinámicamente -->
            </div>
        </div>
    </section>

    @vite('resources/js/bestSellers.js')
@endsection