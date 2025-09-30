// ====================================
// CART SHOPPING SYSTEM
// Sistema completo de carrito de compras
// ====================================

// Variables globales
let cartItems = [];
let allProducts = [];
let appliedCoupon = null;

// Configuración de cupones disponibles
const AVAILABLE_COUPONS = {
    'DESCUENTO10': { discount: 10, type: 'percentage', minAmount: 50000 },
    'DESCUENTO20': { discount: 20, type: 'percentage', minAmount: 100000 },
    'VERANO2024': { discount: 15000, type: 'fixed', minAmount: 0 },
    'BIENVENIDO': { discount: 5, type: 'percentage', minAmount: 0 }
};

// ====================================
// PRODUCTOS ESTÁTICOS
// ====================================
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

// ====================================
// INICIALIZACIÓN
// ====================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🛒 Inicializando sistema de carrito...');
    initializeCart();
});

async function initializeCart() {
    try {
        showLoading();
        
        console.log('📦 Paso 1: Cargando productos desde API...');
        await loadAllProducts();
        console.log('✅ Productos cargados:', allProducts.length);
        
        console.log('🔄 Paso 2: Cargando carrito desde localStorage...');
        loadCartFromStorage();
        console.log('✅ Carrito cargado:', cartItems.length, 'productos');
        
        console.log('⚙️ Paso 3: Configurando event listeners...');
        setupEventListeners();
        
        console.log('🎨 Paso 4: Renderizando carrito...');
        renderCart();
        
        console.log('💰 Paso 5: Actualizando resumen...');
        updateSummary();
        
        hideLoading();
        console.log('✅ Sistema de carrito inicializado correctamente');
    } catch (error) {
        console.error('❌ Error inicializando carrito:', error);
        showErrorNotification('Error al cargar el carrito. Por favor, recarga la página.');
        hideLoading();
    }
}

// ====================================
// CARGA DE DATOS
// ====================================
async function loadAllProducts() {
    try {
        const response = await fetch('/api/productos', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': window.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP Error: ${response.status}`);
        }

        const data = await response.json();
        
        if (data.success && Array.isArray(data.data)) {
            const apiProducts = data.data.map(product => transformProductData(product));
            allProducts = [...getStaticProducts(), ...apiProducts];
        } else if (Array.isArray(data)) {
            const apiProducts = data.map(product => transformProductData(product));
            allProducts = [...getStaticProducts(), ...apiProducts];
        } else {
            // Si falla la API, usar solo productos estáticos
            allProducts = getStaticProducts();
        }
        
        console.log('📊 Total productos disponibles:', allProducts.length);
    } catch (error) {
        console.error('⚠️ Error cargando productos de API, usando productos estáticos:', error);
        allProducts = getStaticProducts();
    }
}

