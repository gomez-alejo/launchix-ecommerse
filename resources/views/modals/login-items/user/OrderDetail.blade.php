@extends('layouts.app')
@section('title', 'Detalle del Pedido')
@vite('resources/css/orders.css')


@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex gap-8">
        <!-- Sidebar -->
        @include('modals.login-items.user.sideBar')

        <!-- Main Content Area -->
        <main class="flex-1">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-medium hover:text-primary transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Volver a Mis Pedidos
                </a>
            </div>

            <!-- Order Header -->
            <div class="bg-white rounded-xl shadow-soft p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-dark mb-2"></h1>
                        <p class="text-medium"></div>
                    <div class="flex flex-wrap gap-3">
                        <span class="badge {{ $order->getStatusBadgeClass() }} px-4 py-2 rounded-full text-sm font-medium">
                          
                        </span>
                    </div>
                </div>

                <!-- Order Timeline -->
                <div class="order-timeline">
                    <div class="timeline-item {{ $order->created_at ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <p class="font-semibold text-dark">Pedido Realizado</p>
                            @if($order->created_at)
                                <p class="text-sm text-medium">{{ $order->created_at->format('d/m/Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-item {{ in_array($order->status, ['Processing', 'Shipped', 'Delivered']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <p class="font-semibold text-dark">En Proceso</p>
                            @if($order->status != 'Pending' && $order->updated_at)
                                <p class="text-sm text-medium">{{ $order->updated_at->format('d/m/Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-item {{ in_array($order->status, ['Shipped', 'Delivered']) ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <p class="font-semibold text-dark">Enviado</p>
                            @if($order->shipped_at)
                                <p class="text-sm text-medium">{{ $order->shipped_at->format('d/m/Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="timeline-item {{ $order->status == 'Delivered' ? 'active' : '' }}">
                        <div class="timeline-dot"></div>
                        <div class="timeline-content">
                            <p class="font-semibold text-dark">Entregado</p>
                            @if($order->delivered_at)
                                <p class="text-sm text-medium">{{ $order->delivered_at->format('d/m/Y h:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    @if($order->status == 'Cancelled')
                        <div class="timeline-item cancelled">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <p class="font-semibold text-error">Cancelado</p>
                                @if($order->cancelled_at)
                                    <p class="text-sm text-medium">{{ $order->cancelled_at->format('d/m/Y h:i A') }}</p>
                                    @if($order->cancellation_reason)
                                        <p class="text-sm text-medium mt-1">Motivo: {{ $order->cancellation_reason }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Order Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h2 class="text-xl font-bold text-dark mb-4">Productos del Pedido</h2>
                        
                        <div class="space-y-4">
                            @foreach($order->items as $item)
                                <div class="flex gap-4 p-4 border border-gray-100 rounded-lg hover:border-primary transition-colors">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($item->product_image)
                                            <img src="{{ asset('storage/' . $item->product_image) }}" alt="{{ $item->product_name }}" class="w-20 h-20 object-cover rounded-lg">
                                        @else
                                            <div class="w-20 h-20 bg-accent rounded-lg flex items-center justify-center">
                                                <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-dark mb-1">{{ $item->product_name }}</h3>
                                        <p class="text-sm text-medium mb-2">Cantidad: {{ $item->quantity }}</p>
                                        <p class="text-sm text-light">Precio unitario: ${{ number_format($item->price, 0, ',', '.') }}</p>
                                    </div>

                                    <!-- Product Total -->
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-dark">${{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="space-y-3">
                                <div class="flex justify-between text-medium">
                                    <span>Subtotal:</span>
                                    <span class="font-semibold">${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-medium">
                                    <span>Envío:</span>
                                    <span class="font-semibold">
                                        @if($order->shipping_cost > 0)
                                            ${{ number_format($order->shipping_cost, 0, ',', '.') }}
                                        @else
                                            <span class="text-green">Gratis</span>
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-medium">
                                    <span>IVA (19%):</span>
                                    <span class="font-semibold">${{ number_format($order->tax, 0, ',', '.') }}</span>
                                </div>
                                @if($order->discount > 0)
                                    <div class="flex justify-between text-green">
                                        <span>Descuento:</span>
                                        <span class="font-semibold">-${{ number_format($order->discount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-xl font-bold text-dark pt-3 border-t border-gray-100">
                                    <span>Total:</span>
                                    <span>${{ number_format($order->total, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Information Sidebar -->
                <div class="space-y-6">
                    <!-- Shipping Information -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-dark">Información de Envío</h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p class="font-semibold text-dark">{{ $order->shipping_name }}</p>
                            <p class="text-medium">{{ $order->shipping_phone }}</p>
                            <p class="text-medium">{{ $order->shipping_address }}</p>
                            <p class="text-medium">{{ $order->shipping_city }}, {{ $order->shipping_state }}</p>
                            <p class="text-medium">{{ $order->shipping_zip_code }}, {{ $order->shipping_country }}</p>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <h3 class="text-lg font-bold text-dark">Información de Pago</h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-medium">Método:</span>
                                <span class="font-semibold text-dark">{{ $order->getPaymentMethodText() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-medium">Estado:</span>
                                <span class="font-semibold {{ $order->payment_status == 'paid' ? 'text-green' : 'text-orange' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </div>
                            @if($order->paid_at)
                                <div class="flex justify-between">
                                    <span class="text-medium">Fecha de pago:</span>
                                    <span class="font-semibold text-dark">{{ $order->paid_at->format('d/m/Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tracking Information -->
                    @if($order->tracking_number)
                        <div class="bg-white rounded-xl shadow-soft p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                </svg>
                                <h3 class="text-lg font-bold text-dark">Seguimiento</h3>
                            </div>
                            <div class="space-y-3">
                                <div class="p-3 bg-accent rounded-lg">
                                    <p class="text-xs text-medium mb-1">Número de rastreo:</p>
                                    <p class="font-mono font-bold text-dark">{{ $order->tracking_number }}</p>
                                </div>
                                <button class="w-full btn-primary py-2 rounded-lg text-sm font-medium transition-all hover:shadow-primary">
                                    Rastrear Pedido
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Order Notes -->
                    @if($order->notes)
                        <div class="bg-white rounded-xl shadow-soft p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                                <h3 class="text-lg font-bold text-dark">Notas del Pedido</h3>
                            </div>
                            <p class="text-sm text-medium">{{ $order->notes }}</p>
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-soft p-6">
                        <h3 class="text-lg font-bold text-dark mb-4">Acciones</h3>
                        <div class="space-y-3">
                            <a href="{{ route('orders.invoice', $order) }}" class="w-full btn-primary py-3 rounded-lg text-sm font-medium transition-all hover:shadow-primary flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Descargar Factura
                            </a>

                            @if($order->canBeCancelled())
                                <button onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_number }}')" class="w-full btn-outline-error py-3 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Cancelar Pedido
                                </button>
                            @endif

                            <a href="{{ route('home') }}" class="w-full btn-outline-primary py-3 rounded-lg text-sm font-medium transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" class="modal-overlay hidden">
    <div class="modal-container">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="text-xl font-bold text-dark">Cancelar Pedido</h3>
                <button onclick="closeCancelModal()" class="text-medium hover:text-dark transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="cancelForm" method="POST" class="modal-body">
                @csrf
                <p class="text-medium mb-4">¿Estás seguro de que deseas cancelar el pedido <span id="modalOrderNumber" class="font-bold text-dark"></span>?</p>
                
                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-dark font-medium mb-2">Motivo de cancelación *</label>
                    <textarea 
                        name="cancellation_reason" 
                        id="cancellation_reason" 
                        rows="4" 
                        required
                        class="w-full px-4 py-3 border border-gray rounded-lg focus:outline-none focus:border-primary transition-colors resize-none"
                        placeholder="Por favor, cuéntanos por qué deseas cancelar este pedido..."
                    ></textarea>
                </div>

                <p class="text-sm text-light mb-6">* Esta acción restaurará el stock de los productos y no se podrá deshacer.</p>

                <div class="flex gap-3">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 btn-outline-primary px-6 py-3 rounded-lg font-medium transition-all">
                        No, mantener pedido
                    </button>
                    <button type="submit" class="flex-1 bg-error text-white px-6 py-3 rounded-lg font-medium transition-all hover:bg-opacity-90">
                        Sí, cancelar pedido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@vite('resources/js/orders.js')