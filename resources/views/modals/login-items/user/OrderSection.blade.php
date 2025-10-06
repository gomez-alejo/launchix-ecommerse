@extends('layouts.app')

@section('title', 'Mis Pedidos')

@vite(['resources/css/profile.css', 'resources/css/orders.css', 'resources/js/orders.js'])

@section('content')

<style>
    /* Variables CSS - Agrégalas si no están en tu archivo principal */
    :root {
        --primary: #FDC040;
        --secondary: #FFD166;
        --accent: #E8E8E8;
        --text-dark: #1F2937;
        --text-medium: #6B7280;
        --green: #10B981;
        --green-light: #D1FAE5;
        --error: #EF4444;
        --sale: #EF4444;
        --sale-bg: #FEE2E2;
        --bg-gray-100: #F3F4F6;
    }

    /* Clases faltantes para filtros */
    .filter-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        border: 2px solid transparent;
        background-color: white;
        color: var(--text-medium);
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-btn:hover {
        background-color: #F9FAFB;
        border-color: var(--primary);
    }

    .filter-btn.active {
        background-color: var(--primary);
        color: var(--text-dark);
        border-color: var(--primary);
    }

    .filter-btn .badge {
        background-color: rgba(0, 0, 0, 0.1);
        padding: 0.125rem 0.5rem;
        border-radius: 1rem;
        font-size: 0.75rem;
    }

    .filter-btn.active .badge {
        background-color: rgba(0, 0, 0, 0.2);
    }

    /* Search Input */
    .search-input {
        padding: 0.75rem 3rem 0.75rem 1rem;
        border: 2px solid #E5E7EB;
        border-radius: 0.5rem;
        min-width: 250px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(253, 192, 64, 0.1);
    }

    .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        width: 1.25rem;
        height: 1.25rem;
        color: var(--text-medium);
        pointer-events: none;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background-color: white;
        border-radius: 1rem;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .empty-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1.5rem;
        color: var(--text-medium);
    }

    /* Utility Classes */
    .shadow-soft {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    .space-y-4 > * + * {
        margin-top: 1rem;
    }

    .flex {
        display: flex;
    }

    .flex-1 {
        flex: 1;
    }

    .flex-wrap {
        flex-wrap: wrap;
    }

    .gap-2 {
        gap: 0.5rem;
    }

    .gap-4 {
        gap: 1rem;
    }

    .gap-8 {
        gap: 2rem;
    }

    .items-center {
        align-items: center;
    }

    .justify-between {
        justify-content: space-between;
    }

    .relative {
        position: relative;
    }

    .mb-2 {
        margin-bottom: 0.5rem;
    }

    .mb-6 {
        margin-bottom: 1.5rem;
    }

    .mb-8 {
        margin-bottom: 2rem;
    }

    .mt-8 {
        margin-top: 2rem;
    }

    .p-6 {
        padding: 1.5rem;
    }

    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .py-8 {
        padding-top: 2rem;
        padding-bottom: 2rem;
    }

    .text-3xl {
        font-size: 1.875rem;
        line-height: 2.25rem;
    }

    .text-xl {
        font-size: 1.25rem;
        line-height: 1.75rem;
    }

    .font-bold {
        font-weight: 700;
    }

    .font-semibold {
        font-weight: 600;
    }

    .text-dark {
        color: var(--text-dark);
    }

    .text-medium {
        color: var(--text-medium);
    }

    .bg-white {
        background-color: white;
    }

    .rounded-xl {
        border-radius: 0.75rem;
    }

    .container {
        width: 100%;
        max-width: 1280px;
        margin-left: auto;
        margin-right: auto;
    }

    .mx-auto {
        margin-left: auto;
        margin-right: auto;
    }

    .hidden {
        display: none;
    }
</style>

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    <div class="flex gap-8">
        <!-- Sidebar -->
        @include('modals.login-items.user.sideBar')

        <!-- Main Content Area -->
        <main class="flex-1">
            <!-- Header Section -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-dark mb-2">Mis Pedidos</h1>
                <p class="text-medium">Revisa el estado de tus pedidos y su historial</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="alert-success" role="alert">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error" role="alert">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Filters Section -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-6">
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <!-- Status Filters -->
                    <div class="flex flex-wrap gap-2">
                        <button class="filter-btn active" data-status="all">
                            Todos
                            <span class="badge">{{ $totalOrders ?? 0 }}</span>
                        </button>
                        <button class="filter-btn" data-status="pending">
                            Pendientes
                            <span class="badge">{{ $pendingOrders ?? 0 }}</span>
                        </button>
                        <button class="filter-btn" data-status="processing">
                            En Proceso
                            <span class="badge">{{ $processingOrders ?? 0 }}</span>
                        </button>
                        <button class="filter-btn" data-status="completed">
                            Completados
                            <span class="badge">{{ $completedOrders ?? 0 }}</span>
                        </button>
                        <button class="filter-btn" data-status="cancelled">
                            Cancelados
                            <span class="badge">{{ $cancelledOrders ?? 0 }}</span>
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="relative">
                        <input 
                            type="text" 
                            id="orderSearch" 
                            placeholder="Buscar pedido..."
                            class="search-input"
                        >
                        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div id="ordersList" class="space-y-4">
                @forelse($orders ?? [] as $order)
                    @include('modals.login-items.user.order-card', ['order' => $order])
                @empty
                    <div class="empty-state">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <h3 class="text-xl font-semibold text-dark mb-2">No tienes pedidos aún</h3>
                        <p class="text-medium mb-6">Comienza a comprar y tus pedidos aparecerán aquí</p>
                        <a href="{{ route('productos') }}" class="btn-primary" style="display: inline-block; padding: 0.75rem 1.5rem; border-radius: 0.5rem; text-decoration: none;">
                            Ir a la tienda
                        </a>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if(isset($orders) && $orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        </main>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-header">
                <h3 style="font-size: 1.25rem; font-weight: 600;">Cancelar Pedido #<span id="modalOrderNumber"></span></h3>
                <button type="button" onclick="closeCancelModal()" style="background: none; border: none; cursor: pointer; font-size: 1.5rem; color: var(--text-medium);">
                    ×
                </button>
            </div>
            <div class="modal-body">
                <form id="cancelForm" method="POST">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <label for="cancellation_reason" style="display: block; margin-bottom: 0.5rem; font-weight: 500;">
                            Motivo de cancelación
                        </label>
                        <textarea 
                            id="cancellation_reason" 
                            name="cancellation_reason" 
                            rows="4" 
                            required
                            placeholder="Por favor, explica por qué deseas cancelar este pedido (mínimo 10 caracteres)"
                            style="width: 100%; padding: 0.75rem; border: 2px solid #E5E7EB; border-radius: 0.5rem; resize: vertical;"
                        ></textarea>
                    </div>
                    <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                        <button type="button" onclick="closeCancelModal()" class="btn-outline-primary" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem;">
                            Cerrar
                        </button>
                        <button type="submit" class="btn-outline-error" style="padding: 0.75rem 1.5rem; border-radius: 0.5rem;">
                            Confirmar Cancelación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection