// Variables globales
let products = []; // Ahora se cargará desde la API
let filteredProducts = [];
let cart = [];
let currentPage = 1;
const productsPerPage = 12;


//si se quieren quitar los productos estaticos primero toca eliminar esta funcion getStaticProducts()
//despues le das a la tecla Ctrl + F para abrir la barra de busqueda y pon : transformProductData los 4 primeros ahi estan las otras instrucciones :)
function getStaticProducts() {
    return [
        {
            id: 99001,
            name: "Smartphone Samsung Galaxy A54",
            category: { name: "Electrónicos", slug: "electronica" },
            price: 299999,
            originalPrice: 349999,
            rating: 4.5,
            reviews: 128,
            image: "https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&h=300&fit=crop",
            gallery: [],
            description: "Smartphone con pantalla AMOLED de 6.4 pulgadas, cámara triple de 50MP y batería de 5000mAh.",
            inStock: true,
            stock: 25,
            isNew: true,
            discount: 14,
            brand: "Samsung",
            sku: "SAM-A54-001",
            created_at: new Date().toISOString()
        },
        {
            id: 99002,
            name: "Auriculares Bluetooth Sony WH-1000XM4",
            category: { name: "Electrónicos", slug: "electronica" },
            price: 199999,
            originalPrice: 199999,
            rating: 4.8,
            reviews: 89,
            image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=300&h=300&fit=crop",
            gallery: [],
            description: "Auriculares inalámbricos con cancelación de ruido líder en la industria.",
            inStock: true,
            stock: 15,
            isNew: false,
            discount: 0,
            brand: "Sony",
            sku: "SONY-WH1000-001",
            created_at: "2024-01-15T10:00:00Z"
        },
        {
            id: 99003,
            name: "Camiseta Nike Dri-FIT",
            category: { name: "Deportes", slug: "deportes" },
            price: 29999,
            originalPrice: 39999,
            rating: 4.2,
            reviews: 45,
            image: "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=300&h=300&fit=crop",
            gallery: [],
            description: "Camiseta deportiva con tecnología Dri-FIT para mantenerte seco y cómodo.",
            inStock: true,
            stock: 50,
            isNew: false,
            discount: 25,
            brand: "Nike",
            sku: "NIKE-DRI-001",
            created_at: "2024-02-10T10:00:00Z"
        },
        {
            id: 99004,
            name: "Laptop HP Pavilion 15",
            category: { name: "Electrónicos", slug: "electronica" },
            price: 599999,
            originalPrice: 699999,
            rating: 4.3,
            reviews: 67,
            image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300&h=300&fit=crop",
            gallery: [],
            description: "Laptop con procesador Intel Core i5, 8GB RAM, SSD 256GB y pantalla Full HD.",
            inStock: true,
            stock: 8,
            isNew: false,
            discount: 14,
            brand: "HP",
            sku: "HP-PAV15-001",
            created_at: "2024-01-20T10:00:00Z"
        },
        {
            id: 99005,
            name: "Cafetera Nespresso Vertuo",
            category: { name: "Hogar", slug: "hogar" },
            price: 149999,
            originalPrice: 149999,
            rating: 4.6,
            reviews: 92,
            image: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=300&h=300&fit=crop",
            gallery: [],
            description: "Cafetera automática que prepara café y espresso de calidad profesional.",
            inStock: true,
            stock: 12,
            isNew: true,
            discount: 0,
            brand: "Nespresso",
            sku: "NESP-VER-001",
            created_at: new Date().toISOString()
        },
        {
            id: 99006,
            name: "Zapatillas Adidas Ultraboost 22",
            category: { name: "Deportes", slug: "deportes" },
            price: 179999,
            originalPrice: 199999,
            rating: 4.7,
            reviews: 156,
            image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&h=300&fit=crop",
            gallery: [],
            description: "Zapatillas de running con tecnología Boost para máximo retorno de energía.",
            inStock: true,
            stock: 30,
            isNew: false,
            discount: 10,
            brand: "Adidas",
            sku: "ADI-UB22-001",
            created_at: "2024-03-05T10:00:00Z"
        },
        {
            id: 99007,
            name: "Set de Maquillaje Urban Decay",
            category: { name: "Belleza", slug: "belleza" },
            price: 89999,
            originalPrice: 120000,
            rating: 4.4,
            reviews: 73,
            image: "https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=300&h=300&fit=crop",
            gallery: [],
            description: "Set completo de maquillaje con paleta de sombras, labiales y base.",
            inStock: true,
            stock: 18,
            isNew: false,
            discount: 25,
            brand: "Urban Decay",
            sku: "UD-SET-001",
            created_at: "2024-02-20T10:00:00Z"
        },
        {
            id: 99008,
            name: "Libro 'El Arte de la Guerra'",
            category: { name: "Libros", slug: "libros" },
            price: 12999,
            originalPrice: 15999,
            rating: 4.9,
            reviews: 245,
            image: "https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=300&h=300&fit=crop",
            gallery: [],
            description: "Clásico tratado de estrategia militar aplicable a los negocios y la vida.",
            inStock: true,
            stock: 100,
            isNew: false,
            discount: 19,
            brand: "Editorial Planeta",
            sku: "LIB-ARTE-001",
            created_at: "2024-01-10T10:00:00Z"
        },
        {
            id: 99009,
            name: "Silla Gamer RGB",
            category: { name: "Hogar", slug: "hogar" },
            price: 249999,
            originalPrice: 299999,
            rating: 4.1,
            reviews: 34,
            image: "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=300&h=300&fit=crop",
            gallery: [],
            description: "Silla ergonómica para gaming con iluminación RGB y soporte lumbar ajustable.",
            inStock: true,
            stock: 6,
            isNew: true,
            discount: 17,
            brand: "GamerPro",
            sku: "GP-CHAIR-001",
            created_at: new Date().toISOString()
        },
        {
            id: 99010,
            name: "Juego de Mesa Monopoly Clásico",
            category: { name: "Juguetes", slug: "juguetes" },
            price: 24999,
            originalPrice: 24999,
            rating: 4.0,
            reviews: 189,
            image: "https://images.unsplash.com/photo-1606092195730-5d7b9af1efc5?w=300&h=300&fit=crop",
            gallery: [],
            description: "El clásico juego de mesa Monopoly para disfrutar en familia.",
            inStock: false,
            stock: 0,
            isNew: false,
            discount: 0,
            brand: "Hasbro",
            sku: "HAS-MON-001",
            created_at: "2024-01-05T10:00:00Z"
        }
    ];
}

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    loadProducts();
});

