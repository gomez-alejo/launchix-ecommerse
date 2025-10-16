// =============================
// Servicios de ejemplo (se mantienen)
// =============================
const exampleServices = [
    {
        id: 'ejemplo-1',
        nombre_servicio: "Pizza Express",
        categoria: "comida",
        precio_base: 25000,
        descripcion: "Deliciosas pizzas artesanales con ingredientes frescos. Servicio a domicilio disponible.",
        imagen_principal: "images/ejemplo-servicios/pizza.jpg",
        direccion: "Calle 123, Ciudad",
        telefono: "1234567890",
        horario_atencion: "Lun-Dom 10:00-22:00"
    },
    {
        id: 'ejemplo-2',
        nombre_servicio: "AutoLavado Premium",
        categoria: "autolavado",
        precio_base: 15000,
        descripcion: "Servicio completo de lavado y encerado. Cuidamos tu vehículo como si fuera nuestro.",
        imagen_principal: "images/ejemplo-servicios/autolavado.jpg",
        direccion: "Av. Principal 456, Ciudad",
        telefono: "0987654321",
        horario_atencion: "Lun-Sab 8:00-18:00"
    },
    {
        id: 'ejemplo-3',
        nombre_servicio: "Carpintería Creativa",
        categoria: "carpinteria",
        precio_base: 30000,
        descripcion: "Muebles a medida, restauración y trabajos en madera de alta calidad.",
        imagen_principal: "images/ejemplo-servicios/carpinteria.jpg",
        direccion: "Cra 10 #45-67, Ciudad",
        telefono: "3216549870",
        horario_atencion: "Lun-Vie 9:00-18:00"
    },
    {
        id: 'ejemplo-4',
        nombre_servicio: "Belleza Total Spa",
        categoria: "belleza",
        precio_base: 40000,
        descripcion: "Spa, peluquería y tratamientos de belleza para toda la familia.",
        imagen_principal: "images/ejemplo-servicios/belleza.jpg",
        direccion: "Calle 50 #12-34, Ciudad",
        telefono: "3123456789",
        horario_atencion: "Mar-Sab 10:00-20:00"
    },
];

// Variable global para almacenar todos los servicios
let allServices = [];
let filteredServices = [];

// =============================
// Función para renderizar una card de servicio con Tailwind
// =============================
function renderServiceCard(servicio) {
    let imgSrc = 'https://via.placeholder.com/300x200?text=Sin+Imagen';
    if (servicio.imagen_principal) {
        if (servicio.imagen_principal.startsWith('images/')) {
            imgSrc = '/' + servicio.imagen_principal;
        } else if (servicio.imagen_principal.startsWith('http')) {
            imgSrc = servicio.imagen_principal;
        } else {
            imgSrc = '/storage/' + servicio.imagen_principal;
        }
    }
    
    const categoryIcons = {
        'comida': 'fa-utensils',
        'autolavado': 'fa-car',
        'carpinteria': 'fa-hammer',
        'drogeria': 'fa-pills',
        'belleza': 'fa-cut',
        'reparaciones': 'fa-wrench'
    };
    
    const categoryColors = {
        'comida': 'bg-orange-500',
        'autolavado': 'bg-blue-500',
        'carpinteria': 'bg-amber-600',
        'drogeria': 'bg-green-500',
        'belleza': 'bg-pink-500',
        'reparaciones': 'bg-gray-700'
    };
    
    const iconClass = categoryIcons[servicio.categoria] || 'fa-store';
    const colorClass = categoryColors[servicio.categoria] || 'bg-gray-500';
    
    return `
        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 flex flex-col h-full group">
            <!-- Imagen con overlay -->
            <div class="relative h-56 overflow-hidden">
                <img src="${imgSrc}" alt="${servicio.nombre_servicio}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                
                <!-- Badge de categoría -->
                <div class="absolute top-3 left-3 ${colorClass} text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                    <i class="fas ${iconClass} mr-1"></i>
                    ${servicio.categoria}
                </div>
                
                <!-- Overlay con botón -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end justify-center pb-6">
                    <button class='ver-mas bg-yellow-400 hover:bg-yellow-500 text-gray-900 px-6 py-3 rounded-full font-bold text-sm shadow-xl transform translate-y-4 group-hover:translate-y-0 transition-all duration-300' data-id='${servicio.id}'>
                        <i class="fas fa-eye mr-2"></i>
                        Ver Detalles
                    </button>
                </div>
            </div>
            
            <!-- Contenido -->
            <div class="p-5 flex-1 flex flex-col">
                <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2 group-hover:text-yellow-600 transition-colors">
                    ${servicio.nombre_servicio}
                </h3>
                
                <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-1">
                    ${servicio.descripcion}
                </p>
                
                <!-- Precio destacado -->
                <div class="text-3xl font-bold text-orange-500 mb-4">
                    ${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : 'Consultar'}
                </div>
                
                <!-- Información adicional -->
                <div class="space-y-2 text-xs text-gray-500 border-t pt-3">
                    ${servicio.direccion ? `
                        <div class="flex items-center">
                            <i class="fas fa-map-marker-alt text-yellow-500 w-4 mr-2"></i>
                            <span class="truncate">${servicio.direccion}</span>
                        </div>
                    ` : ''}
                    ${servicio.horario_atencion ? `
                        <div class="flex items-center">
                            <i class="fas fa-clock text-yellow-500 w-4 mr-2"></i>
                            <span>${servicio.horario_atencion}</span>
                        </div>
                    ` : ''}
                </div>
            </div>
        </div>
    `;
}