function transformProductData(laravelProduct) {
    return {
        id: laravelProduct.id,
        name: laravelProduct.name,
        category: {
            name: laravelProduct.category?.name || 'General',
            slug: laravelProduct.category?.slug || 'general'
        },
        price: parseFloat(laravelProduct.price),
        originalPrice: laravelProduct.original_price ? 
            parseFloat(laravelProduct.original_price) : 
            parseFloat(laravelProduct.price),
        rating: parseFloat(laravelProduct.rating) || 4.0,
        reviews: parseInt(laravelProduct.reviews_count) || 0,
        image: laravelProduct.main_image || 
            'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Producto',
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

function isProductNew(createdAt) {
    if (!createdAt) return false;
    const productDate = new Date(createdAt);
    const now = new Date();
    const diffTime = Math.abs(now - productDate);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays <= 30;
}

function calculateDiscount(currentPrice, originalPrice) {
    if (!originalPrice || originalPrice <= currentPrice) return 0;
    return Math.round(((originalPrice - currentPrice) / originalPrice) * 100);
}

// ====================================
// GESTIÓN DE CARRITO - LOCALSTORAGE
// ====================================
function loadCartFromStorage() {
    try {
        const savedCart = localStorage.getItem('shopping_cart');
        console.log('📂 localStorage data:', savedCart);
        
        if (savedCart) {
            const parsedCart = JSON.parse(savedCart);
            console.log('📦 Parsed cart:', parsedCart);
            
            // Sincronizar con productos actuales de la API
            cartItems = parsedCart.map(cartItem => {
                const currentProduct = allProducts.find(p => p.id === cartItem.id);
                if (currentProduct) {
                    console.log(`✅ Producto ${cartItem.id} encontrado y sincronizado`);
                    return {
                        ...currentProduct,
                        quantity: cartItem.quantity
                    };
                }
                console.log(`⚠️ Producto ${cartItem.id} no encontrado, usando datos del carrito`);
                return cartItem;
            }).filter(item => item.inStock);
            
            console.log('🛒 Cart items final:', cartItems);
        } else {
            console.log('⚠️ No hay carrito en localStorage');
            cartItems = [];
        }
    } catch (error) {
        console.error('❌ Error cargando carrito:', error);
        cartItems = [];
    }
}

function saveCartToStorage() {
    try {
        localStorage.setItem('shopping_cart', JSON.stringify(cartItems));
        console.log('💾 Carrito guardado en localStorage');
    } catch (error) {
        console.error('❌ Error guardando carrito:', error);
    }
}

// ====================================
// RENDERIZADO
// ====================================
function renderCart() {
    const cartList = document.getElementById('cart-items-list');
    const emptyCart = document.getElementById('empty-cart');
    const cartCount = document.getElementById('cart-item-count');
    
    console.log('🎨 Renderizando carrito...');
    console.log('📦 Items a renderizar:', cartItems.length);
    
    if (!cartList) {
        console.error('❌ Elemento cart-items-list no encontrado');
        return;
    }
    
    // Actualizar contador
    if (cartCount) {
        cartCount.textContent = cartItems.length;
    }
    
    // Mostrar estado vacío o productos
    if (cartItems.length === 0) {
        console.log('🔄 Mostrando estado vacío');
        cartList.innerHTML = '';
        if (emptyCart) emptyCart.classList.remove('hidden');
        disableCheckout();
        return;
    }
    
    console.log('✅ Renderizando productos del carrito');
    if (emptyCart) emptyCart.classList.add('hidden');
    
    // Renderizar productos
    cartList.innerHTML = cartItems.map(item => createCartItemHTML(item)).join('');
    
    // Configurar event listeners de los items
    setupItemEventListeners();
    
    enableCheckout();
}

function createCartItemHTML(item) {
    const itemTotal = item.price * item.quantity;
    const hasDiscount = item.discount > 0;
    
    return `
        <div class="cart-item p-6 hover:bg-lighter transition-all duration-300" data-product-id="${item.id}">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Imagen del producto -->
                <div class="flex-shrink-0">
                    <div class="relative w-32 h-32 rounded-xl overflow-hidden shadow-lg group">
                        <img src="${item.image}" 
                             alt="${item.name}" 
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                             onerror="this.src='https://via.placeholder.com/128x128/F77786/FFFFFF?text=Producto'">
                        ${hasDiscount ? `
                            <div class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                -${item.discount}%
                            </div>
                        ` : ''}
                        ${item.isNew ? `
                            <div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg">
                                NUEVO
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                <!-- Información del producto -->
                <div class="flex-1 min-w-0">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-black text-dark mb-2 line-clamp-2">${item.name}</h3>
                            <div class="flex flex-wrap items-center gap-3 mb-3">
                                <span class="inline-flex items-center px-3 py-1 bg-accent rounded-lg text-xs font-bold text-primary">
                                    ${item.category.name}
                                </span>
                                ${item.brand ? `
                                    <span class="text-sm font-semibold text-medium">
                                        ${item.brand}
                                    </span>
                                ` : ''}
                                ${item.sku ? `
                                    <span class="text-xs text-gray">
                                        SKU: ${item.sku}
                                    </span>
                                ` : ''}
                            </div>
                            <p class="text-sm text-medium line-clamp-2 mb-3">${item.description}</p>
                            
                            <!-- Stock warning -->
                            ${item.stock < 5 ? `
                                <div class="inline-flex items-center gap-2 px-3 py-1 bg-orange-light rounded-lg">
                                    <svg class="w-4 h-4 text-orange" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                    <span class="text-xs font-bold text-orange">Solo quedan ${item.stock}</span>
                                </div>
                            ` : `
                                <div class="text-xs text-medium">
                                    Stock disponible: ${item.stock} unidades
                                </div>
                            `}
                        </div>
                        
                        <!-- Precio y controles -->
                        <div class="flex flex-col items-end gap-4">
                            <!-- Precio -->
                            <div class="text-right">
                                <div class="flex items-center gap-2 mb-1">
                                    ${hasDiscount ? `
                                        <span class="text-lg text-gray line-through">
                                            ${item.originalPrice.toFixed(2)}
                                        </span>
                                    ` : ''}
                                    <span class="text-2xl font-black text-primary">
                                        ${item.price.toFixed(2)}
                                    </span>
                                </div>
                                <div class="text-sm text-medium">
                                    por unidad
                                </div>
                            </div>
                            
                            <!-- Cantidad -->
                            <div class="flex items-center gap-3 bg-white rounded-xl shadow-md border-2 border-gray-200 p-1">
                                <button class="decrease-quantity w-10 h-10 flex items-center justify-center bg-lighter hover:bg-accent rounded-lg font-bold text-primary transition-all hover:scale-110"
                                        data-product-id="${item.id}">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M5 12H19"/>
                                    </svg>
                                </button>
                                <input type="number" 
                                       class="quantity-input w-16 text-center font-black text-xl text-dark bg-transparent outline-none"
                                       value="${item.quantity}"
                                       min="1"
                                       max="${item.stock}"
                                       data-product-id="${item.id}">
                                <button class="increase-quantity w-10 h-10 flex items-center justify-center bg-lighter hover:bg-accent rounded-lg font-bold text-primary transition-all hover:scale-110"
                                        data-product-id="${item.id}">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M12 5V19M5 12H19"/>
                                    </svg>
                                </button>
                            </div>
                            
                            <!-- Subtotal y eliminar -->
                            <div class="flex items-center gap-4">
                                <div class="text-right">
                                    <div class="text-xs text-medium mb-1">Subtotal</div>
                                    <div class="text-2xl font-black text-dark">
                                        ${itemTotal.toFixed(2)}
                                    </div>
                                </div>
                                <button class="remove-item w-12 h-12 flex items-center justify-center bg-red-50 hover:bg-red-100 rounded-xl text-red-600 transition-all hover:scale-110 group"
                                        data-product-id="${item.id}"
                                        title="Eliminar producto">
                                    <svg class="w-6 h-6 transition-transform group-hover:scale-110" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2M10 11v6M14 11v6"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ====================================
// EVENT LISTENERS
// ====================================
function setupEventListeners() {
    const clearCartBtn = document.getElementById('clear-cart-btn');
    const clearCartMobile = document.getElementById('clear-cart-mobile');
    
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', clearCart);
    }
    if (clearCartMobile) {
        clearCartMobile.addEventListener('click', clearCart);
    }
    
    const applyCouponBtn = document.getElementById('apply-coupon-btn');
    if (applyCouponBtn) {
        applyCouponBtn.addEventListener('click', applyCoupon);
    }
    
    const couponInput = document.getElementById('coupon-input');
    if (couponInput) {
        couponInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                applyCoupon();
            }
        });
    }
    
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', proceedToCheckout);
    }
}

