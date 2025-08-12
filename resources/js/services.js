// Datos de servicios
const services = [
    {
        name: "Pizza Express",
        category: "comida",
        price: 25000,
        rating: 4.8,
        reviews: 156,
        description: "Deliciosas pizzas artesanales con ingredientes frescos. Servicio a domicilio disponible.",
        image: "https://i.pinimg.com/736x/c3/15/f2/c315f238994440a1342f971546a2ce71.jpg",
        emoji: "🍕"
    },
    {
        name: "AutoLavado Premium",
        category: "autolavado",
        price: 15000,
        rating: 4.5,
        reviews: 89,
        description: "Servicio completo de lavado y encerado. Cuidamos tu vehículo como si fuera nuestro.",
        image: "https://i.pinimg.com/736x/4c/9e/b5/4c9eb52e97e1f2b7a84b6f17259435fa.jpg",
        emoji: "🚗"
    },
    {
        name: "Carpintería Artesanal",
        category: "carpinteria",
        price: 50000,
        rating: 4.9,
        reviews: 234,
        description: "Muebles a medida y reparaciones. Trabajamos con maderas de la mejor calidad.",
        image: "https://images.unsplash.com/photo-1504148455328-c376907d081c?w=300&h=200&fit=crop",
        emoji: "🔨"
    },
    {
        name: "Droguería Salud",
        category: "drogeria",
        price: 12000,
        rating: 4.6,
        reviews: 78,
        description: "Medicamentos, productos de cuidado personal y atención farmacéutica profesional.",
        image: "https://i.pinimg.com/736x/aa/ef/09/aaef09fcbf62ccb206cadcfa19faed8f.jpg",
        emoji: "💊"
    },
    {
        name: "Salón Elegancia",
        category: "belleza",
        price: 35000,
        rating: 4.7,
        reviews: 198,
        description: "Cortes, peinados, tratamientos faciales y servicios de spa. Tu belleza es nuestra prioridad.",
        image: "https://images.unsplash.com/photo-1562322140-8baeececf3df?w=300&h=200&fit=crop",
        emoji: "💄"
    },
    {
        name: "TecniRepara",
        category: "reparaciones",
        price: 40000,
        rating: 4.4,
        reviews: 112,
        description: "Reparación de electrodomésticos, celulares y equipos electrónicos. Servicio técnico especializado.",
        image: "https://images.unsplash.com/photo-1581244277943-fe4a9c777189?w=300&h=200&fit=crop",
        emoji: "🔧"
    },
    {
        name: "Sabor Casero",
        category: "comida",
        price: 18000,
        rating: 4.3,
        reviews: 67,
        description: "Comida tradicional preparada con amor. Menús diarios y platos a la carta.",
        image: "https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=300&h=200&fit=crop",
        emoji: "🍕"
    },
    {
        name: "EcoWash",
        category: "autolavado",
        price: 20000,
        rating: 4.8,
        reviews: 143,
        description: "Lavado ecológico sin químicos dañinos. Cuidamos tu auto y el medio ambiente.",
        image: "https://images.unsplash.com/photo-1520340356584-f9917d1eea6f?w=300&h=200&fit=crop",
        emoji: "🚗"
    }
];

// Función para crear estrellitas
function createStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    let stars = '';
    
    for (let i = 0; i < fullStars; i++) {
        stars += '★';
    }
    
    if (hasHalfStar) {
        stars += '☆';
    }
    
    while (stars.length < 5) {
        stars += '☆';
    }
    
    return stars;
}

// Función para actualizar el estilo de los filtros de categoría
function updateCategoryFilterStyles() {
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        const label = checkbox.nextElementSibling;
        if (checkbox.checked) {
            label.classList.remove('bg-gray-100', 'text-gray-700', 'border-2', 'border-gray-200');
            label.classList.add('bg-accent', 'text-white');
        } else {
            label.classList.remove('bg-accent', 'text-white');
            label.classList.add('bg-gray-100', 'text-gray-700', 'border-2', 'border-gray-200');
        }
    });
}

// Función para filtrar servicios
function filterServices() {
    const selectedCategories = Array.from(document.querySelectorAll('.filter-checkbox:checked')).map(cb => cb.dataset.category);
    const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseInt(document.getElementById('maxPrice').value) || Infinity;
    const selectedRatings = Array.from(document.querySelectorAll('input[data-rating]:checked')).map(cb => parseFloat(cb.dataset.rating));
    
    let filteredServices = services;
    
    // Filtrar por categoría
    if (selectedCategories.length > 0 && !selectedCategories.includes('todos')) {
        filteredServices = filteredServices.filter(service => selectedCategories.includes(service.category));
    }
    
    // Filtrar por precio
    filteredServices = filteredServices.filter(service => service.price >= minPrice && service.price <= maxPrice);
    
    // Filtrar por calificación
    if (selectedRatings.length > 0) {
        filteredServices = filteredServices.filter(service => {
            return selectedRatings.some(rating => service.rating >= rating);
        });
    }
    
    renderServices(filteredServices);
    document.getElementById('results-count').textContent = filteredServices.length;
}

// Función para renderizar servicios
function renderServices(servicesToRender) {
    const grid = document.getElementById('services-grid');
    if (!grid) return; // Evita errores si el grid no existe

    const categoryNames = {
        'comida': 'Comida',
        'autolavado': 'Autolavado',
        'carpinteria': 'Carpintería',
        'drogeria': 'Droguería',
        'belleza': 'Belleza',
        'reparaciones': 'Reparaciones'
    };

    const categoryIcons = {
        'comida': '<i class="fas fa-utensils mr-1"></i>',
        'autolavado': '<i class="fas fa-car mr-1"></i>',
        'carpinteria': '<i class="fas fa-hammer mr-1"></i>',
        'drogeria': '<i class="fas fa-pills mr-1"></i>',
        'belleza': '<i class="fas fa-cut mr-1"></i>',
        'reparaciones': '<i class="fas fa-wrench mr-1"></i>'
    };

    grid.innerHTML = servicesToRender.map(service => `
        <div class="service-card bg-white rounded-lg shadow-lg overflow-hidden" data-category="${service.category}" data-price="${service.price}" data-rating="${service.rating}">
            <div class="relative">
                <img src="${service.image}" alt="${service.name}" class="w-full h-48 object-cover">
                <div class="absolute top-3 left-3 bg-primary text-white px-2 py-1 rounded-full text-xs font-medium">
                    ${categoryIcons[service.category] || ''}${categoryNames[service.category] || ''}
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg text-gray-800 mb-2">${service.name}</h3>
                <p class="text-gray-600 text-sm mb-3">${service.description}</p>
                <div class="flex items-center mb-3">
                    <div class="star-rating text-sm">${createStars(service.rating)}</div>
                    <span class="ml-2 text-sm text-gray-600">(${service.reviews} reseñas)</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-primary font-bold text-lg">$${service.price.toLocaleString()}</div>
                    <button class="bg-primary text-white px-4 py-2 rounded-md hover:bg-secondary transition-colors duration-300 text-sm">
                        Ver Más
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// --- EVENTOS Y RENDER PRINCIPAL ---
document.addEventListener('DOMContentLoaded', function() {
    // Marcar "Todas las categorías" por defecto
    const todosCheckbox = document.querySelector('input[data-category="todos"]');
    if (todosCheckbox) {
        todosCheckbox.checked = true;
        updateCategoryFilterStyles();
    }

    // Filtros de categoría
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.dataset.category === 'todos') {
                document.querySelectorAll('.filter-checkbox').forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            } else {
                const todosCheckbox = document.querySelector('input[data-category="todos"]');
                if (todosCheckbox) todosCheckbox.checked = false;
            }
            updateCategoryFilterStyles();
            filterServices();
        });
    });

    // Filtros de precio
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    if (minPriceInput) minPriceInput.addEventListener('input', filterServices);
    if (maxPriceInput) maxPriceInput.addEventListener('input', filterServices);

    // Filtros de calificación
    document.querySelectorAll('input[data-rating]').forEach(checkbox => {
        checkbox.addEventListener('change', filterServices);
    });

    // Limpiar filtros
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
            if (minPriceInput) minPriceInput.value = '';
            if (maxPriceInput) maxPriceInput.value = '';
            const todosCheckbox = document.querySelector('input[data-category="todos"]');
            if (todosCheckbox) todosCheckbox.checked = true;
            updateCategoryFilterStyles();
            filterServices();
        });
    }

    // Renderizar servicios iniciales
    renderServices(services);

    // Inicializar animaciones
    setTimeout(() => {
        const cards = document.querySelectorAll('.service-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }, 100);
});

// Animaciones al hacer scroll
document.addEventListener('scroll', function() {
    const cards = document.querySelectorAll('.service-card');
    cards.forEach(card => {
        const rect = card.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }
    });
});