// Cargar productos desde la API de Laravel
async function loadProducts() {
    try {
        showLoading();

        // Usar la ruta API específica que siempre devuelve JSON
        const response = await fetch('/api/productos', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content
            }
        });

        console.log('Response status:', response.status);

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response error:', errorText);
            throw new Error(`HTTP Error: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        console.log('Data received:', data);
        console.log('First product raw:', data.data[0]);
        console.log('First product transformed:', products[0]);

        if (data.success && Array.isArray(data.data)) {
            // Transformar los datos de Laravel al formato esperado por el frontend

            //Si se quiere quitar los productos de ejemplo tienes que cambiar las 2 lineas siguientes por las 3 que le siguen 

            // products = data.data.map(product => transformProductData(product));
            // filteredProducts = [...products];
            const apiProducts = data.data.map(product => transformProductData(product));
            products = [...getStaticProducts(), ...apiProducts];
            filteredProducts = [...products];

            initializeApp();
        } else if (Array.isArray(data)) {
            // Si la respuesta es directamente un array de productos (formato simplificado)

            //Si se quiere quitar los productos de ejemplo tienes que cambiar las 2 lineas siguientes por las 3 que le siguen

            // products = data.map(product => transformProductData(product));
            // filteredProducts = [...products];
            const apiProducts = data.map(product => transformProductData(product));
            products = [...getStaticProducts(), ...apiProducts];
            filteredProducts = [...products];

            initializeApp();
        } else {
            console.log('Data structure:', data);
            throw new Error('Formato de datos inesperado del servidor');
        }

    } catch (error) {
        console.error('Error cargando productos:', error);
        showErrorMessage(`Error al cargar los productos: ${error.message}`);

        

        // Como fallback, mostrar mensaje sin productos
        showNoProducts();
    } finally {
        hideLoading();
    }
}

// Transformar datos del producto de Laravel al formato del frontend
function transformProductData(laravelProduct) {
    console.log('Laravel product raw:', laravelProduct);
    console.log('Laravel product category:', laravelProduct.category);
    return {
        id: laravelProduct.id,
        name: laravelProduct.name,
        category: {
            name: laravelProduct.category?.name || 'General',
            slug: laravelProduct.category?.slug || 'general'
        }, // Mantener como string por ahora
        price: parseFloat(laravelProduct.price),
        originalPrice: laravelProduct.original_price ? parseFloat(laravelProduct.original_price) : parseFloat(laravelProduct.price),
        rating: parseFloat(laravelProduct.rating) || 4.0, // Valor por defecto si no hay rating
        reviews: parseInt(laravelProduct.reviews_count) || 0,
        image: laravelProduct.main_image || 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Producto',
        gallery: laravelProduct.gallery_images || [],
        description: laravelProduct.description || '',
        inStock: laravelProduct.stock > 0,
        stock: parseInt(laravelProduct.stock) || 0,
        isNew: isProductNew(laravelProduct.created_at),
        discount: calculateDiscount(laravelProduct.price, laravelProduct.original_price),
        brand: laravelProduct.brand || '',
        sku: laravelProduct.sku || '',
        created_at: laravelProduct.created_at
    };
}

// Verificar si un producto es nuevo (menos de 30 días)
function isProductNew(createdAt) {
    if (!createdAt) return false;

    const productDate = new Date(createdAt);
    const now = new Date();
    const diffTime = Math.abs(now - productDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays <= 30;
}

// Calcular el descuento
function calculateDiscount(currentPrice, originalPrice) {
    if (!originalPrice || originalPrice <= currentPrice) return 0;

    return Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
}

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
    document.getElementById('sortBy')?.addEventListener('change', function() {
        sortProducts(this.value);
    });

    // Filtros de precio
    document.getElementById('minPrice')?.addEventListener('input', filterProducts);
    document.getElementById('maxPrice')?.addEventListener('input', filterProducts);

    // Filtros de calificación
    document.querySelectorAll('.rating-filter').forEach(filter => {
        filter.addEventListener('change', filterProducts);
    });

    // Limpiar filtros
    document.getElementById('clearFilters')?.addEventListener('click', clearAllFilters);

    // Toggle filtros móvil
    document.getElementById('toggleFilters')?.addEventListener('click', function() {
        document.getElementById('filterSidebar').classList.add('active');
        document.getElementById('sidebarOverlay').classList.add('active');
    });

    document.getElementById('closeSidebar')?.addEventListener('click', closeSidebar);
    document.getElementById('sidebarOverlay')?.addEventListener('click', closeSidebar);

    // Carrito
    document.getElementById('cartToggle')?.addEventListener('click', function(e) {
        e.preventDefault();
        toggleMiniCart();
    });

    document.getElementById('closeMiniCart')?.addEventListener('click', function() {
        document.getElementById('miniCart').classList.add('hidden');
    });

    // Búsqueda
    // Búsqueda - event listener directo
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            console.log('Search event triggered:', e.target.value);
            const searchTerm = e.target.value.toLowerCase();

            if (searchTerm === '') {
                filteredProducts = [...products];
            } else {
                filteredProducts = products.filter(product =>
                    product.name.toLowerCase().includes(searchTerm) ||
                    product.description.toLowerCase().includes(searchTerm) ||
                    (product.brand && product.brand.toLowerCase().includes(searchTerm))
                );
            }

            console.log('Filtered products count:', filteredProducts.length);
            currentPage = 1;
            displayProducts();
        });
    }console.log('Setting up event listeners');

    console.log('Search input found:', searchInput);

    // Búsqueda
    searchInput?.addEventListener('input', debounce(searchProducts, 300));
}

// Función de búsqueda
function searchProducts() {
    const searchTerm = document.getElementById('searchInput')?.value.toLowerCase() || '';
    console.log('Search term:', searchTerm);

    if (searchTerm === '') {
        filterProducts();
        return;
    }

    filteredProducts = products.filter(product => {
        const matches = product.name.toLowerCase().includes(searchTerm) ||
            product.description.toLowerCase().includes(searchTerm) ||
            product.brand.toLowerCase().includes(searchTerm);
        console.log('Product:', product.name, 'Matches:', matches);
        return matches;
    });

    console.log('Filtered products:', filteredProducts.length);
    currentPage = 1;
    displayProducts();
}

// Función debounce para optimizar búsquedas
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function closeSidebar() {
    document.getElementById('filterSidebar')?.classList.remove('active');
    document.getElementById('sidebarOverlay')?.classList.remove('active');
}

function displayProducts() {
    const grid = document.getElementById('productsGrid');

    if (!grid) return;

    if (filteredProducts.length === 0) {
        showNoProducts();
        return;
    }

    hideNoProducts();

    // Paginación
    const startIndex = (currentPage - 1) * productsPerPage;
    const endIndex = startIndex + productsPerPage;
    const productsToShow = filteredProducts.slice(startIndex, endIndex);

    grid.innerHTML = productsToShow.map(product => createProductCard(product)).join('');
    updateProductCount(filteredProducts.length);
    setupPagination(filteredProducts.length);

    // Agregar event listeners a los botones de agregar al carrito
    document.querySelectorAll('.add-to-cart').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            addToCart(productId);


        });
    });
    // Event listeners para botones de ver detalles
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            viewProductDetails(productId);
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
            <i class="fas fa-times"></i> Sin Stock (${product.stock} disponibles)
        </button>`;

    const stars = generateStarRating(product.rating);

    return `
        <div class="product-card bg-white rounded-lg shadow-lg overflow-hidden fade-in">
            <div class="relative">
                <img src="${product.image}" alt="${product.name}" class="w-full h-64 object-cover"
                     onerror="this.src='https://via.placeholder.com/300x300/F77786/FFFFFF?text=Producto'">
                ${discountBadge}
                ${newBadge}
                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                    <button class="bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-300 view-details-btn"
                    data-product-id="${product.id}">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="category-tag inline-block mb-2">${getCategoryName(product.category.slug)}</div>
                ${product.brand ? `<div class="text-xs text-gray-500 mb-1">${product.brand}</div>` : ''}
                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">${product.name}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${product.description}</p>

                <div class="flex items-center mb-3">
                    <div class="star-rating mr-2">${stars}</div>
                    <span class="text-sm text-gray-600">(${product.reviews} reseñas)</span>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold text-red-600">$${product.price.toFixed(2)}</span>
                        ${product.originalPrice > product.price ?
                            `<span class="text-lg text-gray-400 line-through">$${product.originalPrice.toFixed(2)}</span>` : ''}
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-gray-400 hover:text-red-600 transition-colors p-2"
                                onclick="toggleWishlist(${product.id})">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="text-gray-400 hover:text-blue-600 transition-colors p-2"
                                onclick="shareProduct(${product.id})">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-2">
                    <span class="text-xs text-gray-500">Stock: ${product.stock} disponibles</span>
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
        stars += '<i class="fas fa-star text-yellow-400"></i>';
    }

    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
    }

    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star text-yellow-400"></i>';
    }

    return stars;
}

function getCategoryName(category) {
    const categoryNames = {
        'electronica': 'Electrónicos',
        'ropa': 'Ropa',
        'hogar': 'Hogar',
        'deportes': 'Deportes',
        'libros': 'Libros',
        'juguetes': 'Juguetes',
        'belleza': 'Belleza',
        'automotriz': 'Automotriz',
        'general': 'General'
    };
    return categoryNames[category] || category.charAt(0).toUpperCase() + category.slice(1);
}

function filterProducts() {
    const activeCategory = document.querySelector('.filter-btn.active')?.dataset.category || 'all';
    console.log('Active category:', activeCategory);
    console.log('Products before filter:', products.length);
    const minPrice = parseFloat(document.getElementById('minPrice')?.value) || 0;
    const maxPrice = parseFloat(document.getElementById('maxPrice')?.value) || Infinity;
    const selectedRatings = Array.from(document.querySelectorAll('.rating-filter:checked'))
        .map(cb => parseFloat(cb.dataset.rating));

    filteredProducts = products.filter(product => {
        const matchesCategory = activeCategory === 'all' || product.category.slug === activeCategory;
        console.log('Product:', product.name, 'Category:', product.category.slug, 'Matches:', matchesCategory);
        const matchesPrice = product.price >= minPrice && product.price <= maxPrice;
        const matchesRating = selectedRatings.length === 0 ||
            selectedRatings.some(rating => product.rating >= rating);

        return matchesCategory && matchesPrice && matchesRating;
    });

    currentPage = 1;
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
            filteredProducts.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'name-asc':
            filteredProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'name-desc':
            filteredProducts.sort((a, b) => b.name.localeCompare(a.name));
            break;
        default:
            // Featured - mantener orden original
            filterProducts();
            return;
    }
    displayProducts();
}

function clearAllFilters() {
    // Resetear categoría
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.filter-btn[data-category="all"]')?.classList.add('active');

    // Resetear precios
    if (document.getElementById('minPrice')) document.getElementById('minPrice').value = '';
    if (document.getElementById('maxPrice')) document.getElementById('maxPrice').value = '';

    // Resetear calificaciones
    document.querySelectorAll('.rating-filter').forEach(cb => cb.checked = false);

    // Resetear ordenamiento
    if (document.getElementById('sortBy')) document.getElementById('sortBy').value = 'featured';

    // Resetear búsqueda
    if (document.getElementById('searchInput')) document.getElementById('searchInput').value = '';

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
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = totalItems;
        badge.style.display = totalItems > 0 ? 'inline' : 'none';
    }
}

function updateMiniCart() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');

    if (!cartItems || !cartTotal) return;

    if (cart.length === 0) {
        cartItems.innerHTML = '<p class="text-gray-500 text-center py-4">Tu carrito está vacío</p>';
        cartTotal.textContent = '$0.00';
        return;
    }

    cartItems.innerHTML = cart.map(item => `
        <div class="flex items-center space-x-3 p-2 border-b">
            <img src="${item.image}" alt="${item.name}" class="w-12 h-12 object-cover rounded"
                 onerror="this.src='https://via.placeholder.com/48x48/F77786/FFFFFF?text=P'">
            <div class="flex-1">
                <h4 class="text-sm font-semibold line-clamp-1">${item.name}</h4>
                <p class="text-xs text-gray-600">Cantidad: ${item.quantity}</p>
                <p class="text-sm font-bold text-red-600">$${(item.price * item.quantity).toFixed(2)}</p>
            </div>
            <button class="text-red-500 hover:text-red-700 remove-item" data-product-id="${item.id}">
                <i class="fas fa-trash text-sm"></i>
            </button>
        </div>
    `).join('');

    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    cartTotal.textContent = `$${total.toFixed(2)}`;

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
    if (miniCart) {
        miniCart.classList.toggle('hidden');
        updateMiniCart();
    }
}

function showAddToCartNotification(productName) {
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

    if (!pagination) return;

    if (totalPages <= 1) {
        pagination.classList.add('hidden');
        return;
    }

    pagination.classList.remove('hidden');

    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const pageNumbers = document.getElementById('pageNumbers');

    if (prevBtn) prevBtn.disabled = currentPage === 1;
    if (nextBtn) nextBtn.disabled = currentPage === totalPages;

    if (pageNumbers) {
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
    }

    // Event listeners para prev/next
    if (prevBtn) {
        const newPrevBtn = prevBtn.cloneNode(true);
        prevBtn.replaceWith(newPrevBtn);
        newPrevBtn.addEventListener('click', () => goToPage(currentPage - 1));
    }

    if (nextBtn) {
        const newNextBtn = nextBtn.cloneNode(true);
        nextBtn.replaceWith(newNextBtn);
        newNextBtn.addEventListener('click', () => goToPage(currentPage + 1));
    }
}

function goToPage(page) {
    currentPage = page;
    displayProducts();
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateProductCount(total) {
    const countElement = document.getElementById('productCount');
    if (countElement) {
        countElement.textContent = `Mostrando ${total} productos`;
    }
}

function showNoProducts() {
    const grid = document.getElementById('productsGrid');
    const noProducts = document.getElementById('noProducts');
    const pagination = document.getElementById('pagination');

    if (grid) grid.classList.add('hidden');
    if (noProducts) noProducts.classList.remove('hidden');
    if (pagination) pagination.classList.add('hidden');
}

function hideNoProducts() {
    const noProducts = document.getElementById('noProducts');
    if (noProducts) noProducts.classList.add('hidden');
}

function showLoading() {
    const loading = document.getElementById('loadingSpinner');
    if (loading) loading.classList.remove('hidden');
}

function hideLoading() {
    const loading = document.getElementById('loadingSpinner');
    if (loading) loading.classList.add('hidden');
}

function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
    errorDiv.innerHTML = `
        <div class="flex items-center space-x-2">
            <i class="fas fa-exclamation-circle"></i>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(errorDiv);

    setTimeout(() => {
        errorDiv.remove();
    }, 5000);
}

