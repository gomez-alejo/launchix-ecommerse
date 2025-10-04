// Datos de ejemplo de productos (estos vendrían de tu backend)
const productsData = [
    {
        id: 1,
        name: "Auriculares Bluetooth Premium",
        category: "electronics",
        price: 89.99,
        oldPrice: 129.99,
        rating: 4.8,
        sales: 1234,
        image: "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500",
        discount: 31,
        isBestseller: true
    },
    {
        id: 2,
        name: "Smartwatch Deportivo",
        category: "electronics",
        price: 199.99,
        oldPrice: 299.99,
        rating: 4.7,
        sales: 987,
        image: "https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=500",
        discount: 33,
        isBestseller: true
    },
    {
        id: 3,
        name: "Zapatillas Running Pro",
        category: "sports",
        price: 119.99,
        oldPrice: 159.99,
        rating: 4.9,
        sales: 1456,
        image: "https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=500",
        discount: 25,
        isBestseller: true
    },
    {
        id: 4,
        name: "Cámara Digital 4K",
        category: "electronics",
        price: 449.99,
        oldPrice: 599.99,
        rating: 4.6,
        sales: 678,
        image: "https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?w=500",
        discount: 25,
        isBestseller: false
    },
    {
        id: 5,
        name: "Chaqueta de Cuero",
        category: "fashion",
        price: 179.99,
        oldPrice: 249.99,
        rating: 4.5,
        sales: 543,
        image: "https://images.unsplash.com/photo-1551028719-00167b16eac5?w=500",
        discount: 28,
        isBestseller: false
    },
    {
        id: 6,
        name: "Lámpara LED Moderna",
        category: "home",
        price: 59.99,
        oldPrice: 89.99,
        rating: 4.7,
        sales: 890,
        image: "https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=500",
        discount: 33,
        isBestseller: true
    },
    {
        id: 7,
        name: "Mochila Táctica",
        category: "sports",
        price: 79.99,
        oldPrice: 109.99,
        rating: 4.8,
        sales: 1123,
        image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=500",
        discount: 27,
        isBestseller: true
    },
    {
        id: 8,
        name: "Cafetera Espresso",
        category: "home",
        price: 299.99,
        oldPrice: 399.99,
        rating: 4.9,
        sales: 765,
        image: "https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=500",
        discount: 25,
        isBestseller: false
    },
    {
        id: 9,
        name: "Gafas de Sol Polarizadas",
        category: "fashion",
        price: 49.99,
        oldPrice: 79.99,
        rating: 4.6,
        sales: 1345,
        image: "https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=500",
        discount: 38,
        isBestseller: true
    }
];

// Variables globales
let currentCategory = 'all';
let currentSort = 'popularity';
let displayedProducts = 6;
let filteredProducts = [...productsData];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
    initializeSorting();
    initializeScrollAnimations();
    renderProducts();
    setupLoadMore();
});

// Renderizar productos
function renderProducts() {
    const grid = document.getElementById('productsGrid');
    
    // Filtrar productos
    filteredProducts = productsData.filter(product => {
        if (currentCategory === 'all') return true;
        return product.category === currentCategory;
    });

    // Ordenar productos
    sortProducts();

    // Mostrar productos
    const productsToShow = filteredProducts.slice(0, displayedProducts);
    
    grid.innerHTML = productsToShow.map(product => createProductCard(product)).join('');

    // Actualizar botón "Cargar más"
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    if (loadMoreBtn) {
        if (displayedProducts >= filteredProducts.length) {
            loadMoreBtn.style.display = 'none';
        } else {
            loadMoreBtn.style.display = 'inline-block';
        }
    }

    // Añadir animaciones
    animateCards();
}

