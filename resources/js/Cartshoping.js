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