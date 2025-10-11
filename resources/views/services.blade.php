@extends('layouts.app')

@section('title', 'servicios')

@vite('resources/css/services.css')


@php
    $entrepreneur = Auth::guard('entrepreneur')->user();
@endphp

@section('content')


    <!-- Contenedor principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-6">
            <!-- Aside de filtros -->
            <aside class="w-80 bg-white rounded-lg shadow-lg sticky-aside">
                <div class="p-6">
                    <!-- Título de filtros -->
                    <div class="flex items-center mb-6">
                        <i class="fas fa-filter text-primary mr-2"></i>
                        <h2 class="text-xl font-bold text-gray-800">Filtros</h2>
                    </div>

                    <!-- Categorías -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Categorías</h3>
                        <div class="space-y-2">
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="todos">
                                <span class="filter-label bg-accent text-white px-4 py-2 rounded-md text-sm font-medium w-full text-center">Todas las categorías</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="comida">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-utensils mr-2"></i>Comida Rápida</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="autolavado">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-car mr-2"></i>Autolavado</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="carpinteria">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-hammer mr-2"></i>Carpintería</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="drogeria">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-pills mr-2"></i>Droguería</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="belleza">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-cut mr-2"></i>Belleza</span>
                            </label>
                            <label class="flex items-center p-3 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="checkbox" class="filter-checkbox sr-only" data-category="reparaciones">
                                <span class="filter-label bg-gray-100 text-gray-700 px-4 py-2 rounded-md text-sm font-medium w-full text-center border-2 border-gray-200"><i class="fas fa-wrench mr-2"></i>Reparaciones</span>
                            </label>
                        </div>
                    </div>

            <!-- Rango de precios -->
            <div class="mb-6">
                <h4 class="font-semibold mb-3 text-gray-700">Precio</h4>
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <input type="number" id="minPrice" placeholder="Min" class="w-20 px-2 py-1 border rounded text-sm">
                        <span class="text-gray-500">-</span>
                        <input type="number" id="maxPrice" placeholder="Max" class="w-20 px-2 py-1 border rounded text-sm">
                    </div>
                </div>
            </div>

                    <!-- Calificación -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Calificación</h3>
                        <div class="space-y-2">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2" data-rating="5">
                                <div class="flex items-center">
                                    <div class="star-rating">★★★★★</div>
                                    <span class="ml-2 text-sm text-gray-600">(5 estrellas)</span>
                                </div>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2" data-rating="4">
                                <div class="flex items-center">
                                    <div class="star-rating">★★★★</div>
                                    <span class="ml-2 text-sm text-gray-600">(4+ estrellas)</span>
                                </div>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" class="mr-2" data-rating="3">
                                <div class="flex items-center">
                                    <div class="star-rating">★★★</div>
                                    <span class="ml-2 text-sm text-gray-600">(3+ estrellas)</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Botón limpiar filtros -->
                    <button class="w-full bg-secondary text-white py-3 px-4 rounded-md hover:bg-primary transition-colors duration-300 flex items-center justify-center" id="clear-filters">
                        <i class="fas fa-trash mr-2"></i>
                        Limpiar Filtros
                    </button>
                </div>
            </aside>

            <!-- Contenido principal -->
            <main class="flex-1">
                <!-- Encabezado -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">Servicios de la Comunidad</h1>
                    <p class="text-gray-600">Descubre los mejores servicios locales de tu comunidad</p>
                </div>

                <!-- Contador de resultados -->
                <div class="mb-6 text-sm text-gray-600">
                    Mostrando <span id="results-count">8</span> servicios
                </div>

                <!-- Grid de servicios -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="services-grid">
                    <!-- Las cards se cargan dinámicamente por JS -->
                </div>

                <!-- Modales globales para ver más y editar (solo si está autenticado como entrepreneur) -->
                @if($entrepreneur)
                    @include('modals.login-items.entrepreneur.ServiceViewModal')
                    @include('modals.login-items.entrepreneur.ServiceEditModal')
                @endif

                <script>
                    window.entrepreneurIdFromBlade = @json($entrepreneur ? $entrepreneur->id : null);
                </script>
            </main>
        </div>
    </div>

@endsection

@vite('resources/js/services.js')
