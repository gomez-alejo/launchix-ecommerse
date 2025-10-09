
// Variables globales para servicios
let services = [];
let filteredServices = [];
let currentPage = 1;
const servicesPerPage = 12;

// ====================================
// SERVICIOS ESTÁTICOS DE EJEMPLO
// ====================================
function getStaticServices() {
    return [
        {
            id: 99001,
            nombre_servicio: "Pizza Express",
            categoria: { name: "Comida", slug: "comida" },
            precio_base: 25000,
            descripcion: "Deliciosas pizzas artesanales con ingredientes frescos. Servicio a domicilio disponible las 24 horas.",
            imagen_principal: "images/ejemplo-servicios/pizza.jpg",
            direccion: "Calle 123, Ciudad Centro",
            telefono: "1234567890",
            horario_atencion: "Lun-Dom 10:00-22:00",
            rating: 4.5,
            reviews: 87,
            isNew: true,
            disponible: true,
            created_at: new Date().toISOString()
        },
        {
            id: 99002,
            nombre_servicio: "AutoLavado Premium",
            categoria: { name: "Autolavado", slug: "autolavado" },
            precio_base: 15000,
            descripcion: "Servicio completo de lavado y encerado. Cuidamos tu vehículo como si fuera nuestro con productos premium.",
            imagen_principal: "images/ejemplo-servicios/autolavado.jpg",
            direccion: "Av. Principal 456, Ciudad",
            telefono: "0987654321",
            horario_atencion: "Lun-Sab 8:00-18:00",
            rating: 4.8,
            reviews: 134,
            isNew: false,
            disponible: true,
            created_at: "2024-01-15T10:00:00Z"
        },
        {
            id: 99003,
            nombre_servicio: "Carpintería Creativa",
            categoria: { name: "Carpintería", slug: "carpinteria" },
            precio_base: 30000,
            descripcion: "Muebles a medida, restauración y trabajos en madera de alta calidad. Diseños únicos y personalizados.",
            imagen_principal: "images/ejemplo-servicios/carpinteria.jpg",
            direccion: "Cra 10 #45-67, Ciudad",
            telefono: "3216549870",
            horario_atencion: "Lun-Vie 9:00-18:00",
            rating: 4.7,
            reviews: 92,
            isNew: false,
            disponible: true,
            created_at: "2024-02-10T10:00:00Z"
        },
        {
            id: 99004,
            nombre_servicio: "Belleza Total Spa",
            categoria: { name: "Belleza", slug: "belleza" },
            precio_base: 40000,
            descripcion: "Spa, peluquería y tratamientos de belleza para toda la familia. Relájate y renuévate con nosotros.",
            imagen_principal: "images/ejemplo-servicios/belleza.jpg",
            direccion: "Calle 50 #12-34, Ciudad",
            telefono: "3123456789",
            horario_atencion: "Mar-Sab 10:00-20:00",
            rating: 4.6,
            reviews: 156,
            isNew: true,
            disponible: true,
            created_at: new Date().toISOString()
        }
    ];
}

