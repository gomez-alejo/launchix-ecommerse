document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('mis-servicios-grid');
    const loading = document.getElementById('servicios-loading');
    const countEl = document.getElementById('servicios-count');

    window.closeModal = function(id) {
        document.getElementById(id).classList.add('hidden');
    };

    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    async function loadMisServicios() {
        if (loading) loading.classList.remove('hidden');
        grid.innerHTML = '';
        try {
            const resp = await fetch('/mis-servicios', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await resp.json();
            if (data.success && Array.isArray(data.data)) {
                countEl.textContent = data.data.length;
                if (data.data.length === 0) {
                    grid.innerHTML = '<div class="col-span-full text-center text-gray-400">No tienes servicios publicados.</div>';
                } else {
                    data.data.forEach(servicio => {
                        grid.innerHTML += renderServicioCard(servicio);
                    });
                }
            } else {
                grid.innerHTML = `<div class='col-span-full text-center text-red-400'>${data.message || 'Error al cargar servicios.'}</div>`;
            }
        } catch (e) {
            grid.innerHTML = `<div class='col-span-full text-center text-red-400'>Error al cargar servicios: ${e.message}</div>`;
        }
        if (loading) loading.classList.add('hidden');
        bindActions();
    }

    function renderServicioCard(servicio) {
        return `
        <div class="bg-white rounded-lg shadow-lg p-4 flex flex-col h-full relative group">
            <img src="${servicio.imagen_principal ? '/storage/' + servicio.imagen_principal : 'https://via.placeholder.com/300x200?text=Sin+Imagen'}" alt="${servicio.nombre_servicio}" class="w-full h-40 object-cover rounded mb-3">
            <h3 class="text-lg font-bold text-gray-800 mb-1">${servicio.nombre_servicio}</h3>
            <div class="text-sm text-gray-600 mb-2">${servicio.categoria}</div>
            <div class="text-gray-700 mb-2 line-clamp-2">${servicio.descripcion}</div>
            <div class="text-primary font-semibold mb-2">${servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : ''}</div>
            <div class="text-xs text-gray-500 mb-1">${servicio.direccion || ''}</div>
            <div class="text-xs text-gray-500 mb-1">${servicio.telefono || ''}</div>
            <div class="text-xs text-gray-500 mb-4">${servicio.horario_atencion || ''}</div>
            <div class="flex flex-wrap gap-2 mt-auto justify-end">
                <button class="bg-red-500 hover:bg-blue-600 text-white px-4 py-1 rounded-lg text-xs font-semibold shadow ver-mas transition-colors duration-200" data-id="${servicio.id}">
                    <svg class=\"w-4 h-4 inline mr-1 -mt-1\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12H9m6 0l-3-3m3 3l-3 3\"/></svg>
                    Ver detalle
                </button>
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg text-xs font-semibold shadow editar-servicio transition-colors duration-200" data-id="${servicio.id}">
                    <svg class=\"w-4 h-4 inline mr-1 -mt-1\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6\"/></svg>
                    Editar
                </button>
                <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded-lg text-xs font-semibold shadow eliminar-servicio transition-colors duration-200" data-id="${servicio.id}">
                    <svg class=\"w-4 h-4 inline mr-1 -mt-1\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\"><path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M6 18L18 6M6 6l12 12\"/></svg>
                    Eliminar
                </button>
            </div>
        </div>
        `;
    }

    function bindActions() {
        // Ver más
        grid.querySelectorAll('.ver-mas').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                try {
                    const resp = await fetch(`/servicios/${id}`);
                    const data = await resp.json();
                    if (data.success) {
                        fillVerModal(data.data);
                        openModal('modal-ver');
                    }
                } catch {}
            });
        });
        // Editar
        grid.querySelectorAll('.editar-servicio').forEach(btn => {
            btn.addEventListener('click', async function() {
                const id = this.dataset.id;
                try {
                    const resp = await fetch(`/servicios/${id}`);
                    const data = await resp.json();
                    if (data.success) {
                        fillEditModal(data.data);
                        openModal('modal-editar');
                    }
                } catch {}
            });
        });
        // Eliminar
        grid.querySelectorAll('.eliminar-servicio').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (!confirm('¿Seguro que deseas eliminar este servicio?')) return;
                const id = this.dataset.id;
                try {
                    const resp = await fetch(`/servicios/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    const data = await resp.json();
                    if (data.success) {
                        loadMisServicios();
                        if (window.ServicesManager && typeof window.ServicesManager.loadServicios === 'function') {
                            window.ServicesManager.loadServicios(); // recarga la vista pública si existe
                        }
                        showToast('Servicio eliminado correctamente', 'success');
                    } else {
                        showToast(data.message || 'No se pudo eliminar', 'error');
                    }
                } catch (e) {
                    showToast('Error al eliminar', 'error');
                }
            });
        });
    // Toast visual reutilizable
    function showToast(message, type = 'success') {
        let toast = document.getElementById('service-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'service-toast';
            toast.style.position = 'fixed';
            toast.style.top = '32px';
            toast.style.right = '32px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '220px';
            toast.style.padding = '16px 24px';
            toast.style.borderRadius = '8px';
            toast.style.fontSize = '1rem';
            toast.style.boxShadow = '0 2px 12px rgba(0,0,0,0.12)';
            toast.style.transition = 'opacity 0.3s';
            document.body.appendChild(toast);
        }
        toast.innerText = message;
        toast.style.background = type === 'success' ? '#22c55e' : '#ef4444';
        toast.style.color = '#fff';
        toast.style.opacity = '1';
        toast.style.display = 'block';
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => { toast.style.display = 'none'; }, 400);
        }, 2500);
    }
    }

    // Llenar modal ver más
    function fillVerModal(data) {
        document.getElementById('ver-nombre').textContent = data.nombre_servicio;
        document.getElementById('ver-img').src = data.imagen_principal ? '/storage/' + data.imagen_principal : 'https://via.placeholder.com/300x200?text=Sin+Imagen';
        document.getElementById('ver-descripcion').textContent = data.descripcion || '';
        document.getElementById('ver-categoria').textContent = data.categoria || '';
        document.getElementById('ver-precio').textContent = data.precio_base ? '$' + Number(data.precio_base).toLocaleString() : '';
        document.getElementById('ver-direccion').textContent = data.direccion || '';
        document.getElementById('ver-telefono').textContent = data.telefono || '';
        document.getElementById('ver-horario').textContent = data.horario_atencion || '';
        // Galería
        const galeriaDiv = document.getElementById('ver-galeria');
        galeriaDiv.innerHTML = '';
        if (data.galeria_imagenes && Array.isArray(data.galeria_imagenes)) {
            data.galeria_imagenes.forEach(img => {
                galeriaDiv.innerHTML += `<img src="/storage/${img}" class="w-16 h-16 object-cover rounded" alt="Imagen galería">`;
            });
        }
    }

    // Llenar modal editar
    function fillEditModal(data) {
        document.getElementById('edit-id').value = data.id;
        document.getElementById('edit-nombre').value = data.nombre_servicio || '';
        document.getElementById('edit-categoria').value = data.categoria || '';
        document.getElementById('edit-descripcion').value = data.descripcion || '';
        document.getElementById('edit-direccion').value = data.direccion || '';
        document.getElementById('edit-telefono').value = data.telefono || '';
        document.getElementById('edit-precio').value = data.precio_base || '';
        document.getElementById('edit-horario').value = data.horario_atencion || '';
        document.getElementById('edit-imagen').value = '';
        document.getElementById('edit-galeria').value = '';
        document.getElementById('edit-errors').innerHTML = '';
    }

    // Editar servicio (submit)
    document.getElementById('form-editar-servicio').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const form = e.target;
        const formData = new FormData(form);

        try {
            const resp = await fetch(`/servicios/${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });
            const data = await resp.json();
            if (data.success) {
                closeModal('modal-editar');
                loadMisServicios();
                alert('Servicio actualizado');
            } else {
                showEditErrors(data.errors);
            }
        } catch (e) {
            showEditErrors({'general': ['Ocurrió un error']});
        }
    });

    function showEditErrors(errors) {
        const div = document.getElementById('edit-errors');
        let html = '';
        for (const key in errors) {
            html += errors[key].join('<br>') + '<br>';
        }
        div.innerHTML = html;
    }

    // Exponer globalmente para recarga desde otros scripts
    window.loadMisServicios = loadMisServicios;
    loadMisServicios();
});