// Funciones adicionales para mejorar la experiencia
function viewProductDetails(productId) {
    const product = products.find(p => p.id === productId);
    if (!product) return;

    // Crear el modal dinámicamente
    const modalHTML = `
        <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">${product.name}</h2>
                        <button onclick="closeProductModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <img src="${product.image}" alt="${product.name}" class="w-full h-64 object-cover rounded-lg mb-4">
                            <div class="flex space-x-2 overflow-x-auto">
                                <img src="${product.image}" alt="${product.name}" class="w-16 h-16 object-cover rounded cursor-pointer border-2 border-red-500" onclick="this.parentElement.previousElementSibling.src='${product.image}'">
                                ${product.gallery.map(img => `<img src="${img}" alt="${product.name}" class="w-16 h-16 object-cover rounded cursor-pointer border-2 border-gray-200 hover:border-red-500" onclick="this.parentElement.previousElementSibling.src='${img}'">`).join('')}
                            </div>
                        </div>

                        <div>
                            <div class="category-tag inline-block mb-2">${getCategoryName(product.category.slug)}</div>
                            ${product.brand ? `<div class="text-sm text-gray-600 mb-2">${product.brand}</div>` : ''}
                            <div class="flex items-center mb-3">
                                ${generateStarRating(product.rating)}
                                <span class="ml-2 text-sm text-gray-600">(${product.reviews} reseñas)</span>
                            </div>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="text-3xl font-bold text-red-600">$${product.price.toFixed(2)}</span>
                                ${product.originalPrice > product.price ? `<span class="text-lg text-gray-400 line-through">$${product.originalPrice.toFixed(2)}</span>` : ''}
                            </div>
                            <p class="text-gray-700 mb-4">${product.description}</p>
                            <div class="text-sm text-gray-600 mb-4">Stock: ${product.stock} disponibles</div>
                            <button onclick="addToCartFromModal(${product.id});" class="btn-primary w-full py-3 rounded-lg font-semibold ${!product.inStock ? 'opacity-50 cursor-not-allowed' : ''}" ${!product.inStock ? 'disabled' : ''}>
                                <i class="fas fa-cart-plus"></i> ${product.inStock ? 'Agregar al Carrito' : 'Sin Stock'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Agregar al body
    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Cerrar al hacer clic fuera
    document.getElementById('productModal').addEventListener('click', function(e) {
        if (e.target === this) closeProductModal();
    });
}

// Función para cerrar el modal
function closeProductModal() {
    const modal = document.getElementById('productModal');
    if (modal) modal.remove();
}

function toggleWishlist(productId) {

    // Por ahora solo mostrar un mensaje
    showAddToCartNotification('Producto agregado a favoritos');
    // Implementar funcionalidad de lista de deseos
    console.log('Toggle wishlist for product:', productId);
    // Aquí puedes agregar la lógica para manejar la lista de deseos
}

function shareProduct(productId) {
    // Implementar funcionalidad para compartir producto
    const product = products.find(p => p.id === productId);
    if (product && navigator.share) {
        navigator.share({
            title: product.name,
            text: product.description,
            url: window.location.href
        });
    } else {
        // Fallback: copiar URL al portapapeles
        navigator.clipboard.writeText(window.location.href);
        showAddToCartNotification('Enlace copiado al portapapeles');
    }
}

// Función para recargar productos (útil después de cambios en admin)
function refreshProducts() {
    loadProducts();
}

// Cerrar modales al hacer clic fuera
document.addEventListener('click', function(e) {
    const categoriesModal = document.getElementById('categoriesModal');
    const categoriesButton = document.getElementById('categoriesButton');

    if (categoriesModal && categoriesButton && !categoriesButton.contains(e.target) && !categoriesModal.contains(e.target)) {
        categoriesModal.classList.add('hidden');
    }

    const miniCart = document.getElementById('miniCart');
    const cartToggle = document.getElementById('cartToggle');

    if (miniCart && cartToggle && !cartToggle.contains(e.target) && !miniCart.contains(e.target)) {
        miniCart.classList.add('hidden');
    }

    const filterSidebar = document.getElementById('filterSidebar');
    const toggleFilters = document.getElementById('toggleFilters');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (filterSidebar && toggleFilters && window.innerWidth < 1024 &&
        !toggleFilters.contains(e.target) && !filterSidebar.contains(e.target)) {
        filterSidebar.classList.remove('active');
        if (sidebarOverlay) sidebarOverlay.classList.remove('active');
    }

});
// Hacer la función closeProductModal globalmente accesible


function addToCartFromModal(productId) {
    addToCart(productId); // Llama a la función normal
    closeProductModal(); // Cierra el modal después
}

// Hacer la función globalmente accesible

window.viewProductDetails = viewProductDetails;
window.closeProductModal = closeProductModal;
window.addToCart = addToCart;
window.toggleWishlist = toggleWishlist;
window.shareProduct = shareProduct;
window.addToCartFromModal = addToCartFromModal;