// ====================================
// CREAR TARJETA DE SERVICIO (ESTILO PRODUCTOS)
// ====================================
function createServiceCard(servicio) {
    const newBadge = servicio.isNew ?
        `<div class="absolute top-2 right-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs font-bold">
            NUEVO
        </div>` : '';

    const disponibilidadStatus = servicio.disponible ?
        `<button class="view-service-btn btn-primary w-full py-2 rounded-lg font-semibold transition-all duration-300" data-service-id="${servicio.id}">
            <i class="fas fa-eye"></i> Ver más
        </button>` :
        `<button class="bg-gray-400 text-white w-full py-2 rounded-lg font-semibold cursor-not-allowed" disabled>
            <i class="fas fa-times"></i> No Disponible
        </button>`;

    const stars = generateServiceStarRating(servicio.rating || 4.5);

    let imgSrc = 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio';
    if (servicio.imagen_principal) {
        if (servicio.imagen_principal.startsWith('images/')) {
            imgSrc = '/' + servicio.imagen_principal;
        } else if (servicio.imagen_principal.startsWith('http')) {
            imgSrc = servicio.imagen_principal;
        } else {
            imgSrc = '/storage/' + servicio.imagen_principal;
        }
    }

    return `
        <div class="product-card bg-white rounded-lg shadow-lg overflow-hidden fade-in">
            <div class="relative">
                <img src="${imgSrc}" alt="${servicio.nombre_servicio}" class="w-full h-64 object-cover"
                     onerror="this.src='https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio'">
                ${newBadge}
                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                    <button class="bg-white text-gray-800 px-4 py-2 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-300 view-service-details-btn"
                    data-service-id="${servicio.id}">
                        <i class="fas fa-eye"></i> Ver Detalles
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="category-tag inline-block mb-2">${getServiceCategoryName(servicio.categoria?.slug || servicio.categoria)}</div>
                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">${servicio.nombre_servicio}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${servicio.descripcion}</p>
                <div class="flex items-center mb-3">
                    <div class="star-rating mr-2">${stars}</div>
                    <span class="text-sm text-gray-600">(${servicio.reviews || 0} reseñas)</span>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold text-red-600">${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : 'Consultar'}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="text-gray-400 hover:text-red-600 transition-colors p-2"
                                onclick="toggleServiceWishlist(${servicio.id})">
                            <i class="fas fa-heart"></i>
                        </button>
                        <button class="text-gray-400 hover:text-blue-600 transition-colors p-2"
                                onclick="shareService(${servicio.id})">
                            <i class="fas fa-share-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-2">
                    <span class="text-xs text-gray-500">${servicio.direccion || 'Ubicación por confirmar'}</span>
                </div>
                <div class="mb-2">
                    <span class="text-xs text-gray-500">${servicio.horario_atencion || 'Horarios flexibles'}</span>
                </div>

                ${disponibilidadStatus}
            </div>
        </div>
    `;
}

function generateServiceStarRating(rating) {
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

function getServiceCategoryName(category) {
    const categoryNames = {
        'comida': 'Comida',
        'autolavado': 'Autolavado',
        'carpinteria': 'Carpintería',
        'belleza': 'Belleza y Spa',
        'tecnologia': 'Tecnología',
        'hogar': 'Hogar y Jardinería',
        'educacion': 'Educación',
        'salud': 'Salud y Bienestar',
        'otros': 'Otros'
    };
    return categoryNames[category] || category || 'Sin categoría';
}

// ====================================
// CARGAR Y MOSTRAR SERVICIOS
// ====================================
async function loadServices() {
    try {
        // Mostrar loading
        showServicesLoading();

        // Obtener servicios estáticos
        const staticServices = getStaticServices();

        // Intentar obtener servicios del backend
        let backendServices = [];
        try {
            const response = await fetch('/servicios', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (data.success && Array.isArray(data.data)) {
                backendServices = data.data.map(servicio => ({
                    ...servicio,
                    categoria: servicio.categoria ? { name: servicio.categoria, slug: servicio.categoria } : { name: 'Otros', slug: 'otros' },
                    rating: servicio.rating || 4.0 + Math.random(),
                    reviews: servicio.reviews || Math.floor(Math.random() * 100) + 10,
                    isNew: servicio.created_at ? new Date(servicio.created_at) > new Date(Date.now() - 30*24*60*60*1000) : false,
                    disponible: servicio.disponible !== false
                }));
            }
        } catch (error) {
            console.log('No se pudieron cargar servicios del backend, usando solo estáticos');
        }

        // Combinar servicios
        services = [...staticServices, ...backendServices];
        filteredServices = [...services];

        displayServices();
        updateServicesCount();

    } catch (error) {
        console.error('Error al cargar servicios:', error);
        showServicesError('Error al cargar los servicios');
    }
}

function displayServices() {
    const grid = document.getElementById('services-grid');
    if (!grid) return;

    // Calcular paginación
    const startIndex = (currentPage - 1) * servicesPerPage;
    const endIndex = startIndex + servicesPerPage;
    const servicesToShow = filteredServices.slice(startIndex, endIndex);

    // Mostrar mensaje si no hay servicios
    if (filteredServices.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-medium text-gray-600 mb-2">No se encontraron servicios</h3>
                <p class="text-gray-400">Intenta ajustar los filtros o busca con otros términos</p>
            </div>
        `;
        return;
    }

    // Mostrar servicios
    grid.innerHTML = servicesToShow.map(service => createServiceCard(service)).join('');

    // Actualizar paginación
    updateServicesPagination();

    // Agregar clase de animación
    grid.classList.remove('fade-in');
    setTimeout(() => {
        grid.classList.add('fade-in');
        // Mostrar controles de administración después de renderizar
        showAdminControls();
    }, 100);
}

function showServicesLoading() {
    const grid = document.getElementById('services-grid');
    if (grid) {
        grid.innerHTML = `
            <div class="col-span-full flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="loading-spinner mx-auto mb-4"></div>
                    <p class="text-gray-600">Cargando servicios...</p>
                </div>
            </div>
        `;
    }
}

function showServicesError(message) {
    const grid = document.getElementById('services-grid');
    if (grid) {
        grid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                <h3 class="text-xl font-medium text-red-600 mb-2">Error</h3>
                <p class="text-gray-600">${message}</p>
                <button onclick="loadServices()" class="mt-4 btn-primary px-6 py-2 rounded-lg">
                    <i class="fas fa-redo"></i> Reintentar
                </button>
            </div>
        `;
    }
}

function updateServicesCount() {
    const count = document.getElementById('results-count');
    if (count) {
        count.textContent = filteredServices.length;
    }
}

function updateServicesPagination() {
    const totalPages = Math.ceil(filteredServices.length / servicesPerPage);
    const paginationContainer = document.getElementById('services-pagination');

    if (!paginationContainer || totalPages <= 1) {
        if (paginationContainer) paginationContainer.innerHTML = '';
        return;
    }

    let paginationHTML = '';

    // Botón anterior
    if (currentPage > 1) {
        paginationHTML += `
            <button onclick="changeServicesPage(${currentPage - 1})" class="pagination-btn">
                <i class="fas fa-chevron-left"></i>
            </button>
        `;
    }

    // Números de página
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            paginationHTML += `<button class="pagination-btn active">${i}</button>`;
        } else {
            paginationHTML += `<button onclick="changeServicesPage(${i})" class="pagination-btn">${i}</button>`;
        }
    }

    // Botón siguiente
    if (currentPage < totalPages) {
        paginationHTML += `
            <button onclick="changeServicesPage(${currentPage + 1})" class="pagination-btn">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    }

    paginationContainer.innerHTML = paginationHTML;
}

function changeServicesPage(page) {
    currentPage = page;
    displayServices();

    // Scroll hacia arriba
    document.getElementById('services-grid').scrollIntoView({ behavior: 'smooth' });
}

// ====================================
// ====================================
// FUNCIONES AUXILIARES PARA EL MODAL
// ====================================
function generateEntrepreneurSection(service) {
    if (!service.entrepreneur) {
        return ''; // No mostrar nada si no hay información del emprendedor
    }

    const entrepreneur = service.entrepreneur;
    const profileUrl = `/entrepreneur/${entrepreneur.id}/profile`;

    return `
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 mb-6 border border-blue-100">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <img src="${entrepreneur.profile_photo_url}"
                         alt="${entrepreneur.full_name}"
                         class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-md hover:scale-105 transition-transform duration-300 cursor-pointer"
                         onclick="window.open('${profileUrl}', '_blank')"
                         onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(entrepreneur.full_name)}&size=64&background=3B82F6&color=fff&bold=true'">
                    <div class="absolute -bottom-1 -right-1 bg-green-500 w-5 h-5 rounded-full border-2 border-white"></div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center space-x-2">
                        <h4 class="text-lg font-semibold text-gray-800 cursor-pointer hover:text-blue-600 transition-colors duration-300"
                            onclick="window.open('${profileUrl}', '_blank')">
                            ${entrepreneur.full_name}
                        </h4>
                        <i class="fas fa-external-link-alt text-gray-400 text-sm"></i>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-store mr-1"></i>Emprendedor verificado
                    </p>
                    ${entrepreneur.city ? `
                        <p class="text-xs text-gray-500">
                            <i class="fas fa-map-marker-alt mr-1"></i>${entrepreneur.city}
                        </p>
                    ` : ''}
                    ${entrepreneur.profile_description ? `
                        <p class="text-xs text-gray-600 mt-2 line-clamp-2">
                            ${entrepreneur.profile_description}
                        </p>
                    ` : ''}
                </div>
                <div class="text-right">
                    <button onclick="window.open('${profileUrl}', '_blank')"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform hover:scale-105 shadow-md">
                        <i class="fas fa-user mr-2"></i>Ver Perfil
                    </button>
                </div>
            </div>
        </div>
    `;
}

// ====================================
// MODAL DE DETALLES DEL SERVICIO
// ====================================
async function viewServiceDetails(serviceId) {
    // Primero intentar obtener el servicio actualizado de la API
    let service = services.find(s => s.id === serviceId);

    // Para servicios reales (no estáticos), obtener datos actualizados del servidor
    if (service && serviceId < 99000) {
        try {
            const response = await fetch(`/servicios/details/${serviceId}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (response.ok) {
                const data = await response.json();
                if (data.success) {
                    service = data.data;
                }
            }
        } catch (error) {
            console.log('Error al obtener detalles actualizados:', error);
        }
    }

    if (!service) return;

    let imgSrc = 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio';
    if (service.imagen_principal) {
        if (service.imagen_principal.startsWith('images/')) {
            imgSrc = '/' + service.imagen_principal;
        } else if (service.imagen_principal.startsWith('http')) {
            imgSrc = service.imagen_principal;
        } else {
            imgSrc = '/storage/' + service.imagen_principal;
        }
    }

    // Generar enlaces de contacto si hay teléfono
    let contactButtons = '';
    if (service.telefono) {
        const phoneDigits = service.telefono.replace(/\D/g, '');
        contactButtons = `
            <div class="flex space-x-3 w-full">
                <a href="tel:${phoneDigits}" class="btn-primary flex-1 py-3 rounded-lg font-semibold text-center transition-all duration-300" target="_blank">
                    <i class="fas fa-phone mr-2"></i> Llamar
                </a>
                <a href="https://wa.me/${phoneDigits}?text=Hola,%20estoy%20interesado%20en%20el%20servicio%20${encodeURIComponent(service.nombre_servicio)}"
                   class="bg-green-500 hover:bg-green-600 text-white flex-1 py-3 rounded-lg font-semibold text-center transition-all duration-300" target="_blank">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                </a>
            </div>
        `;
    } else {
        contactButtons = `
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-info-circle mr-2"></i>
                No hay información de contacto disponible
            </div>
        `;
    }

    // Remover modal existente si existe
    const existingModal = document.getElementById('serviceModal');
    if (existingModal) {
        existingModal.remove();
    }

    const modalHTML = `
        <div id="serviceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">${service.nombre_servicio}</h2>
                        <button class="modal-close-btn text-gray-600 hover:text-red-600 text-xl" onclick="closeServiceModal()" type="button">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    ${generateEntrepreneurSection(service)}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <img src="${imgSrc}" alt="${service.nombre_servicio}" class="w-full h-64 object-cover rounded-lg mb-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="font-semibold text-gray-700 mb-2">Información de Contacto</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <i class="fas fa-map-marker-alt w-4 mr-2 text-red-500"></i>
                                        <span>${service.direccion || 'Ubicación por confirmar'}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-phone w-4 mr-2 text-blue-500"></i>
                                        <span>${service.telefono || 'No disponible'}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-clock w-4 mr-2 text-green-500"></i>
                                        <span>${service.horario_atencion || 'Horarios flexibles'}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="category-tag inline-block mb-2">${getServiceCategoryName(service.categoria?.slug || service.categoria)}</div>
                            <div class="flex items-center mb-3">
                                ${generateServiceStarRating(service.rating || 4.5)}
                                <span class="ml-2 text-sm text-gray-600">(${service.reviews || 0} reseñas)</span>
                            </div>
                            <div class="flex items-center space-x-3 mb-4">
                                <span class="text-3xl font-bold text-red-600">${service.precio_base ? '$' + Number(service.precio_base).toLocaleString() : 'Consultar precio'}</span>
                            </div>
                            <p class="text-gray-700 mb-6">${service.descripcion}</p>
                            <div class="bg-blue-50 p-4 rounded-lg mb-6">
                                <h4 class="font-semibold text-blue-700 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    ¿Cómo contactar?
                                </h4>
                                <p class="text-sm text-blue-600">
                                    Puedes llamar directamente o escribir por WhatsApp para obtener más información sobre este servicio.
                                </p>
                            </div>
                            ${contactButtons}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Abrir modal usando la función que funciona
    openServiceModal();

    // Configurar event listeners (igual que en entrepreneur)
    setupServiceModalCloseEvents();
}

// Funciones de modal mejoradas con animaciones (igual que en entrepreneur)
window.closeServiceModal = function() {
    const modal = document.getElementById('serviceModal');
    if (modal) {
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.opacity = '';
            modal.style.transform = '';
        }, 200);
    }
}

function openServiceModal() {
    const modal = document.getElementById('serviceModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.opacity = '0';
        modal.style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.style.transform = 'scale(1)';
        }, 10);
    }
}

// Configurar eventos de cierre de modal (igual que en entrepreneur)
function setupServiceModalCloseEvents() {
    const modal = document.getElementById('serviceModal');
    if (!modal) return;

    // Click fuera del modal para cerrar
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeServiceModal();
        }
    });

    // Escape key para cerrar
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('serviceModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeServiceModal();
            }
        }
    });

    // Configurar botón de cerrar
    const closeBtn = modal.querySelector('.modal-close-btn');
    if (closeBtn) {
        closeBtn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeServiceModal();
        };
    }
}

// Función alternativa para asegurar cierre
function forceCloseServiceModal() {
    const modal = document.getElementById('serviceModal');
    if (modal) {
        modal.remove();
    }
}

// Funciones CRUD que conectan con ServicePublishing.js
window.editService = function(serviceId) {
    if (window.ServicesManager) {
        window.ServicesManager.editarServicio(serviceId);
    } else {
        console.error('ServicesManager no está disponible');
    }
}

window.deleteService = function(serviceId) {
    if (window.ServicesManager) {
        window.ServicesManager.eliminarServicio(serviceId);
    } else {
        console.error('ServicesManager no está disponible');
    }
}

window.addNewService = function() {
    showCreateServiceModal();
}

// Modal dinámico para crear servicios
function showCreateServiceModal() {
    const modalHTML = `
        <div id="createServiceModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 transition-all duration-200" style="opacity: 0;">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-screen overflow-y-auto transform transition-all duration-200" style="transform: scale(0.95);">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Agregar Nuevo Servicio</h2>
                        <button onclick="closeCreateServiceModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <form id="createServiceForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio *</label>
                                <input type="text" name="nombre_servicio" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                                <select name="categoria" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="limpieza">Servicios de Limpieza</option>
                                    <option value="reparaciones">Reparaciones del Hogar</option>
                                    <option value="jardineria">Jardinería y Paisajismo</option>
                                    <option value="transporte">Servicios de Transporte</option>
                                    <option value="tecnologia">Soporte Técnico</option>
                                    <option value="educacion">Educación y Tutorías</option>
                                    <option value="salud">Salud y Bienestar</option>
                                    <option value="eventos">Organización de Eventos</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                            <textarea name="descripcion" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Describe tu servicio..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base</label>
                                <input type="number" name="precio_base" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="tel" name="telefono" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+1234567890">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Horario de Atención</label>
                                <input type="text" name="horario_atencion" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Lunes a Viernes 9-17h">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                            <input type="text" name="direccion" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dirección del servicio...">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Imagen Principal</label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer"
                                 id="imageDropzone" onclick="document.getElementById('imagen_principal').click()">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-600 mb-1">Arrastra una imagen o haz clic para seleccionar</p>
                                <p class="text-xs text-gray-500">Máximo 2MB - JPG, PNG</p>
                                <input type="file" name="imagen_principal" id="imagen_principal" accept="image/jpeg,image/png" class="hidden">
                            </div>
                            <div id="imagePreview" class="mt-3"></div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeCreateServiceModal()" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-primary px-6 py-2 rounded-lg font-semibold transition-all duration-300">
                                <i class="fas fa-save mr-2"></i> Guardar Servicio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHTML);

    // Animaciones
    const modal = document.getElementById('createServiceModal');
    const modalContent = modal.querySelector('.bg-white');

    setTimeout(() => {
        modal.style.opacity = '1';
        modalContent.style.transform = 'scale(1)';
    }, 10);

    // Event listeners
    modal.addEventListener('click', function(e) {
        if (e.target === this) closeCreateServiceModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCreateServiceModal();
    });

    // Form submission
    document.getElementById('createServiceForm').addEventListener('submit', handleCreateServiceSubmit);

    // Setup image upload functionality
    setupImageUploadForModal();
}

