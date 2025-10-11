// home.js - Funcionalidades de la página principal
document.addEventListener('DOMContentLoaded', function() {
    
    // ===== HERO CAROUSEL =====
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.hero-dot');
    const prevBtn = document.getElementById('heroPrev');
    const nextBtn = document.getElementById('heroNext');
    let currentSlide = 0;
    let autoplayInterval = null;

    if (slides.length > 0) {
        // Mostrar slide específico
        function showSlide(index) {
            // Normalizar el índice
            if (index >= slides.length) {
                currentSlide = 0;
            } else if (index < 0) {
                currentSlide = slides.length - 1;
            } else {
                currentSlide = index;
            }

            // Actualizar slides
            slides.forEach((slide, i) => {
                if (i === currentSlide) {
                    slide.classList.remove('opacity-0');
                    slide.classList.add('opacity-100');
                } else {
                    slide.classList.remove('opacity-100');
                    slide.classList.add('opacity-0');
                }
            });

            // Actualizar dots
            dots.forEach((dot, i) => {
                if (i === currentSlide) {
                    dot.classList.remove('w-2', 'bg-white/50');
                    dot.classList.add('w-12', 'bg-white');
                } else {
                    dot.classList.remove('w-12', 'bg-white');
                    dot.classList.add('w-2', 'bg-white/50');
                }
            });
        }

        // Siguiente slide
        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        // Slide anterior
        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        // Iniciar autoplay
        function startAutoplay() {
            stopAutoplay(); // Limpiar cualquier intervalo previo
            autoplayInterval = setInterval(nextSlide, 5000); // Cambiar cada 5 segundos
        }

        // Detener autoplay
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }

        // Event listeners para botones
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                stopAutoplay();
                prevSlide();
                startAutoplay();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                stopAutoplay();
                nextSlide();
                startAutoplay();
            });
        }

        // Event listeners para dots
        dots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                stopAutoplay();
                showSlide(index);
                startAutoplay();
            });
        });

        // Pausar autoplay al hover
        const carouselContainer = document.getElementById('heroCarousel');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', stopAutoplay);
            carouselContainer.addEventListener('mouseleave', startAutoplay);
        }

        // Inicializar
        showSlide(0);
        startAutoplay();
    }

    // ===== COUNTDOWN TIMER (Oferta del día) =====
    function updateCountdown() {
        const now = new Date();
        const endOfDay = new Date();
        endOfDay.setHours(23, 59, 59, 999);
        
        const diff = endOfDay - now;
        
        const hours = Math.floor(diff / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
        
        const hoursEl = document.getElementById('hours');
        const minutesEl = document.getElementById('minutes');
        const secondsEl = document.getElementById('seconds');
        
        if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0');
        if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0');
        if (secondsEl) secondsEl.textContent = String(seconds).padStart(2, '0');
    }
    
    // Iniciar countdown si existen los elementos
    if (document.getElementById('hours')) {
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // ===== NEWSLETTER FORM =====
    const newsletterForm = document.querySelector('form');
    if (newsletterForm && newsletterForm.querySelector('input[type="email"]')) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const emailInput = this.querySelector('input[type="email"]');
            const email = emailInput.value.trim();
            
            if (email && validateEmail(email)) {
                // Aquí puedes hacer una petición AJAX a tu backend
                // Por ahora mostramos un mensaje de éxito
                showNotification('¡Gracias por suscribirte! Pronto recibirás nuestras ofertas.', 'success');
                this.reset();
            } else {
                showNotification('Por favor ingresa un correo electrónico válido.', 'error');
            }
        });
    }

    // ===== FUNCIONES AUXILIARES =====
    
    // Validar email
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    // Mostrar notificación (puedes personalizarla con una librería como Toastify)
    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 
            type === 'error' ? 'bg-red-500 text-white' : 
            'bg-blue-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animación de entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 10);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // ===== SMOOTH SCROLL PARA CATEGORÍAS =====
    const categoryScroll = document.querySelector('.scrollbar-hide');
    if (categoryScroll) {
        let isDown = false;
        let startX;
        let scrollLeft;

        categoryScroll.addEventListener('mousedown', (e) => {
            isDown = true;
            categoryScroll.style.cursor = 'grabbing';
            startX = e.pageX - categoryScroll.offsetLeft;
            scrollLeft = categoryScroll.scrollLeft;
        });

        categoryScroll.addEventListener('mouseleave', () => {
            isDown = false;
            categoryScroll.style.cursor = 'grab';
        });

        categoryScroll.addEventListener('mouseup', () => {
            isDown = false;
            categoryScroll.style.cursor = 'grab';
        });

        categoryScroll.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - categoryScroll.offsetLeft;
            const walk = (x - startX) * 2;
            categoryScroll.scrollLeft = scrollLeft - walk;
        });
    }

    // ===== LAZY LOADING PARA IMÁGENES =====
    const images = document.querySelectorAll('img[loading="lazy"]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.src; // Forzar recarga si es necesario
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
});