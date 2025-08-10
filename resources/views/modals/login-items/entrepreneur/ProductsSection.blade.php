<!-- resources/views/modals/login-items/entrepreneur/ProductsSection.blade.php -->
<div id="productos" class="section-content">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Mis Productos</h1>
            <button class="btn-primary text-white px-4 py-2 rounded-lg" onclick="resetProductForm()">
                Agregar Producto
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @if(isset($products) && $products->count() > 0)
                @foreach($products as $product)
                    <div class="product-card bg-white border rounded-lg overflow-hidden">
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-800 mb-2">{{ $product->name }}</h3>
                            <p class="text-gray-600 text-sm mb-2">{{ $product->description }}</p>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-primary font-bold">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-gray-500">Stock: {{ $product->stock }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ ucfirst($product->category) }}</span>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 font-medium text-sm transition-colors duration-200" 
                                            data-product-id="{{ $product->id }}" 
                                            onclick="event.preventDefault(); loadProductData('{{ $product->id }}');">
                                        Editar
                                    </button>
                                    <button class="text-red-600 hover:text-red-800 font-medium text-sm transition-colors duration-200"
                                            data-product-id="{{ $product->id }}"
                                            data-product-name="{{ $product->name }}"
                                            onclick="event.preventDefault(); deleteProduct('{{ $product->id }}', '{{ $product->name }}');">
                                        Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full text-center py-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No hay productos disponibles</h3>
                    <p class="text-gray-500 mb-4">Comienza agregando tu primer producto</p>
                    <button class="btn-primary px-4 py-2 text-white rounded-lg" onclick="resetProductForm()">
                        Agregar Primer Producto
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>