// =============================
// Función para cargar servicios
// =============================
async function loadServicios() {
    const grid = document.getElementById('services-grid');
    const count = document.getElementById('results-count');
    if (!grid) return;
    
    grid.innerHTML = `
        <div class="col-span-full flex flex-col items-center justify-center py-16">
            <i class="fas fa-spinner fa-spin text-6xl text-yellow-500 mb-4"></i>
            <p class="text-gray-500 text-lg">Cargando servicios...</p>
        </div>
    `;
    
    try {
        const response = await fetch('/servicios', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await response.json();
        
        allServices = [...exampleServices];
        if (data.success && Array.isArray(data.data)) {
            allServices = allServices.concat(data.data);
        }
        
        filteredServices = [...allServices];
        renderServices(filteredServices);
        
    } catch (e) {
        allServices = [...exampleServices];
        filteredServices = [...allServices];
        renderServices(filteredServices);
    }
}

// =============================
// Función para renderizar servicios
// =============================
function renderServices(services) {
    const grid = document.getElementById('services-grid');
    const count = document.getElementById('results-count');
    
    grid.innerHTML = '';
    
    if (services.length > 0) {
        services.forEach(servicio => {
            grid.innerHTML += renderServiceCard(servicio);
        });
        if (count) count.textContent = services.length;
    } else {
        grid.innerHTML = `
            <div class="col-span-full flex flex-col items-center justify-center py-20">
                <i class="fas fa-search text-8xl text-gray-300 mb-6"></i>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">No se encontraron servicios</h3>
                <p class="text-gray-500 mb-6">Intenta ajustar los filtros de búsqueda</p>
                <button id="reset-filters-btn" class="px-8 py-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white rounded-full font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <i class="fas fa-redo mr-2"></i>
                    Limpiar Filtros
                </button>
            </div>
        `;
        if (count) count.textContent = 0;
    }
}

// =============================
// Funcionalidad de filtros
// =============================
function applyFilters() {
    let filtered = [...allServices];
    
    // Filtro de categoría
    const categoryCheckboxes = document.querySelectorAll('.filter-checkbox[data-category]:checked');
    const selectedCategories = Array.from(categoryCheckboxes).map(cb => cb.dataset.category);
    
    if (selectedCategories.length > 0 && !selectedCategories.includes('todos')) {
        filtered = filtered.filter(s => selectedCategories.includes(s.categoria));
    }
    
    // Filtro de precio
    const minPrice = parseFloat(document.getElementById('minPrice')?.value) || 0;
    const maxPrice = parseFloat(document.getElementById('maxPrice')?.value) || Infinity;
    
    filtered = filtered.filter(s => {
        const price = s.precio_base || 0;
        return price >= minPrice && price <= maxPrice;
    });
    
    // Filtro de calificación (preparado para futuro)
    const ratingCheckboxes = document.querySelectorAll('input[data-rating]:checked');
    if (ratingCheckboxes.length > 0) {
        // Implementar cuando tengas calificaciones
    }
    
    filteredServices = filtered;
    renderServices(filteredServices);
}

// =============================
// Modal mejorado para ver detalles con Tailwind
// =============================
function showServiceModal(servicio) {
    let modal = document.getElementById('modal-ver-detalles');
    if (modal) modal.remove();
    
    modal = document.createElement('div');
    modal.id = 'modal-ver-detalles';
    modal.className = 'fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm animate-fadeIn';
    modal.onclick = (e) => {
        if (e.target === modal) {
            modal.classList.add('animate-fadeOut');
            setTimeout(() => modal.remove(), 300);
        }
    };
    
    let imgSrc = 'https://via.placeholder.com/600x400?text=Sin+Imagen';
    if (servicio.imagen_principal) {
        if (servicio.imagen_principal.startsWith('images/')) {
            imgSrc = '/' + servicio.imagen_principal;
        } else if (servicio.imagen_principal.startsWith('http')) {
            imgSrc = servicio.imagen_principal;
        } else {
            imgSrc = '/storage/' + servicio.imagen_principal;
        }
    }
    
    const categoryIcons = {
        'comida': 'fa-utensils',
        'autolavado': 'fa-car',
        'carpinteria': 'fa-hammer',
        'drogeria': 'fa-pills',
        'belleza': 'fa-cut',
        'reparaciones': 'fa-wrench'
    };
    
    const categoryColors = {
        'comida': 'bg-orange-500',
        'autolavado': 'bg-blue-500',
        'carpinteria': 'bg-amber-600',
        'drogeria': 'bg-green-500',
        'belleza': 'bg-pink-500',
        'reparaciones': 'bg-gray-700'
    };
    
    const iconClass = categoryIcons[servicio.categoria] || 'fa-store';
    const colorClass = categoryColors[servicio.categoria] || 'bg-gray-500';
    
    let contactButtons = '';
    if (servicio.telefono) {
        const phoneDigits = servicio.telefono.replace(/\D/g, '');
        contactButtons = `
            <div class="flex flex-wrap gap-4 mt-8">
                <a href="tel:${phoneDigits}" class="flex-1 min-w-[200px] flex items-center justify-center gap-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all" target="_blank">
                    <i class="fas fa-phone-alt text-xl"></i>
                    Llamar Ahora
                </a>
                <a href="https://wa.me/${phoneDigits}" class="flex-1 min-w-[200px] flex items-center justify-center gap-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all" target="_blank">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    WhatsApp
                </a>
            </div>
        `;
    }
    
    modal.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto animate-slideUp" onclick="event.stopPropagation()">
            <!-- Botón cerrar -->
            <button class="absolute top-4 right-4 z-10 w-12 h-12 bg-white hover:bg-red-500 text-gray-800 hover:text-white rounded-full shadow-lg flex items-center justify-center transition-all hover:rotate-90" onclick="document.getElementById('modal-ver-detalles').remove()">
                <i class="fas fa-times text-xl"></i>
            </button>
            
            <!-- Imagen con badge -->
            <div class="relative h-80 overflow-hidden rounded-t-3xl">
                <img src="${imgSrc}" alt="${servicio.nombre_servicio}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                
                <!-- Badge de categoría -->
                <div class="absolute bottom-6 left-6 ${colorClass} text-white px-5 py-2 rounded-full font-bold shadow-xl">
                    <i class="fas ${iconClass} mr-2"></i>
                    ${servicio.categoria}
                </div>
            </div>
            
            <!-- Contenido -->
            <div class="p-8">
                <!-- Título y precio -->
                <h2 class="text-4xl font-bold text-gray-900 mb-2">
                    ${servicio.nombre_servicio}
                </h2>
                
                <div class="text-5xl font-bold bg-gradient-to-r from-orange-500 to-red-500 bg-clip-text text-transparent mb-6">
                    ${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : 'Consultar precio'}
                </div>
                
                <!-- Descripción -->
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg mb-6">
                    <p class="text-gray-700 leading-relaxed text-lg">
                        ${servicio.descripcion || 'Sin descripción disponible'}
                    </p>
                </div>
                
                <!-- Grid de información -->
                <div class="grid gap-4 mb-6">
                    ${servicio.direccion ? `
                        <div class="flex items-start gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                            <div class="w-12 h-12 bg-yellow-400 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Dirección</div>
                                <div class="text-gray-900 font-medium">${servicio.direccion}</div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${servicio.telefono ? `
                        <div class="flex items-start gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                            <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-phone text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Teléfono</div>
                                <div class="text-gray-900 font-medium">${servicio.telefono}</div>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${servicio.horario_atencion ? `
                        <div class="flex items-start gap-4 p-4 bg-gray-50 hover:bg-gray-100 rounded-xl transition-colors">
                            <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-clock text-white text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Horario</div>
                                <div class="text-gray-900 font-medium">${servicio.horario_atencion}</div>
                            </div>
                        </div>
                    ` : ''}
                </div>
                
                <!-- Botones de contacto -->
                ${contactButtons}
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
}

// =============================
// Inicialización
// =============================
document.addEventListener('DOMContentLoaded', () => {
    if (window.currentEntrepreneurId === undefined && window.entrepreneurIdFromBlade) {
        window.currentEntrepreneurId = window.entrepreneurIdFromBlade;
    }
    
    loadServicios();
    
    // Event listener para filtros de categoría
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            
            if (this.checked) {
                label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
                label.classList.add('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
            } else {
                label.classList.remove('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
                label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
            }
            
            // Si se marca "Todas las categorías", desmarcar las demás
            if (this.dataset.category === 'todos' && this.checked) {
                document.querySelectorAll('.filter-checkbox[data-category]:not([data-category="todos"])').forEach(cb => {
                    cb.checked = false;
                    const lbl = cb.nextElementSibling;
                    lbl.classList.remove('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
                    lbl.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
                });
            } else if (this.dataset.category !== 'todos' && this.checked) {
                const todosCheckbox = document.querySelector('.filter-checkbox[data-category="todos"]');
                if (todosCheckbox && todosCheckbox.checked) {
                    todosCheckbox.checked = false;
                    const lbl = todosCheckbox.nextElementSibling;
                    lbl.classList.remove('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
                    lbl.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
                }
            }
            
            applyFilters();
        });
    });
    
    // Event listeners para filtros de precio
    const minPriceInput = document.getElementById('minPrice');
    const maxPriceInput = document.getElementById('maxPrice');
    
    if (minPriceInput) {
        minPriceInput.addEventListener('input', applyFilters);
    }
    if (maxPriceInput) {
        maxPriceInput.addEventListener('input', applyFilters);
    }
    
    // Event listeners para filtros de calificación
    document.querySelectorAll('input[data-rating]').forEach(checkbox => {
        checkbox.addEventListener('change', applyFilters);
    });
    
    // Limpiar filtros
    const clearFiltersBtn = document.getElementById('clear-filters');
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox, input[data-rating]').forEach(cb => {
                cb.checked = false;
                if (cb.nextElementSibling && cb.nextElementSibling.classList.contains('filter-label')) {
                    const label = cb.nextElementSibling;
                    label.classList.remove('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
                    label.classList.add('bg-gray-100', 'text-gray-700', 'border-gray-200');
                }
            });
            
            const todosCheckbox = document.querySelector('.filter-checkbox[data-category="todos"]');
            if (todosCheckbox) {
                todosCheckbox.checked = true;
                const label = todosCheckbox.nextElementSibling;
                label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
                label.classList.add('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
            }
            
            if (minPriceInput) minPriceInput.value = '';
            if (maxPriceInput) maxPriceInput.value = '';
            
            applyFilters();
        });
    }
    
    // Delegar evento para el botón 'Ver detalle'
    document.body.addEventListener('click', async function(e) {
        if (e.target.closest('.ver-mas')) {
            const btn = e.target.closest('.ver-mas');
            const id = btn.dataset.id;
            
            let servicio = allServices.find(s => s.id == id || s.id === id);
            
            if (servicio) {
                showServiceModal(servicio);
            } else {
                try {
                    const resp = await fetch(`/servicios/${id}`);
                    const data = await resp.json();
                    if (data.success) {
                        showServiceModal(data.data);
                    }
                } catch (err) {
                    console.error('Error al cargar el servicio:', err);
                }
            }
        }
        
        if (e.target.id === 'reset-filters-btn') {
            document.getElementById('clear-filters')?.click();
        }
    });
    
    // Marcar "Todas las categorías" por defecto
    const todosCheckbox = document.querySelector('.filter-checkbox[data-category="todos"]');
    if (todosCheckbox && !todosCheckbox.checked) {
        todosCheckbox.checked = true;
        const label = todosCheckbox.nextElementSibling;
        if (label) {
            label.classList.remove('bg-gray-100', 'text-gray-700', 'border-gray-200');
            label.classList.add('bg-yellow-400', 'text-gray-900', 'border-yellow-400');
        }
    }
});