window.closeCreateServiceModal = function() {
    const modal = document.getElementById('createServiceModal');
    if (modal) {
        modal.style.opacity = '0';
        modal.querySelector('.bg-white').style.transform = 'scale(0.95)';
        setTimeout(() => {
            modal.remove();
        }, 200);
    }
}

async function handleCreateServiceSubmit(e) {
    e.preventDefault();

    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;

    // Loading state
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';

    try {
        const formData = new FormData(form);

        // Agregar CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            formData.append('_token', csrfToken.getAttribute('content'));
        }

        const response = await fetch('/servicios', {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (response.ok && result.success) {
            // Cerrar modal
            closeCreateServiceModal();

            // Mostrar mensaje de éxito
            showSuccessMessage('Servicio creado exitosamente');

            // Recargar servicios si es posible
            if (typeof loadServices === 'function') {
                loadServices();
            } else {
                // Recargar página como fallback
                setTimeout(() => location.reload(), 1000);
            }
        } else {
            showErrorMessage(result.message || 'Error al crear el servicio');
        }
    } catch (error) {
        console.error('Error:', error);
        showErrorMessage('Error de conexión al crear el servicio');
    } finally {
        submitButton.disabled = false;
        submitButton.innerHTML = originalText;
    }
}

// Funciones de utilidad para mensajes
function showSuccessMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function showErrorMessage(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${message}`;
    document.body.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-full'), 100);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Función para configurar la subida de imágenes en el modal
function setupImageUploadForModal() {
    const dropzone = document.getElementById('imageDropzone');
    const input = document.getElementById('imagen_principal');
    const preview = document.getElementById('imagePreview');

    if (!dropzone || !input || !preview) return;

    // Eventos de drag & drop
    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.classList.add('border-blue-500', 'bg-blue-50');
    });

    dropzone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('border-blue-500', 'bg-blue-50');
        const files = e.dataTransfer.files;
        handleImageUpload(files[0], preview, input);
    });

    // Evento de cambio de archivo
    input.addEventListener('change', (e) => {
        handleImageUpload(e.target.files[0], preview, input);
    });
}

function handleImageUpload(file, preview, input) {
    if (!file) return;

    // Validaciones
    const maxSize = 2 * 1024 * 1024; // 2MB
    const allowedTypes = ['image/jpeg', 'image/png'];

    if (!allowedTypes.includes(file.type)) {
        showErrorMessage('Solo se permiten imágenes JPG o PNG');
        input.value = '';
        return;
    }

    if (file.size > maxSize) {
        showErrorMessage('La imagen debe pesar máximo 2MB');
        input.value = '';
        return;
    }

    // Crear preview
    const reader = new FileReader();
    reader.onload = (e) => {
        preview.innerHTML = `
            <div class="relative inline-block">
                <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border">
                <button type="button" onclick="removeImagePreview()"
                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    };
    reader.readAsDataURL(file);
}

window.removeImagePreview = function() {
    const preview = document.getElementById('imagePreview');
    const input = document.getElementById('imagen_principal');
    if (preview) preview.innerHTML = '';
    if (input) input.value = '';
}

// ====================================
// FUNCIONES AUXILIARES
// ====================================
function toggleServiceWishlist(serviceId) {
    console.log('Agregado a favoritos:', serviceId);
    // Implementar lógica de favoritos
}

function shareService(serviceId) {
    const service = services.find(s => s.id === serviceId);
    if (service && navigator.share) {
        navigator.share({
            title: service.nombre_servicio,
            text: service.descripcion,
            url: window.location.href
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            alert('Enlace copiado al portapapeles');
        });
    }
}

// ====================================
// FILTROS Y BÚSQUEDA
// ====================================
function filterServicesByCategory(category) {
    if (category === 'all' || category === 'todos') {
        filteredServices = [...services];
    } else {
        filteredServices = services.filter(service => {
            const serviceCategory = service.categoria?.slug || service.categoria;
            return serviceCategory === category;
        });
    }

    currentPage = 1;
    displayServices();
    updateServicesCount();
}

function searchServices(query) {
    const searchTerm = query.toLowerCase().trim();

    if (!searchTerm) {
        filteredServices = [...services];
    } else {
        filteredServices = services.filter(service => {
            return (
                service.nombre_servicio.toLowerCase().includes(searchTerm) ||
                service.descripcion.toLowerCase().includes(searchTerm) ||
                (service.categoria?.name || service.categoria || '').toLowerCase().includes(searchTerm) ||
                (service.direccion || '').toLowerCase().includes(searchTerm)
            );
        });
    }

    currentPage = 1;
    displayServices();
    updateServicesCount();
}

// ====================================
// INICIALIZACIÓN Y EVENTOS
// ====================================
document.addEventListener('DOMContentLoaded', () => {
    // Cargar servicios al iniciar
    loadServices();

    // Event delegation para botones de ver detalles
    document.addEventListener('click', (e) => {
        if (e.target.closest('.view-service-details-btn') || e.target.closest('.view-service-btn')) {
            const button = e.target.closest('.view-service-details-btn') || e.target.closest('.view-service-btn');
            const serviceId = parseInt(button.dataset.serviceId);
            viewServiceDetails(serviceId);
        }
    });

    // Configurar búsqueda si existe el input
    const searchInput = document.getElementById('services-search');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            searchServices(e.target.value);
        });
    }

    // Configurar filtros si existen
    const filterButtons = document.querySelectorAll('.service-filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;
            filterServicesByCategory(category);

            // Actualizar estado activo
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });
});

// ====================================
// CERRAR MODALES AL HACER CLIC FUERA
// ====================================
document.addEventListener('click', (e) => {
    // Cerrar modal de categorías si existe
    const categoriesModal = document.getElementById('categoriesModal');
    const categoriesButton = document.getElementById('categoriesButton');

    if (categoriesModal && categoriesButton && !categoriesButton.contains(e.target) && !categoriesModal.contains(e.target)) {
        categoriesModal.classList.add('hidden');
    }
});

// ====================================
// FUNCIONALIDADES ADICIONALES
// ====================================

// Filtros de precio
function filterByPriceRange() {
    const minPrice = parseInt(document.getElementById('minPrice').value) || 0;
    const maxPrice = parseInt(document.getElementById('maxPrice').value) || Infinity;

    filteredServices = services.filter(service => {
        const price = service.precio_base || 0;
        return price >= minPrice && price <= maxPrice;
    });

    currentPage = 1;
    displayServices();
    updateServicesCount();
}

// Filtros de calificación
function filterByRating(minRating) {
    filteredServices = services.filter(service => {
        const rating = service.rating || 0;
        return rating >= minRating;
    });

    currentPage = 1;
    displayServices();
    updateServicesCount();
}

// Ordenamiento
function sortServices(criteria) {
    switch (criteria) {
        case 'newest':
            filteredServices.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
            break;
        case 'rating':
            filteredServices.sort((a, b) => (b.rating || 0) - (a.rating || 0));
            break;
        case 'price-low':
            filteredServices.sort((a, b) => (a.precio_base || 0) - (b.precio_base || 0));
            break;
        case 'price-high':
            filteredServices.sort((a, b) => (b.precio_base || 0) - (a.precio_base || 0));
            break;
        case 'name':
            filteredServices.sort((a, b) => a.nombre_servicio.localeCompare(b.nombre_servicio));
            break;
    }

    currentPage = 1;
    displayServices();
}

// Limpiar filtros
function clearAllFilters() {
    // Limpiar filtros de categoria
    document.querySelectorAll('.service-filter-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelector('[data-category="all"]').classList.add('active');

    // Limpiar precio
    document.getElementById('minPrice').value = '';
    document.getElementById('maxPrice').value = '';

    // Limpiar calificación
    document.querySelectorAll('input[name="rating"]').forEach(input => input.checked = false);

    // Limpiar búsqueda
    const searchInput = document.getElementById('services-search');
    if (searchInput) searchInput.value = '';

    // Restablecer ordenamiento
    document.getElementById('sortServices').value = 'newest';

    // Restablecer servicios
    filteredServices = [...services];
    currentPage = 1;
    displayServices();
    updateServicesCount();
}

// Configurar sidebar móvil
function setupMobileSidebar() {
    const mobileToggle = document.getElementById('mobileFilterToggle');
    const sidebar = document.getElementById('filterSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');

    if (mobileToggle && sidebar) {
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
        });
    }

    if (closeSidebar && sidebar) {
        closeSidebar.addEventListener('click', () => {
            sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }
}

// Configurar eventos adicionales
function setupAdditionalEvents() {
    // Filtro de precio
    const applyPriceBtn = document.getElementById('applyPriceFilter');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', filterByPriceRange);
    }

    // Filtros de calificación
    document.querySelectorAll('input[name="rating"]').forEach(input => {
        input.addEventListener('change', (e) => {
            if (e.target.checked) {
                filterByRating(parseInt(e.target.value));
            }
        });
    });

    // Ordenamiento
    const sortSelect = document.getElementById('sortServices');
    if (sortSelect) {
        sortSelect.addEventListener('change', (e) => {
            sortServices(e.target.value);
        });
    }

    // Limpiar filtros
    const clearBtn = document.getElementById('clearServiceFilters');
    if (clearBtn) {
        clearBtn.addEventListener('click', clearAllFilters);
    }

    // Sidebar móvil
    setupMobileSidebar();
}

// Actualizar inicialización
document.addEventListener('DOMContentLoaded', () => {
    // Cargar servicios al iniciar
    loadServices();

    // Event delegation para botones de ver detalles
    document.addEventListener('click', (e) => {
        if (e.target.closest('.view-service-details-btn') || e.target.closest('.view-service-btn')) {
            const button = e.target.closest('.view-service-details-btn') || e.target.closest('.view-service-btn');
            const serviceId = parseInt(button.dataset.serviceId);
            viewServiceDetails(serviceId);
        }
    });

    // Configurar búsqueda si existe el input
    const searchInput = document.getElementById('services-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchServices(e.target.value);
            }, 300);
        });
    }

    // Configurar filtros si existen
    const filterButtons = document.querySelectorAll('.service-filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const category = button.dataset.category;
            filterServicesByCategory(category);

            // Actualizar estado activo
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // Configurar eventos adicionales
    setupAdditionalEvents();
});

// Mantener compatibilidad con código existente
function loadServicios() {
    loadServices();
}

// Función para mostrar controles de administración
function showAdminControls() {
    if (window.isEntrepreneurLoggedIn) {
        const adminControls = document.querySelectorAll('.admin-controls');
        adminControls.forEach(control => {
            control.style.display = 'flex';
        });
    }
}

// Asegurar que las funciones estén disponibles globalmente
window.addEventListener('DOMContentLoaded', function() {
    // Forzar disponibilidad global de funciones de modal
    if (typeof window.closeServiceModal !== 'function') {
        window.closeServiceModal = function() {
            const modal = document.getElementById('serviceModal');
            if (modal) {
                modal.style.opacity = '0';
                const modalContent = modal.querySelector('.bg-white');
                if (modalContent) {
                    modalContent.style.transform = 'scale(0.95)';
                }
                setTimeout(() => {
                    if (modal.parentNode) {
                        modal.remove();
                    }
                }, 200);
            }
        };
    }

    // Función de debugging
    window.debugServiceModal = function() {
        console.log('closeServiceModal function:', typeof window.closeServiceModal);
        console.log('serviceModal element:', document.getElementById('serviceModal'));
    };
});


