
        // Datos de productos
        const products = [
            {
                id: 1,
                name: "Cargador Inalámbrico Premium",
                description: "Carga rápida y segura para todos tus dispositivos",
                price: 49.99,
                image: "https://via.placeholder.com/400x300/F77786/FFFFFF?text=Cargador",
                category: "electronics"
            },
            {
                id: 2,
                name: "Auriculares Bluetooth Pro",
                description: "Sonido de alta calidad con cancelación de ruido",
                price: 89.99,
                image: "https://via.placeholder.com/400x300/998486/FFFFFF?text=Auriculares",
                category: "electronics"
            },
            {
                id: 3,
                name: "Smartwatch Deportivo",
                description: "Monitorea tu salud y mantente conectado",
                price: 199.99,
                image: "https://via.placeholder.com/400x300/D1A1A7/FFFFFF?text=Smartwatch",
                category: "electronics"
            },
            {
                id: 4,
                name: "Mochila Inteligente",
                description: "Con puerto USB y compartimento para laptop",
                price: 79.99,
                image: "https://via.placeholder.com/400x300/EB0924/FFFFFF?text=Mochila",
                category: "accessories"
            },
            {
                id: 5,
                name: "Lámpara LED Inteligente",
                description: "Control por voz y múltiples colores",
                price: 34.99,
                image: "https://via.placeholder.com/400x300/C7AFB2/FFFFFF?text=Lámpara",
                category: "home"
            },
            {
                id: 6,
                name: "Teclado Mecánico RGB",
                description: "Para gamers y profesionales exigentes",
                price: 129.99,
                image: "https://via.placeholder.com/400x300/F77786/FFFFFF?text=Teclado",
                category: "electronics"
            }
        ];

        // Variables globales
        let currentIndex = 0;
        let cart = [];
        const totalItems = document.querySelectorAll('.carousel-item').length;

        // Funciones del carrusel
        function updateCarousel() {
            const offset = -currentIndex * 100;
            document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
            updateIndicators();
        }

        function updateIndicators() {
            document.querySelectorAll('.carousel-indicator').forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentIndex);
            });
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalItems;
            updateCarousel();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalItems) % totalItems;
            updateCarousel();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateCarousel();
        }

        // Renderizar productos
        function renderProducts() {
            const grid = document.getElementById('productsGrid');
            grid.innerHTML = products.map(product => `
                <div class="product-card scroll-animate p-6" data-product-id="${product.id}">
                    <div class="overflow-hidden rounded-lg mb-4">
                        <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover">
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-primary">${product.name}</h3>
                    <p class="text-gray-600 mb-4">${product.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-2xl font-bold text-primary">$${product.price}</span>
                        <button onclick="addToCart(${product.id})" class="btn-primary py-2 px-4 rounded-lg transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-cart-plus mr-2"></i>Agregar
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Funciones del carrito
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ ...product, quantity: 1 });
            }
            
            updateCartUI();
            showCartNotification();
        }

        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCartUI();
        }

        function updateCartUI() {
            const cartBadge = document.getElementById('cartBadge');
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            
            const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
            const totalPrice = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            cartBadge.textContent = totalItems;
            cartTotal.textContent = `$${totalPrice.toFixed(2)}`;
            
            cartItems.innerHTML = cart.map(item => `
                <div class="flex items-center justify-between p-2 border-b">
                    <div class="flex-1">
                        <h4 class="font-medium text-sm">${item.name}</h4>
                        <p class="text-xs text-gray-500">$${item.price} x ${item.quantity}</p>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash text-xs"></i>
                    </button>
                </div>
            `).join('');
        }

        function showCartNotification() {
            // Crear notificación temporal
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-primary text-white p-4 rounded-lg shadow-lg z-50 animate-fadeInUp';
            notification.innerHTML = '<i class="fas fa-check mr-2"></i>Producto agregado al carrito';
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Funciones de animación en scroll
        function handleScrollAnimations() {
            const elements = document.querySelectorAll('.scroll-animate');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });

            elements.forEach(el => observer.observe(el));
        }

        // Funciones del modal de categorías
        function setupCategoriesModal() {
            const container = document.getElementById('categoriesContainer');
            const modal = document.getElementById('modal');
            
            container.addEventListener('mouseenter', () => {
                modal.classList.remove('hidden');
                setTimeout(() => modal.classList.add('show'), 10);
            });
            
            container.addEventListener('mouseleave', () => {
                modal.classList.remove('show');
                setTimeout(() => modal.classList.add('hidden'), 300);
            });
        }

        // Setup del mini carrito
        function setupMiniCart() {
            const cartToggle = document.getElementById('cartToggle');
            const miniCart = document.getElementById('miniCart');
            const closeMiniCart = document.getElementById('closeMiniCart');
            
            cartToggle.addEventListener('click', (e) => {
                e.preventDefault();
                miniCart.classList.toggle('hidden');
            });
            
            closeMiniCart.addEventListener('click', () => {
                miniCart.classList.add('hidden');
            });
            
            // Cerrar al hacer clic fuera
            document.addEventListener('click', (e) => {
                if (!miniCart.contains(e.target) && !cartToggle.contains(e.target)) {
                    miniCart.classList.add('hidden');
                }
            });
        }

        // Setup del menú móvil
        function setupMobileMenu() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const navLinks = document.getElementById('navLinks');
            
            mobileMenuBtn.addEventListener('click', () => {
                navLinks.classList.toggle('hidden');
                navLinks.classList.toggle('flex');
            });
        }

        // Función de búsqueda
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                const productCards = document.querySelectorAll('[data-product-id]');
                
                productCards.forEach(card => {
                    const productName = card.querySelector('h3').textContent.toLowerCase();
                    const productDesc = card.querySelector('p').textContent.toLowerCase();
                    
                    if (productName.includes(query) || productDesc.includes(query)) {
                        card.style.display = 'block';
                        card.classList.add('animate-fadeInUp');
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        // Función para efectos de hover en productos
        function setupProductEffects() {
            document.addEventListener('mouseover', (e) => {
                if (e.target.closest('.product-card')) {
                    const card = e.target.closest('.product-card');
                    card.style.transform = 'translateY(-10px) scale(1.02)';
                }
            });
            
            document.addEventListener('mouseout', (e) => {
                if (e.target.closest('.product-card')) {
                    const card = e.target.closest('.product-card');
                    card.style.transform = 'translateY(0) scale(1)';
                }
            });
        }

        // Funciones para categorías
        function filterByCategory(category) {
            const productCards = document.querySelectorAll('[data-product-id]');
            
            productCards.forEach(card => {
                const productId = parseInt(card.dataset.productId);
                const product = products.find(p => p.id === productId);
                
                if (category === 'all' || product.category === category) {
                    card.style.display = 'block';
                    card.classList.add('animate-fadeInUp');
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Setup de filtros de categoría
        function setupCategoryFilters() {
            document.querySelectorAll('.category-card').forEach(card => {
                card.addEventListener('click', () => {
                    const categoryName = card.querySelector('h3').textContent.toLowerCase();
                    let category = 'all';
                    
                    switch(categoryName) {
                        case 'electrónicos':
                            category = 'electronics';
                            break;
                        case 'ropa':
                            category = 'fashion';
                            break;
                        case 'hogar':
                            category = 'home';
                            break;
                        case 'deportes':
                            category = 'sports';
                            break;
                    }
                    
                    filterByCategory(category);
                    
                    // Scroll a la sección de productos
                    document.querySelector('#productsGrid').scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        }

        // Función para lazy loading de imágenes
        function setupLazyLoading() {
            const images = document.querySelectorAll('img[data-src]');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        }

        // Función para efectos de partículas (opcional)
        function createParticleEffect(element) {
            const particle = document.createElement('div');
            particle.className = 'absolute w-2 h-2 bg-primary rounded-full opacity-70';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.top = Math.random() * 100 + '%';
            particle.style.animation = 'pulse 2s infinite';
            
            element.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 2000);
        }

        // Función de inicialización
        function init() {
            renderProducts();
            setupCategoriesModal();
            setupMiniCart();
            setupMobileMenu();
            setupSearch();
            setupProductEffects();
            setupCategoryFilters();
            setupLazyLoading();
            handleScrollAnimations();
            
            // Auto-advance del carrusel
            setInterval(nextSlide, 5000);
            
            // Efectos adicionales
            document.querySelectorAll('.btn-primary').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    // Efecto de ripple
                    const ripple = document.createElement('span');
                    ripple.className = 'absolute inset-0 rounded-full bg-white opacity-25 scale-0';
                    ripple.style.animation = 'pulse 0.6s ease-out';
                    this.appendChild(ripple);
                    
                    setTimeout(() => ripple.remove(), 600);
                });
            });
            
            console.log('🚀 E-commerce inicializado correctamente!');
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', init);

        // Función adicional para notificaciones de stock
        function checkStock(productId) {
            // Simulación de verificación de stock
            const random = Math.random();
            if (random < 0.1) {
                showNotification('¡Última unidad disponible!', 'warning');
            } else if (random < 0.05) {
                showNotification('Producto agotado', 'error');
                return false;
            }
            return true;
        }

        function showNotification(message, type = 'success') {
            const colors = {
                success: 'bg-green-500',
                warning: 'bg-yellow-500',
                error: 'bg-red-500'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 left-1/2 transform -translate-x-1/2 ${colors[type]} text-white p-4 rounded-lg shadow-lg z-50 animate-fadeInUp`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'fadeInUp 0.5s ease-out reverse';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        }

        // Función para modo oscuro (bonus)
        function toggleDarkMode() {
            document.body.classList.toggle('dark');
            localStorage.setItem('darkMode', document.body.classList.contains('dark'));
        }

        // Restaurar modo oscuro del localStorage
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }
