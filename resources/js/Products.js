        // Datos de productos simulados
        const products = [
            {
                id: 1,
                name: "Smartphone Premium X1",
                category: "electronics",
                price: 899.99,
                originalPrice: 1099.99,
                rating: 4.8,
                reviews: 256,
                image: "https://via.placeholder.com/300x300/F77786/FFFFFF?text=Smartphone",
                description: "Smartphone de última generación con cámara de 108MP",
                inStock: true,
                isNew: true,
                discount: 18
            },
            {
                id: 2,
                name: "Laptop Gamer Pro",
                category: "electronics",
                price: 1299.99,
                originalPrice: 1499.99,
                rating: 4.9,
                reviews: 128,
                image: "https://via.placeholder.com/300x300/998486/FFFFFF?text=Laptop",
                description: "Laptop gaming con RTX 4060 y 16GB RAM",
                inStock: true,
                isNew: false,
                discount: 13
            },
            {
                id: 3,
                name: "Camiseta Deportiva",
                category: "clothing",
                price: 29.99,
                originalPrice: 39.99,
                rating: 4.5,
                reviews: 89,
                image: "https://via.placeholder.com/300x300/EB0924/FFFFFF?text=Camiseta",
                description: "Camiseta deportiva transpirable de alta calidad",
                inStock: true,
                isNew: false,
                discount: 25
            },
            {
                id: 4,
                name: "Sofá Moderno 3 Plazas",
                category: "home",
                price: 599.99,
                originalPrice: 799.99,
                rating: 4.7,
                reviews: 45,
                image: "https://via.placeholder.com/300x300/D1A1A7/FFFFFF?text=Sofa",
                description: "Sofá moderno y cómodo para tu sala de estar",
                inStock: true,
                isNew: true,
                discount: 25
            },
            {
                id: 5,
                name: "Balón de Fútbol Profesional",
                category: "sports",
                price: 49.99,
                originalPrice: 69.99,
                rating: 4.6,
                reviews: 156,
                image: "https://via.placeholder.com/300x300/C7AFB2/FFFFFF?text=Balon",
                description: "Balón oficial FIFA para partidos profesionales",
                inStock: true,
                isNew: false,
                discount: 29
            },
            {
                id: 6,
                name: "Auriculares Inalámbricos",
                category: "electronics",
                price: 159.99,
                originalPrice: 199.99,
                rating: 4.4,
                reviews: 312,
                image: "https://via.placeholder.com/300x300/F77786/FFFFFF?text=Auriculares",
                description: "Auriculares con cancelación de ruido activa",
                inStock: false,
                isNew: false,
                discount: 20
            },
            {
                id: 7,
                name: "Vestido Elegante",
                category: "clothing",
                price: 89.99,
                originalPrice: 129.99,
                rating: 4.3,
                reviews: 67,
                image: "https://via.placeholder.com/300x300/998486/FFFFFF?text=Vestido",
                description: "Vestido elegante para ocasiones especiales",
                inStock: true,
                isNew: true,
                discount: 31
            },
            {
                id: 8,
                name: "Mesa de Comedor",
                category: "home",
                price: 399.99,
                originalPrice: 499.99,
                rating: 4.8,
                reviews: 78,
                image: "https://via.placeholder.com/300x300/EB0924/FFFFFF?text=Mesa",
                description: "Mesa de comedor de madera maciza para 6 personas",
                inStock: true,
                isNew: false,
                discount: 20
            },
            {
                id: 9,
                name: "Bicicleta Montaña",
                category: "sports",
                price: 699.99,
                originalPrice: 899.99,
                rating: 4.7,
                reviews: 94,
                image: "https://via.placeholder.com/300x300/D1A1A7/FFFFFF?text=Bicicleta",
                description: "Bicicleta de montaña con suspensión completa",
                inStock: true,
                isNew: true,
                discount: 22
            },
            {
                id: 10,
                name: "Tablet Pro 12",
                category: "electronics",
                price: 549.99,
                originalPrice: 699.99,
                rating: 4.6,
                reviews: 189,
                image: "https://via.placeholder.com/300x300/C7AFB2/FFFFFF?text=Tablet",
                description: "Tablet profesional con pantalla de 12 pulgadas",
                inStock: true,
                isNew: true,
                discount: 21
            },
            {
                id: 11,
                name: "Zapatillas Running",
                category: "sports",
                price: 129.99,
                originalPrice: 159.99,
                rating: 4.4,
                reviews: 203,
                image: "https://via.placeholder.com/300x300/F77786/FFFFFF?text=Zapatillas",
                description: "Zapatillas de running con tecnología de amortiguación",
                inStock: true,
                isNew: false,
                discount: 19
            },
            {
                id: 12,
                name: "Chaqueta Invierno",
                category: "clothing",
                price: 179.99,
                originalPrice: 229.99,
                rating: 4.7,
                reviews: 76,
                image: "https://via.placeholder.com/300x300/998486/FFFFFF?text=Chaqueta",
                description: "Chaqueta de invierno impermeable y cálida",
                inStock: true,
                isNew: true,
                discount: 22
            }
        ];

        // Variables globales
        let filteredProducts = [...products];
        let cart = [];

        // Inicialización
        document.addEventListener('DOMContentLoaded', function() {
            initializeApp();
        });

        function initializeApp() {
            displayProducts();
            setupEventListeners();
            updateCartBadge();
            hideLoading();
        }

        function setupEventListeners() {
            // Filtros de categoría
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    filterProducts();
                });
            });

            // Ordenamiento
            document.getElementById('sortBy').addEventListener('change', function() {
                sortProducts(this.value);
            });

            // Filtros de precio
            document.getElementById('minPrice').addEventListener('input', filterProducts);
            document.getElementById('maxPrice').addEventListener('input', filterProducts);

            // Filtros de calificación
            document.querySelectorAll('.rating-filter').forEach(filter => {
                filter.addEventListener('change', filterProducts);
            });

            // Limpiar filtros
            document.getElementById('clearFilters').addEventListener('click', clearAllFilters);

            // Toggle filtros móvil
            document.getElementById('toggleFilters').addEventListener('click', function() {
                document.getElementById('filterSidebar').classList.add('active');
                document.getElementById('sidebarOverlay').classList.add('active');
            });

            document.getElementById('closeSidebar').addEventListener('click', closeSidebar);
            document.getElementById('sidebarOverlay').addEventListener('click', closeSidebar);

            // Carrito
            document.getElementById('cartToggle').addEventListener('click', function(e) {
                e.preventDefault();
                toggleMiniCart();
            });

            document.getElementById('closeMiniCart').addEventListener('click', function() {
                document.getElementById('miniCart').classList.add('hidden');
            });
        }

        function closeSidebar() {
            document.getElementById('filterSidebar').classList.remove('active');
            document.getElementById('sidebarOverlay').classList.remove('active');
        }

        function displayProducts() {
            const grid = document.getElementById('productsGrid');
            
            if (filteredProducts.length === 0) {
                showNoProducts();
                return;
            }

            hideNoProducts();
            
            grid.innerHTML = filteredProducts.map(product => createProductCard(product)).join('');
            updateProductCount(filteredProducts.length);
            
            // Agregar event listeners a los botones de agregar al carrito
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = parseInt(this.dataset.productId);
                    addToCart(productId);
                });
            });

            // Mostrar grid
            grid.classList.remove('hidden');
            grid.classList.add('fade-in');
        }

        function createProductCard(product) {
            const discountBadge = product.discount > 0 ? 
                `<div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold">
                    -${product.discount}%
                </div>` : '';

            const newBadge = product.isNew ? 
                `<div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                    NUEVO
                </div>` : '';

            const stockStatus = product.inStock ? 
                `<button class="add-to-cart btn-primary w-full py-2 rounded-lg font-semibold transition-all duration-300" data-product-id="${product.id}">
                    <i class="fas fa-cart-plus"></i> Agregar al Carrito
                </button>` :
                `<button class="bg-gray-400 text-white w-full py-2 rounded-lg font-semibold cursor-not-allowed" disabled>
                    <i class="fas fa-times"></i> Sin Stock
                </button>`;

            const stars = generateStarRating(product.rating);

            return `
                <div class="product-card bg-white rounded-lg shadow-lg overflow-hidden fade-in">
                    <div class="relative">
                        <img src="${product.image}" alt="${product.name}" class="w-full h-64 object-cover">
                        ${discountBadge}
                        ${newBadge}
                        <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                            <button class="bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-300">
                                <i class="fas fa-eye"></i> Ver Detalles
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="category-tag inline-block mb-2">${getCategoryName(product.category)}</div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">${product.name}</h3>
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">${product.description}</p>
                        
                        <div class="flex items-center mb-3">
                            <div class="star-rating mr-2">${stars}</div>
                            <span class="text-sm text-gray-600">(${product.reviews} reseñas)</span>
                        </div>

                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600">$${product.price}</span>
                                ${product.originalPrice > product.price ? 
                                    `<span class="text-lg text-gray-400 line-through">$${product.originalPrice}</span>` : ''}
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="text-gray-400 hover:text-red-600 transition-colors p-2">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="text-gray-400 hover:text-blue-600 transition-colors p-2">
                                    <i class="fas fa-share-alt"></i>
                                </button>
                            </div>
                        </div>

                        ${stockStatus}
                    </div>
                </div>
            `;
        }

        function generateStarRating(rating) {
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 !== 0;
            let stars = '';

            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star"></i>';
            }

            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt"></i>';
            }

            const emptyStars = 5 - Math.ceil(rating);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star"></i>';
            }

            return stars;
        }

        function getCategoryName(category) {
            const categoryNames = {
                'electronics': 'Electrónicos',
                'clothing': 'Ropa',
                'home': 'Hogar',
                'sports': 'Deportes'
            };
            return categoryNames[category] || category;
        }

        function filterProducts() {
            const activeCategory = document.querySelector('.filter-btn.active').dataset.category;
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            const selectedRatings = Array.from(document.querySelectorAll('.rating-filter:checked'))
                .map(cb => parseFloat(cb.dataset.rating));

            filteredProducts = products.filter(product => {
                const matchesCategory = activeCategory === 'all' || product.category === activeCategory;
                const matchesPrice = product.price >= minPrice && product.price <= maxPrice;
                const matchesRating = selectedRatings.length === 0 || 
                    selectedRatings.some(rating => product.rating >= rating);

                return matchesCategory && matchesPrice && matchesRating;
            });

            displayProducts();
        }

        function sortProducts(sortBy) {
            switch (sortBy) {
                case 'price-low':
                    filteredProducts.sort((a, b) => a.price - b.price);
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => b.price - a.price);
                    break;
                case 'rating':
                    filteredProducts.sort((a, b) => b.rating - a.rating);
                    break;
                case 'newest':
                    filteredProducts.sort((a, b) => b.isNew - a.isNew);
                    break;
                default:
                    // Featured - orden original
                    filteredProducts = [...products].filter(product => {
                        const activeCategory = document.querySelector('.filter-btn.active').dataset.category;
                        return activeCategory === 'all' || product.category === activeCategory;
                    });
            }
            displayProducts();
        }

        function clearAllFilters() {
            // Resetear categoría
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelector('.filter-btn[data-category="all"]').classList.add('active');
            
            // Resetear precios
            document.getElementById('minPrice').value = '';
            document.getElementById('maxPrice').value = '';
            
            // Resetear calificaciones
            document.querySelectorAll('.rating-filter').forEach(cb => cb.checked = false);
            
            // Resetear ordenamiento
            document.getElementById('sortBy').value = 'featured';
            
            // Resetear búsqueda
            document.getElementById('searchInput').value = '';

            // Aplicar filtros
            filteredProducts = [...products];
            currentPage = 1;
            displayProducts();
        }

        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (!product || !product.inStock) return;

            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }

            updateCartBadge();
            updateMiniCart();
            showAddToCartNotification(product.name);
        }

        function updateCartBadge() {
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            document.getElementById('cartBadge').textContent = totalItems;
        }

        function updateMiniCart() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');

            if (cart.length === 0) {
                cartItems.innerHTML = '<p class="text-gray-500 text-center py-4">Tu carrito está vacío</p>';
                cartTotal.textContent = '$0';
                return;
            }

            cartItems.innerHTML = cart.map(item => `
                <div class="flex items-center space-x-3 p-2 border-b">
                    <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded">
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold line-clamp-1">${item.name}</h4>
                        <p class="text-xs text-gray-600">Cantidad: ${item.quantity}</p>
                        <p class="text-sm font-bold text-red-600">${(item.price * item.quantity).toFixed(2)}</p>
                    </div>
                    <button class="text-red-500 hover:text-red-700 remove-item" data-product-id="${item.id}">
                        <i class="fas fa-trash text-sm"></i>
                    </button>
                </div>
            `).join('');

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotal.textContent = `${total.toFixed(2)}`;

            // Agregar event listeners para remover items
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = parseInt(this.dataset.productId);
                    removeFromCart(productId);
                });
            });
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartBadge();
            updateMiniCart();
        }

        function toggleMiniCart() {
            const miniCart = document.getElementById('miniCart');
            miniCart.classList.toggle('hidden');
            updateMiniCart();
        }

        function showAddToCartNotification(productName) {
            // Simple notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>¡${productName} agregado al carrito!</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function setupPagination(totalProducts) {
            const totalPages = Math.ceil(totalProducts / productsPerPage);
            const pagination = document.getElementById('pagination');
            
            if (totalPages <= 1) {
                pagination.classList.add('hidden');
                return;
            }

            pagination.classList.remove('hidden');
            
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const pageNumbers = document.getElementById('pageNumbers');

            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;

            // Generar números de página
            pageNumbers.innerHTML = '';
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }

            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = `px-3 py-2 border rounded-lg ${i === currentPage ? 'bg-red-600 text-white border-red-600' : 'bg-white hover:bg-gray-50'}`;
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => goToPage(i));
                pageNumbers.appendChild(pageBtn);
            }

            // Event listeners para prev/next
            prevBtn.replaceWith(prevBtn.cloneNode(true));
            nextBtn.replaceWith(nextBtn.cloneNode(true));
            
            document.getElementById('prevPage').addEventListener('click', () => goToPage(currentPage - 1));
            document.getElementById('nextPage').addEventListener('click', () => goToPage(currentPage + 1));
        }

        function goToPage(page) {
            currentPage = page;
            displayProducts();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function updateProductCount(total) {
            document.getElementById('productCount').textContent = `Mostrando ${total} productos`;
        }

        function showNoProducts() {
            document.getElementById('productsGrid').classList.add('hidden');
            document.getElementById('noProducts').classList.remove('hidden');
            document.getElementById('pagination').classList.add('hidden');
        }

        function hideNoProducts() {
            document.getElementById('noProducts').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').classList.add('hidden');
        }

        function switchToListView() {
            // Implementar vista de lista si se desea
            // Por ahora mantiene la vista de grid
        }

        // Cerrar modales al hacer clic fuera
        document.addEventListener('click', function(e) {
            const categoriesModal = document.getElementById('categoriesModal');
            const categoriesButton = document.getElementById('categoriesButton');
            
            if (!categoriesButton.contains(e.target) && !categoriesModal.contains(e.target)) {
                categoriesModal.classList.add('hidden');
            }

            const miniCart = document.getElementById('miniCart');
            const cartToggle = document.getElementById('cartToggle');
            
            if (!cartToggle.contains(e.target) && !miniCart.contains(e.target)) {
                miniCart.classList.add('hidden');
            }

            const filterSidebar = document.getElementById('filterSidebar');
            const toggleFilters = document.getElementById('toggleFilters');
            
            if (window.innerWidth < 1024 && !toggleFilters.contains(e.target) && !filterSidebar.contains(e.target)) {
                filterSidebar.classList.remove('active');
            }
        });
