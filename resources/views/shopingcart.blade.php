@extends('layouts.app')

@section('title', 'carrito de compras')

@section('content')

    <div class="container mx-auto px-4 py-8">
        <!-- Empty Cart Message -->
        <div id="empty-cart" class="text-center py-16">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.5 6H19"></path>
            </svg>
            <h2 class="text-2xl font-bold text-gray-600 mb-4">Tu carrito está vacío</h2>
            <p class="text-gray-500 mb-6">¡Agrega algunos productos para comenzar a comprar!</p>
            <button onclick="addSampleProducts()" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-secondary transition-colors duration-200">
                Agregar productos de ejemplo
            </button>
        </div>

        <!-- Cart Content -->
        <div id="cart-content" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-6">Productos en tu carrito</h2>
                        <div id="cart-items" class="space-y-4">
                            <!-- Cart items will be populated here -->
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Resumen del pedido</h3>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-semibold">$0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Envío:</span>
                                <span id="shipping" class="font-semibold">$5.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Impuestos:</span>
                                <span id="taxes" class="font-semibold">$0.00</span>
                            </div>
                            <hr class="my-4">
                            <div class="flex justify-between text-lg font-bold">
                                <span>Total:</span>
                                <span id="total" class="text-primary">$0.00</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <button onclick="proceedToCheckout()" class="w-full bg-primary text-white py-3 rounded-lg hover:bg-secondary transition-colors duration-200 font-semibold">
                                Proceder al pago
                            </button>
                            <button onclick="continueShopping()" class="w-full border-2 border-primary text-primary py-3 rounded-lg hover:bg-primary hover:text-white transition-all duration-200">
                                Continuar comprando
                            </button>
                        </div>

                        <!-- Promo Code -->
                        <div class="mt-6 pt-6 border-t">
                            <h4 class="font-semibold text-gray-800 mb-3">Código de descuento</h4>
                            <div class="flex">
                                <input type="text" id="promo-code" placeholder="Ingresa tu código" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary">
                                <button onclick="applyPromoCode()" class="bg-accent text-white px-4 py-2 rounded-r-lg hover:bg-primary transition-colors duration-200">
                                    Aplicar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="success-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md mx-4 bounce-in">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">¡Operación exitosa!</h3>
                <p id="success-message" class="text-gray-600 mb-6"></p>
                <button onclick="closeModal()" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-secondary transition-colors duration-200">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

@endsection

<script>
            // Cart data
        let cart = [];
        let cartCount = 0;
        let subtotal = 0;
        let shipping = 5.00;
        let taxes = 0;
        let total = 0;

        // Sample products
        const sampleProducts = [
            { id: 1, name: "Smartphone Premium", price: 899.99, image: "https://via.placeholder.com/100x100/EB0924/FFFFFF?text=Phone", quantity: 1 },
            { id: 2, name: "Auriculares Bluetooth", price: 129.99, image: "https://via.placeholder.com/100x100/F77786/FFFFFF?text=Audio", quantity: 2 },
            { id: 3, name: "Laptop Gamer", price: 1299.99, image: "https://via.placeholder.com/100x100/998486/FFFFFF?text=Laptop", quantity: 1 }
        ];

        // Initialize cart
        function initCart() {
            updateCartDisplay();
            updateCartCount();
        }

        // Add sample products to cart
        function addSampleProducts() {
            cart = [...sampleProducts];
            cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            updateCartDisplay();
            updateCartCount();
            showSuccessModal("Productos agregados al carrito");
        }

        // Update cart display
        function updateCartDisplay() {
            const emptyCart = document.getElementById('empty-cart');
            const cartContent = document.getElementById('cart-content');
            const cartItems = document.getElementById('cart-items');

            if (cart.length === 0) {
                emptyCart.classList.remove('hidden');
                cartContent.classList.add('hidden');
                return;
            }

            emptyCart.classList.add('hidden');
            cartContent.classList.remove('hidden');

            // Generate cart items HTML
            cartItems.innerHTML = cart.map(item => `
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg fade-in">
                    <img src="${item.image}" alt="${item.name}" class="w-20 h-20 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-semibold text-gray-800">${item.name}</h4>
                        <p class="text-gray-600">$${item.price.toFixed(2)}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="updateQuantity(${item.id}, -1)" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                            <span class="text-sm font-bold">-</span>
                        </button>
                        <span class="w-8 text-center font-semibold">${item.quantity}</span>
                        <button onclick="updateQuantity(${item.id}, 1)" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                            <span class="text-sm font-bold">+</span>
                        </button>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-800">$${(item.price * item.quantity).toFixed(2)}</p>
                        <button onclick="removeItem(${item.id})" class="text-red-500 hover:text-red-700 text-sm mt-1">
                            Eliminar
                        </button>
                    </div>
                </div>
            `).join('');

            calculateTotals();
        }

        // Update quantity
        function updateQuantity(productId, change) {
            const item = cart.find(item => item.id === productId);
            if (item) {
                item.quantity += change;
                if (item.quantity <= 0) {
                    removeItem(productId);
                } else {
                    cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
                    updateCartDisplay();
                    updateCartCount();
                }
            }
        }

        // Remove item
        function removeItem(productId) {
            cart = cart.filter(item => item.id !== productId);
            cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            updateCartDisplay();
            updateCartCount();
            showSuccessModal("Producto eliminado del carrito");
        }

        // Calculate totals
        function calculateTotals() {
            subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            taxes = subtotal * 0.1; // 10% tax
            total = subtotal + shipping + taxes;

            document.getElementById('subtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('taxes').textContent = `$${taxes.toFixed(2)}`;
            document.getElementById('total').textContent = `$${total.toFixed(2)}`;
        }

        // Update cart count
        function updateCartCount() {
            document.getElementById('cart-count').textContent = cartCount;
        }

        // Apply promo code
        function applyPromoCode() {
            const promoCode = document.getElementById('promo-code').value.trim();
            if (promoCode.toUpperCase() === 'DESCUENTO10') {
                const discount = subtotal * 0.1;
                total = subtotal + shipping + taxes - discount;
                document.getElementById('total').textContent = `$${total.toFixed(2)}`;
                showSuccessModal("¡Código de descuento aplicado! 10% de descuento");
                document.getElementById('promo-code').value = '';
            } else if (promoCode) {
                showSuccessModal("Código de descuento no válido");
            }
        }

        // Navigation functions
        function goToProducts() {
            showSuccessModal("Redirigiendo a la página de productos...");
        }

        function continueShopping() {
            showSuccessModal("Continuando con las compras...");
        }

        function proceedToCheckout() {
            if (cart.length === 0) {
                showSuccessModal("Tu carrito está vacío");
                return;
            }
            showSuccessModal("Redirigiendo al proceso de pago...");
        }

        // Modal functions
        function showSuccessModal(message) {
            document.getElementById('success-message').textContent = message;
            document.getElementById('success-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('success-modal').classList.add('hidden');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initCart();
        });

        // Close modal when clicking outside
        document.getElementById('success-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
</script>