// Crear tarjeta de producto
function createProductCard(product) {
    const stars = '⭐'.repeat(Math.floor(product.rating));
    
    return `
        <div class="product-card scroll-animate" data-product-id="${product.id}">
            ${product.isBestseller ? '<div class="badge bestseller">🏆 Bestseller</div>' : '<div class="badge">Popular</div>'}
            
            <div class="image-container">
                <img src="${product.image}" alt="${product.name}" loading="lazy">
                <div class="quick-view" onclick="quickView(${product.id})">
                    Vista Rápida
                </div>
            </div>

            <div class="content">
                <div class="rating">
                    <span class="stars">${stars}</span>
                    <span class="sales-count">(${product.sales.toLocaleString()} vendidos)</span>
                </div>

                <h3>${product.name}</h3>

                <div class="price-section">
                    <span class="current-price">$${product.price.toFixed(2)}</span>
                    <span class="old-price">$${product.oldPrice.toFixed(2)}</span>
                    <span class="discount-badge">-${product.discount}%</span>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" onclick="addToCart(${product.id})">
                        Agregar al Carrito
                    </button>
                    <button class="btn btn-outline" onclick="addToWishlist(${product.id})">
                        ❤️
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Inicializar filtros
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remover clase active de todos los botones
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Agregar clase active al botón clickeado
            this.classList.add('active');
            
            // Actualizar categoría actual
            currentCategory = this.dataset.category;
            
            // Resetear productos mostrados
            displayedProducts = 6;
            
            // Renderizar productos
            renderProducts();
        });
    });
}

// Inicializar ordenamiento
function initializeSorting() {
    const sortSelect = document.getElementById('sortSelect');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentSort = this.value;
            renderProducts();
        });
    }
}

// Ordenar productos
function sortProducts() {
    switch(currentSort) {
        case 'popularity':
            filteredProducts.sort((a, b) => b.sales - a.sales);
            break;
        case 'price-low':
            filteredProducts.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            filteredProducts.sort((a, b) => b.price - a.price);
            break;
        case 'rating':
            filteredProducts.sort((a, b) => b.rating - a.rating);
            break;
    }
}

// Configurar botón "Cargar más"
function setupLoadMore() {
    const loadMoreBtn = document.getElementById('loadMoreBtn');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            displayedProducts += 6;
            renderProducts();
            
            // Scroll suave a los nuevos productos
            setTimeout(() => {
                const cards = document.querySelectorAll('.product-card');
                if (cards.length > displayedProducts - 6) {
                    cards[displayedProducts - 6].scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }
            }, 100);
        });
    }
}

// Animaciones de scroll
function initializeScrollAnimations() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    // Observar elementos con animación
    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });
}

// Animar tarjetas de productos
function animateCards() {
    const cards = document.querySelectorAll('.product-card');
    
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 50);
        }, index * 100);
    });

    // Re-observar los nuevos elementos
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, {
        threshold: 0.1
    });

    cards.forEach(card => observer.observe(card));
}

// Agregar al carrito
function addToCart(productId) {
    const product = productsData.find(p => p.id === productId);
    
    if (product) {
        // Aquí iría tu lógica para agregar al carrito
        console.log('Producto agregado al carrito:', product);
        
        // Mostrar notificación
        showNotification(`${product.name} agregado al carrito`, 'success');
        
        // Puedes hacer una petición AJAX a tu backend aquí
        // fetch('/cart/add', { ... })
    }
}

// Agregar a favoritos
function addToWishlist(productId) {
    const product = productsData.find(p => p.id === productId);
    
    if (product) {
        console.log('Producto agregado a favoritos:', product);
        showNotification(`${product.name} agregado a favoritos`, 'success');
        
        // Petición AJAX a tu backend
        // fetch('/wishlist/add', { ... })
    }
}

// Vista rápida del producto
function quickView(productId) {
    const product = productsData.find(p => p.id === productId);
    
    if (product) {
        console.log('Vista rápida:', product);
        // Aquí puedes abrir un modal con los detalles del producto
        alert(`Vista rápida de: ${product.name}\nPrecio: $${product.price}\nCalificación: ${product.rating}⭐`);
    }
}

// Mostrar notificación
function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10b981' : '#3b82f6'};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Agregar estilos para las animaciones de notificación
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);