function setupItemEventListeners() {
    document.querySelectorAll('.increase-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            updateQuantity(productId, 1);
        });
    });
    
    document.querySelectorAll('.decrease-quantity').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            updateQuantity(productId, -1);
        });
    });
    
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const productId = parseInt(this.dataset.productId);
            const newQuantity = parseInt(this.value);
            setQuantity(productId, newQuantity);
        });
    });
    
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = parseInt(this.dataset.productId);
            removeItem(productId);
        });
    });
}

// ====================================
// OPERACIONES DEL CARRITO
// ====================================
function updateQuantity(productId, change) {
    const item = cartItems.find(i => i.id === productId);
    if (!item) return;
    
    const newQuantity = item.quantity + change;
    setQuantity(productId, newQuantity);
}

function setQuantity(productId, quantity) {
    const item = cartItems.find(i => i.id === productId);
    if (!item) return;
    
    if (quantity < 1) {
        removeItem(productId);
        return;
    }
    
    if (quantity > item.stock) {
        showWarningNotification(`Solo hay ${item.stock} unidades disponibles de este producto`);
        quantity = item.stock;
    }
    
    item.quantity = quantity;
    saveCartToStorage();
    renderCart();
    updateSummary();
}

function removeItem(productId) {
    const item = cartItems.find(i => i.id === productId);
    if (!item) return;
    
    if (confirm(`¿Estás seguro de eliminar "${item.name}" del carrito?`)) {
        cartItems = cartItems.filter(i => i.id !== productId);
        saveCartToStorage();
        renderCart();
        updateSummary();
        showSuccessNotification('Producto eliminado del carrito');
    }
}

function clearCart() {
    if (cartItems.length === 0) return;
    
    if (confirm('¿Estás seguro de vaciar todo el carrito?')) {
        cartItems = [];
        appliedCoupon = null;
        saveCartToStorage();
        renderCart();
        updateSummary();
        showSuccessNotification('Carrito vaciado');
    }
}

