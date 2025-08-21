

// =============================
// Servicios de ejemplo (se mantienen)
// =============================
const exampleServices = [
    {
        nombre_servicio: "Pizza Express",
        categoria: "comida",
        precio_base: 25000,
        descripcion: "Deliciosas pizzas artesanales con ingredientes frescos. Servicio a domicilio disponible.",
        imagen_principal: 'pizza-express.jpg',
        direccion: "Calle 123, Ciudad",
        telefono: "1234567890",
        horario_atencion: "Lun-Dom 10:00-22:00"
    },
    {
        nombre_servicio: "AutoLavado Premium",
        categoria: "autolavado",
        precio_base: 15000,
        descripcion: "Servicio completo de lavado y encerado. Cuidamos tu vehículo como si fuera nuestro.",
        imagen_principal: 'https://tse4.mm.bing.net/th/id/OIP.OdVvocpu_mXYcOlB67PdEAHaFB?pid=Api&P=0&h=180',
        direccion: "Av. Principal 456, Ciudad",
        telefono: "0987654321",
        horario_atencion: "Lun-Sab 8:00-18:00"
    }
];

// =============================
// Cargar servicios reales desde el backend y combinarlos con los de ejemplo
// =============================

// Función para renderizar una card de servicio (sirve para ambos tipos)
function renderServiceCard(servicio) {
    return `
        <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col h-full">
            <img src="${servicio.imagen_principal ? '/storage/' + servicio.imagen_principal : 'https://via.placeholder.com/300x200?text=Sin+Imagen'}" alt="${servicio.nombre_servicio}" class="w-full h-40 object-cover rounded mb-3">
            <h3 class="text-lg font-bold text-gray-800 mb-1">${servicio.nombre_servicio}</h3>
            <div class="text-sm text-gray-600 mb-2">${servicio.categoria}</div>
            <div class="text-gray-700 mb-2 line-clamp-2">${servicio.descripcion}</div>
            <div class="text-primary font-semibold mb-2">${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : ''}</div>
            <div class="text-xs text-gray-500 mb-1">${servicio.direccion || ''}</div>
            <div class="text-xs text-gray-500 mb-1">${servicio.telefono || ''}</div>
            <div class="text-xs text-gray-500 mb-4">${servicio.horario_atencion || ''}</div>
            <div class='flex justify-end mt-auto'>
                <button class='bg-red-500 hover:bg-red-400 text-white px-4 py-1 rounded-lg text-xs font-semibold shadow ver-mas transition-colors duration-200' data-id='${servicio.id}'>
                    <svg class="w-4 h-4 inline mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m6 0l-3-3m3 3l-3 3"/></svg>
                    Ver detalle
                </button>
            </div>
        </div>
    `;
}

// Función para cargar y renderizar servicios
async function loadServicios() {
    const grid = document.getElementById('services-grid');
    const count = document.getElementById('results-count');
    if (!grid) return;
    grid.innerHTML = '<div class="col-span-full text-center text-gray-400">Cargando servicios...</div>';
    try {
        // Petición AJAX al backend para obtener los servicios reales
        const response = await fetch('/servicios', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await response.json();
        let allServices = [...exampleServices]; // Comenzar con los de ejemplo
        if (data.success && Array.isArray(data.data)) {
            // Agregar los servicios reales al array
            allServices = allServices.concat(data.data);
        }
        grid.innerHTML = '';
        if (allServices.length > 0) {
            allServices.forEach(servicio => {
                grid.innerHTML += renderServiceCard(servicio);
            });
            if (count) count.textContent = allServices.length;
        } else {
            grid.innerHTML = '<div class="col-span-full text-center text-gray-400">No hay servicios disponibles.</div>';
            if (count) count.textContent = 0;
        }
    } catch (e) {
        grid.innerHTML = '<div class="col-span-full text-center text-red-400">Error al cargar servicios.</div>';
        if (count) count.textContent = 0;
    }
}


// Obtener el id del usuario autenticado si está disponible (inyectado desde Blade)
document.addEventListener('DOMContentLoaded', () => {
    if (window.currentEntrepreneurId === undefined && window.entrepreneurIdFromBlade) {
        window.currentEntrepreneurId = window.entrepreneurIdFromBlade;
    }
    loadServicios();

    // Delegar evento para el botón 'Ver más'
    document.body.addEventListener('click', async function(e) {
        if (e.target.closest('.ver-mas')) {
            const btn = e.target.closest('.ver-mas');
            const id = btn.dataset.id;
            try {
                const resp = await fetch(`/servicios/${id}`);
                const data = await resp.json();
                if (data.success) {
                    showServiceModal(data.data);
                }
            } catch {}
        }
    });
});

// Modal para ver detalles del servicio
function showServiceModal(servicio) {
    let modal = document.getElementById('modal-ver-detalles');
    if (modal) modal.remove();
    modal = document.createElement('div');
    modal.id = 'modal-ver-detalles';
    modal.className = 'fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center';
    modal.innerHTML = `
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
            <button class="absolute top-2 right-2 text-gray-600 hover:text-primary" onclick="document.getElementById('modal-ver-detalles').remove()">&times;</button>
            <h3 class="text-xl font-bold mb-2">${servicio.nombre_servicio}</h3>
            <img src="${servicio.imagen_principal ? '/storage/' + servicio.imagen_principal : 'https://via.placeholder.com/300x200?text=Sin+Imagen'}" alt="${servicio.nombre_servicio}" class="w-full h-48 object-cover rounded mb-3">
            <div class="mb-2 text-gray-700">${servicio.descripcion || ''}</div>
            <div class="mb-2"><span class="font-semibold">Categoría:</span> ${servicio.categoria || ''}</div>
            <div class="mb-2"><span class="font-semibold">Precio:</span> ${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : ''}</div>
            <div class="mb-2"><span class="font-semibold">Dirección:</span> ${servicio.direccion || ''}</div>
            <div class="mb-2"><span class="font-semibold">Teléfono:</span> ${servicio.telefono || ''}</div>
            <div class="mb-2"><span class="font-semibold">Horario:</span> ${servicio.horario_atencion || ''}</div>
            <div class="flex justify-end mt-6">
                <button class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-lg font-semibold shadow transition-colors duration-200" onclick="alert('Funcionalidad de agregar al carrito aquí')">Agregar al carrito</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}
