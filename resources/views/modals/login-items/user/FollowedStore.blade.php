@extends('layouts.app')

@section('title', 'Tiendas Seguidas')

@vite('resources/css/profile.css')

@section('content')

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <div class="flex gap-8">
        <!-- Sidebar -->
        @include('modals.login-items.user.sideBar')

        <!-- Main Content Area -->
        <main class="flex-1">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <h1 class="text-3xl font-bold text-dark">Tiendas Seguidas</h1>
                    <button id="filterBtn" class="flex items-center gap-2 px-4 py-2 border-2 border-gray rounded-lg hover:border-primary transition-all duration-300">
                        <svg class="w-5 h-5 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        <span class="text-medium font-medium">Filtros</span>
                    </button>
                </div>
                <p class="text-light text-sm">Gestiona y explora las tiendas que sigues</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-xl p-6 border border-gray hover:shadow-soft transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-light text-sm mb-1">Total Tiendas</p>
                            <p class="text-2xl font-bold text-dark">3</p>
                        </div>
                        <div class="w-12 h-12 bg-light rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray hover:shadow-soft transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-light text-sm mb-1">Activas Hoy</p>
                            <p class="text-2xl font-bold text-dark">3</p>
                        </div>
                        <div class="w-12 h-12 bg-green-light rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray hover:shadow-soft transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-light text-sm mb-1">Con Promociones</p>
                            <p class="text-2xl font-bold text-dark">2</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-light rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl p-4 mb-6 border border-gray">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="searchInput" placeholder="Buscar tiendas..." class="w-full pl-10 pr-4 py-2 border border-gray rounded-lg focus:outline-none focus:border-primary transition-colors duration-300">
                    </div>
                </div>
            </div>

            <!-- Filter Pills (Hidden by default) -->
            <div id="filterPills" class="hidden mb-6 flex flex-wrap gap-2">
                <button class="filter-pill active" data-filter="all">
                    Todas
                    <span class="count">3</span>
                </button>
                <button class="filter-pill" data-filter="promo">
                    Con Promoción
                    <span class="count">2</span>
                </button>
                <button class="filter-pill" data-filter="active">
                    Activas
                    <span class="count">3</span>
                </button>
                <button class="filter-pill" data-filter="organic">
                    Orgánicas
                    <span class="count">1</span>
                </button>
            </div>

            <!-- Stores Grid -->
            <div id="storesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                <!-- Store Card 1 - Con Promoción -->
                <div class="store-card bg-white rounded-xl overflow-hidden border border-gray hover:shadow-primary transition-all duration-300" data-categories="promo active organic">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=200&fit=crop" alt="Tienda" class="w-full h-48 object-cover">
                        <div class="absolute top-3 right-3">
                            <span class="badge-sale px-3 py-1 rounded-full text-xs font-bold shadow-lg">-30% OFF</span>
                        </div>
                        <div class="absolute top-3 left-3">
                            <span class="bg-white px-3 py-1 rounded-full text-xs font-medium shadow-md flex items-center gap-1">
                                <span class="w-2 h-2 bg-green rounded-full"></span>
                                Activa
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-dark mb-1">Verduras Ecológicas</h3>
                                <p class="text-sm text-light flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    0.8 km
                                </p>
                            </div>
                            <button class="follow-btn active" data-store-id="1">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-sm font-semibold text-dark">5.0</span>
                            </div>
                            <span class="text-light text-sm">•</span>
                            <span class="text-sm text-light">178 productos</span>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="badge-organic px-2 py-1 rounded-md text-xs font-medium">Orgánico</span>
                            <span class="bg-accent text-dark px-2 py-1 rounded-md text-xs font-medium">Verduras</span>
                        </div>
                        <button class="w-full btn-primary py-2.5 rounded-lg font-semibold hover:shadow-primary transition-all duration-300">
                            Ver Tienda
                        </button>
                    </div>
                </div>

                <!-- Store Card 2 - Con Promoción -->
                <div class="store-card bg-white rounded-xl overflow-hidden border border-gray hover:shadow-primary transition-all duration-300" data-categories="promo active">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?w=400&h=200&fit=crop" alt="Tienda" class="w-full h-48 object-cover">
                        <div class="absolute top-3 right-3">
                            <span class="badge-sale px-3 py-1 rounded-full text-xs font-bold shadow-lg">-25% OFF</span>
                        </div>
                        <div class="absolute top-3 left-3">
                            <span class="bg-white px-3 py-1 rounded-full text-xs font-medium shadow-md flex items-center gap-1">
                                <span class="w-2 h-2 bg-green rounded-full"></span>
                                Activa
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-dark mb-1">Panadería Artesanal</h3>
                                <p class="text-sm text-light flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    1.2 km
                                </p>
                            </div>
                            <button class="follow-btn active" data-store-id="2">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-sm font-semibold text-dark">4.8</span>
                            </div>
                            <span class="text-light text-sm">•</span>
                            <span class="text-sm text-light">56 productos</span>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-accent text-dark px-2 py-1 rounded-md text-xs font-medium">Panadería</span>
                        </div>
                        <button class="w-full btn-primary py-2.5 rounded-lg font-semibold hover:shadow-primary transition-all duration-300">
                            Ver Tienda
                        </button>
                    </div>
                </div>

                <!-- Store Card 3 - Sin Promoción -->
                <div class="store-card bg-white rounded-xl overflow-hidden border border-gray hover:shadow-primary transition-all duration-300" data-categories="active">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?w=400&h=200&fit=crop" alt="Tienda" class="w-full h-48 object-cover">
                        <div class="absolute top-3 left-3">
                            <span class="bg-white px-3 py-1 rounded-full text-xs font-medium shadow-md flex items-center gap-1">
                                <span class="w-2 h-2 bg-green rounded-full"></span>
                                Activa
                            </span>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-dark mb-1">Bebidas Naturales</h3>
                                <p class="text-sm text-light flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    4.2 km
                                </p>
                            </div>
                            <button class="follow-btn active" data-store-id="3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                <span class="ml-1 text-sm font-semibold text-dark">4.5</span>
                            </div>
                            <span class="text-light text-sm">•</span>
                            <span class="text-sm text-light">92 productos</span>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="bg-accent text-dark px-2 py-1 rounded-md text-xs font-medium">Bebidas</span>
                        </div>
                        <button class="w-full btn-primary py-2.5 rounded-lg font-semibold hover:shadow-primary transition-all duration-300">
                            Ver Tienda
                        </button>
                    </div>
                </div>

            </div>

            <!-- Empty State (Hidden by default, shown when no results) -->
            <div id="emptyState" class="hidden text-center py-16">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-light rounded-full mb-6">
                    <svg class="w-12 h-12 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-dark mb-2">No se encontraron tiendas</h3>
                <p class="text-light mb-6">Intenta ajustar tus filtros o búsqueda</p>
                <button id="clearFilters" class="btn-primary px-6 py-2.5 rounded-lg font-semibold">
                    Limpiar Filtros
                </button>
            </div>

        </main>
    </div>
</div>

<!-- Modal de Confirmación para Dejar de Seguir -->
<div id="unfollowModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75"></div>

        <!-- Modal -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-6 pt-6 pb-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-orange-light">
                        <svg class="h-6 w-6 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4 flex-1">
                        <h3 class="text-lg font-bold text-dark mb-2">¿Dejar de seguir tienda?</h3>
                        <p class="text-sm text-light">Ya no recibirás actualizaciones ni promociones de <span id="storeName" class="font-semibold text-dark"></span>. Puedes volver a seguirla en cualquier momento.</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex gap-3 justify-end">
                <button type="button" id="cancelBtn" class="px-5 py-2.5 border-2 border-gray rounded-lg text-medium font-semibold hover:border-primary transition-all duration-300">
                    Cancelar
                </button>
                <button type="button" id="confirmBtn" class="px-5 py-2.5 bg-orange text-white rounded-lg font-semibold hover:bg-warning transition-all duration-300">
                    Dejar de Seguir
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Variables de colores desde el archivo CSS */
    .text-dark { color: var(--text-dark, #1A1A1A); }
    .text-medium { color: var(--text-medium, #4A4A4A); }
    .text-light { color: var(--text-light, #808080); }
    .text-gray { color: var(--text-gray, #999999); }
    .text-primary { color: var(--primary, #FDC040); }
    .text-orange { color: var(--orange, #FF8A00); }
    .text-green { color: var(--green, #00B207); }
    
    .bg-light { background-color: var(--light, #FFFBF0); }
    .bg-green-light { background-color: var(--green-light, #EDF2EE); }
    .bg-orange-light { background-color: var(--orange-light, #FFF3E0); }
    .bg-accent { background-color: var(--accent, #FFF4D6); }
    .bg-green { background-color: var(--green, #00B207); }
    .bg-orange { background-color: var(--orange, #FF8A00); }
    
    .border-gray { border-color: #E6E6E6; }
    
    .btn-primary {
        background-color: var(--primary, #FDC040);
        color: var(--text-dark, #1A1A1A);
    }
    
    .btn-primary:hover {
        background-color: var(--secondary, #FFB800);
    }
    
    /* Animación de entrada */
    .store-card {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .store-card:nth-child(1) { animation-delay: 0.1s; }
    .store-card:nth-child(2) { animation-delay: 0.2s; }
    .store-card:nth-child(3) { animation-delay: 0.3s; }
    
    /* Botón de seguir */
    .follow-btn {
        padding: 0.5rem;
        border-radius: 50%;
        background-color: transparent;
        transition: all 0.3s ease;
        cursor: pointer;
        border: none;
    }
    
    .follow-btn svg {
        color: #D1D5DB;
        transition: all 0.3s ease;
    }
    
    .follow-btn.active svg {
        color: var(--sale, #FF6B6B);
    }
    
    .follow-btn:hover {
        background-color: var(--accent, #FFF4D6);
        transform: scale(1.1);
    }
    
    .follow-btn:active {
        transform: scale(0.95);
    }
    
    /* Filter Pills */
    .filter-pill {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        border: 2px solid #E6E6E6;
        background-color: white;
        color: var(--text-medium, #4A4A4A);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .filter-pill .count {
        background-color: var(--bg-gray-100, #F2F2F2);
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .filter-pill:hover {
        border-color: var(--primary, #FDC040);
        background-color: var(--accent, #FFF4D6);
    }
    
    .filter-pill.active {
        border-color: var(--primary, #FDC040);
        background-color: var(--primary, #FDC040);
        color: var(--text-dark, #1A1A1A);
    }
    
    .filter-pill.active .count {
        background-color: var(--secondary, #FFB800);
        color: var(--text-dark, #1A1A1A);
    }
    
    /* Badge Sale */
    .badge-sale {
        background-color: var(--sale, #FF6B6B);
        color: white;
    }
    
    /* Badge Organic */
    .badge-organic {
        background-color: var(--green-light, #EDF2EE);
        color: var(--green, #00B207);
    }
    
    /* Shadow Soft */
    .shadow-soft {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    
    /* Shadow Primary */
    .shadow-primary {
        box-shadow: 0 10px 25px -5px rgba(253, 192, 64, 0.3);
    }
    
    /* Modal animations */
    #unfollowModal > div > div:last-child {
        animation: slideUpModal 0.3s ease-out;
    }
    
    @keyframes slideUpModal {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .store-card {
            animation-delay: 0s !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 JavaScript iniciado - Tiendas Seguidas');
        
        // Variables globales
        let currentStoreId = null;
        let currentStoreName = '';
        
        // Elementos del DOM
        const storesGrid = document.getElementById('storesGrid');
        const emptyState = document.getElementById('emptyState');
        const searchInput = document.getElementById('searchInput');
        const filterBtn = document.getElementById('filterBtn');
        const filterPillsContainer = document.getElementById('filterPills');
        const filterPills = document.querySelectorAll('.filter-pill');
        const unfollowModal = document.getElementById('unfollowModal');
        const storeNameEl = document.getElementById('storeName');
        const cancelBtn = document.getElementById('cancelBtn');
        const confirmBtn = document.getElementById('confirmBtn');
        const clearFiltersBtn = document.getElementById('clearFilters');
        
        // Verificar elementos
        if (!storesGrid || !searchInput || !filterBtn) {
            console.error('❌ Elementos del DOM no encontrados');
            return;
        }
        
        console.log('✅ Elementos del DOM encontrados correctamente');
        console.log('📦 Total de tiendas:', document.querySelectorAll('.store-card').length);
        console.log('🏷️ Total de filtros:', filterPills.length);
        
        // ========== Toggle de filtros ==========
        filterBtn.addEventListener('click', function(e) {
            e.preventDefault();
            filterPillsContainer.classList.toggle('hidden');
            filterPillsContainer.classList.toggle('flex');
            console.log('🔄 Filtros toggled');
        });
        
        // ========== Búsqueda en tiempo real ==========
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase().trim();
            console.log('🔍 Buscando:', searchTerm);
            filterStores(searchTerm);
        });
        
        // ========== Filtros por categoría ==========
        filterPills.forEach(pill => {
            pill.addEventListener('click', function(e) {
                e.preventDefault();
                const filter = this.dataset.filter;
                console.log('🏷️ Filtro clickeado:', filter);
                
                // Remover active de todos
                filterPills.forEach(p => p.classList.remove('active'));
                // Agregar active al clickeado
                this.classList.add('active');
                
                // Limpiar búsqueda
                searchInput.value = '';
                
                filterByCategory(filter);
            });
        });
        
        // ========== Función de filtrado por búsqueda ==========
        function filterStores(searchTerm) {
            const stores = document.querySelectorAll('.store-card');
            let visibleCount = 0;
            
            stores.forEach(store => {
                const storeName = store.querySelector('h3')?.textContent.toLowerCase() || '';
                const badges = store.querySelectorAll('.bg-accent, .badge-organic, .badge-sale');
                let badgeText = '';
                badges.forEach(badge => {
                    badgeText += badge.textContent.toLowerCase() + ' ';
                });
                
                const isVisible = storeName.includes(searchTerm) || badgeText.includes(searchTerm);
                
                if (isVisible) {
                    store.style.display = 'block';
                    visibleCount++;
                } else {
                    store.style.display = 'none';
                }
            });
            
            console.log('📊 Tiendas visibles después de búsqueda:', visibleCount);
            toggleEmptyState(visibleCount === 0);
        }
        
        // ========== Función de filtrado por categoría ==========
        function filterByCategory(filter) {
            const stores = document.querySelectorAll('.store-card');
            let visibleCount = 0;
            
            stores.forEach(store => {
                if (filter === 'all') {
                    store.style.display = 'block';
                    visibleCount++;
                } else {
                    const categories = store.dataset.categories || '';
                    if (categories.includes(filter)) {
                        store.style.display = 'block';
                        visibleCount++;
                    } else {
                        store.style.display = 'none';
                    }
                }
            });
            
            console.log('📊 Filtro aplicado:', filter, '- Tiendas visibles:', visibleCount);
            toggleEmptyState(visibleCount === 0);
        }
        
        // ========== Toggle del estado vacío ==========
        function toggleEmptyState(show) {
            if (show) {
                storesGrid.style.display = 'none';
                if (emptyState) {
                    emptyState.classList.remove('hidden');
                }
            } else {
                storesGrid.style.display = 'grid';
                if (emptyState) {
                    emptyState.classList.add('hidden');
                }
            }
        }
        
        // ========== Limpiar filtros ==========
        if (clearFiltersBtn) {
            clearFiltersBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🧹 Limpiando filtros');
                searchInput.value = '';
                
                // Activar el filtro "Todas"
                filterPills.forEach(p => p.classList.remove('active'));
                if (filterPills[0]) {
                    filterPills[0].classList.add('active');
                }
                
                filterByCategory('all');
            });
        }
        
        // ========== Botones de seguir/dejar de seguir ==========
        const followBtns = document.querySelectorAll('.follow-btn');
        console.log('❤️ Botones de seguir encontrados:', followBtns.length);
        
        followBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const storeId = this.dataset.storeId;
                const storeCard = this.closest('.store-card');
                const storeName = storeCard?.querySelector('h3')?.textContent || 'esta tienda';
                
                console.log('💝 Click en botón de seguir - ID:', storeId, '- Nombre:', storeName);
                
                if (this.classList.contains('active')) {
                    // Mostrar modal de confirmación
                    currentStoreId = storeId;
                    currentStoreName = storeName;
                    openModal(storeName);
                } else {
                    // Seguir tienda
                    this.classList.add('active');
                    showNotification('Ahora sigues esta tienda', 'success');
                }
            });
        });
        
        // ========== Funciones del modal ==========
        function openModal(name) {
            console.log('🔔 Abriendo modal para:', name);
            if (storeNameEl && unfollowModal) {
                storeNameEl.textContent = name;
                unfollowModal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        
        function closeModal() {
            console.log('❌ Cerrando modal');
            if (unfollowModal) {
                unfollowModal.classList.add('hidden');
                document.body.style.overflow = 'auto';
                currentStoreId = null;
                currentStoreName = '';
            }
        }
        
        function confirmUnfollow() {
            console.log('✅ Confirmando dejar de seguir - ID:', currentStoreId);
            const btn = document.querySelector(`.follow-btn[data-store-id="${currentStoreId}"]`);
            if (btn) {
                btn.classList.remove('active');
                showNotification('Has dejado de seguir esta tienda', 'info');
            }
            closeModal();
        }
        
        // Event listeners del modal
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }
        
        if (confirmBtn) {
            confirmBtn.addEventListener('click', confirmUnfollow);
        }
        
        // Cerrar modal al hacer click en el overlay
        if (unfollowModal) {
            unfollowModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        }
        
        // ========== Sistema de notificaciones ==========
        function showNotification(message, type = 'success') {
            console.log('📢 Mostrando notificación:', message, '-', type);
            
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full';
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-orange-500 text-white'
            };
            
            notification.classList.add(...colors[type].split(' '));
            
            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
                info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
                warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>'
            };
            
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    ${icons[type] || icons.success}
                    <span class="font-medium">${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animación de entrada
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remover después de 3 segundos
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        console.log('✨ Todos los event listeners configurados correctamente');
    });
</script>

@endsection