// ====================================
// CUPONES
// ====================================
function applyCoupon() {
    const couponInput = document.getElementById('coupon-input');
    if (!couponInput) return;
    
    const code = couponInput.value.trim().toUpperCase();
    
    if (!code) {
        showWarningNotification('Por favor, ingresa un código de cupón');
        return;
    }
    
    const coupon = AVAILABLE_COUPONS[code];
    
    if (!coupon) {
        showErrorNotification('Cupón inválido o expirado');
        return;
    }
    
    const subtotal = calculateSubtotal();
    
    if (subtotal < coupon.minAmount) {
        showWarningNotification(`Este cupón requiere una compra mínima de ${coupon.minAmount.toFixed(2)}`);
        return;
    }
    
    appliedCoupon = { code, ...coupon };
    updateSummary();
    showSuccessNotification(`Cupón "${code}" aplicado correctamente`);
    couponInput.value = '';
}

// ====================================
// CÁLCULOS
// ====================================
function calculateSubtotal() {
    return cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
}

function calculateDiscountAmount() {
    if (!appliedCoupon) return 0;
    
    const subtotal = calculateSubtotal();
    
    if (appliedCoupon.type === 'percentage') {
        return (subtotal * appliedCoupon.discount) / 100;
    } else {
        return appliedCoupon.discount;
    }
}

function calculateTotal() {
    const subtotal = calculateSubtotal();
    const discount = calculateDiscountAmount();
    return Math.max(0, subtotal - discount);
}

// ====================================
// ACTUALIZAR RESUMEN
// ====================================
function updateSummary() {
    const subtotal = calculateSubtotal();
    const discount = calculateDiscountAmount();
    const total = calculateTotal();
    
    const subtotalElement = document.getElementById('subtotal-amount');
    if (subtotalElement) {
        subtotalElement.textContent = `${subtotal.toFixed(2)}`;
    }
    
    const discountRow = document.getElementById('discount-row');
    const discountElement = document.getElementById('discount-amount');
    
    if (discount > 0) {
        if (discountRow) discountRow.classList.remove('hidden');
        if (discountElement) discountElement.textContent = `-${discount.toFixed(2)}`;
    } else {
        if (discountRow) discountRow.classList.add('hidden');
    }
    
    const totalElement = document.getElementById('total-amount');
    if (totalElement) {
        totalElement.textContent = `${total.toFixed(2)}`;
    }
}

// ====================================
// CHECKOUT
// ====================================
function enableCheckout() {
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = false;
    }
}

function disableCheckout() {
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;
    }
}

function proceedToCheckout() {
    if (cartItems.length === 0) {
        showWarningNotification('Tu carrito está vacío');
        return;
    }
    
    const checkoutData = {
        items: cartItems.map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            quantity: item.quantity,
            total: item.price * item.quantity
        })),
        subtotal: calculateSubtotal(),
        discount: calculateDiscountAmount(),
        total: calculateTotal(),
        coupon: appliedCoupon ? appliedCoupon.code : null
    };
    
    console.log('Checkout data:', checkoutData);
    showSuccessNotification('Procesando tu pedido...');
    
    sessionStorage.setItem('checkout_data', JSON.stringify(checkoutData));
}

// ====================================
// UTILIDADES
// ====================================
function showLoading() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.classList.remove('hidden');
}

function hideLoading() {
    const spinner = document.getElementById('loadingSpinner');
    if (spinner) spinner.classList.add('hidden');
}

function showSuccessNotification(message) {
    showNotification(message, 'success');
}

function showErrorNotification(message) {
    showNotification(message, 'error');
}

function showWarningNotification(message) {
    showNotification(message, 'warning');
}

function showNotification(message, type = 'info') {
    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-orange-500',
        info: 'bg-blue-500'
    };
    
    const icons = {
        success: '<path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"/>',
        error: '<path d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        warning: '<path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>',
        info: '<path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
    };
    
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${colors[type]} text-white px-6 py-4 rounded-xl shadow-2xl z-50 transform transition-all duration-300 flex items-center gap-3 max-w-md`;
    notification.innerHTML = `
        <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            ${icons[type]}
        </svg>
        <span class="font-bold">${message}</span>
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.style.transform = 'translateX(0)', 10);
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

// ====================================
// API GLOBAL
// ====================================
window.cartSystem = {
    refresh: initializeCart,
    addItem: function(productId, quantity = 1) {
        const product = allProducts.find(p => p.id === productId);
        if (!product || !product.inStock) return false;
        
        const existingItem = cartItems.find(i => i.id === productId);
        if (existingItem) {
            existingItem.quantity += quantity;
        } else {
            cartItems.push({ ...product, quantity });
        }
        
        saveCartToStorage();
        return true;
    },
    getCartCount: function() {
        return cartItems.reduce((sum, item) => sum + item.quantity, 0);
    },
    getCart: function() {
        return cartItems;
    }
};

console.log('Sistema de carrito cargado correctamente');