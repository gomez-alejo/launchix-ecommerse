document.addEventListener('DOMContentLoaded', function() {
    const grid = document.getElementById('mis-servicios-grid');
    const loading = document.getElementById('servicios-loading');
    const countEl = document.getElementById('servicios-count');

    // Funciones de modal mejoradas con animaciones
    window.closeModal = function(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.style.opacity = '0';
            modal.style.transform = 'scale(0.95)';
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.style.opacity = '';
                modal.style.transform = '';
            }, 200);
        }
    };

    function openModal(id) {
        const modal = document.getElementById(id);
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

    // Cerrar modal haciendo clic fuera del contenido
    function setupModalCloseOnOutsideClick() {
        document.addEventListener('click', function(e) {
            // Para modal ver
            const modalVer = document.getElementById('modal-ver');
            if (modalVer && !modalVer.classList.contains('hidden')) {
                const modalContent = modalVer.querySelector('.bg-white');
                if (e.target === modalVer && !modalContent.contains(e.target)) {
                    closeModal('modal-ver');
                }
            }

            // Para modal editar
            const modalEditar = document.getElementById('modal-editar');
            if (modalEditar && !modalEditar.classList.contains('hidden')) {
                const modalContent = modalEditar.querySelector('.bg-white');
                if (e.target === modalEditar && !modalContent.contains(e.target)) {
                    closeModal('modal-editar');
                }
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modalVer = document.getElementById('modal-ver');
                const modalEditar = document.getElementById('modal-editar');

                if (modalVer && !modalVer.classList.contains('hidden')) {
                    closeModal('modal-ver');
                }
                if (modalEditar && !modalEditar.classList.contains('hidden')) {
                    closeModal('modal-editar');
                }
            }
        });
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
        const categoryTag = getServiceCategoryName(servicio.categoria);
        const imageUrl = servicio.imagen_principal ? '/storage/' + servicio.imagen_principal : 'https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio';
        const precio = servicio.precio_base ? '$' + Number(servicio.precio_base).toLocaleString() : 'Consultar precio';

        return `
        <div class="product-card bg-white rounded-lg shadow-lg overflow-hidden fade-in transform hover:scale-105 transition-all duration-300">
            <div class="relative">
                <img src="${imageUrl}" alt="${servicio.nombre_servicio}" class="w-full h-64 object-cover"
                     onerror="this.src='https://via.placeholder.com/300x300/F77786/FFFFFF?text=Servicio'">
                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 transition-all duration-300 flex items-center justify-center opacity-0 hover:opacity-100">
                    <div class="flex space-x-2">
                        <button class="bg-white text-gray-800 px-3 py-2 rounded-lg font-semibold shadow-lg transform hover:scale-105 transition-all duration-300 ver-mas text-sm" data-id="${servicio.id}">
                            <i class="fas fa-eye mr-1"></i> Ver Detalle
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="category-tag inline-block mb-2 bg-red-100 text-red-600 px-2 py-1 rounded-full text-xs font-semibold">${categoryTag}</div>
                <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2">${servicio.nombre_servicio}</h3>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${servicio.descripcion}</p>

                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="text-2xl font-bold text-red-600">${precio}</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-gray-400 text-sm"></i>
                        <span class="text-xs text-gray-500">${servicio.direccion || 'Ubicación por definir'}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="flex items-center space-x-2 text-xs text-gray-500">
                        <i class="fas fa-phone text-blue-500"></i>
                        <span>${servicio.telefono || 'No especificado'}</span>
                    </div>
                    <div class="flex items-center space-x-2 text-xs text-gray-500 mt-1">
                        <i class="fas fa-clock text-green-500"></i>
                        <span>${servicio.horario_atencion || 'Horarios flexibles'}</span>
                    </div>
                </div>

                <!-- Botones de administración -->
                <div class="admin-controls border-t border-gray-200 pt-4 flex space-x-2">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 flex-1 editar-servicio" data-id="${servicio.id}">
                        <i class="fas fa-edit mr-2"></i> Editar
                    </button>
                    <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 eliminar-servicio" data-id="${servicio.id}">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
        `;
    }

    function getServiceCategoryName(categoria) {
        const categoryMap = {
            'limpieza': 'Servicios de Limpieza',
            'reparaciones': 'Reparaciones del Hogar',
            'jardineria': 'Jardinería y Paisajismo',
            'transporte': 'Servicios de Transporte',
            'tecnologia': 'Soporte Técnico',
            'educacion': 'Educación y Tutorías',
            'salud': 'Salud y Bienestar',
            'eventos': 'Organización de Eventos',
            'comida': 'Comida',
            'autolavado': 'Autolavado',
            'carpinteria': 'Carpintería',
            'belleza': 'Belleza y Spa',
            'hogar': 'Hogar y Jardinería'
        };

        // Si es un objeto con name o slug
        if (typeof categoria === 'object' && categoria !== null) {
            return categoria.name || categoryMap[categoria.slug] || categoria.slug || 'Sin categoría';
        }

        // Si es una cadena
        return categoryMap[categoria] || categoria || 'Sin categoría';
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
        document.getElementById('ver-nombre').textContent = data.nombre_servicio || 'Sin nombre';

        // Imagen principal
        const imgElement = document.getElementById('ver-img');
        imgElement.src = data.imagen_principal ? '/storage/' + data.imagen_principal : 'https://via.placeholder.com/400x300/F77786/FFFFFF?text=Servicio';
        imgElement.alt = data.nombre_servicio || 'Servicio';

        // Descripción
        document.getElementById('ver-descripcion').textContent = data.descripcion || 'Sin descripción disponible';

        // Categoría con estilo
        const categoriaName = getServiceCategoryName(data.categoria);
        document.getElementById('ver-categoria-badge').textContent = categoriaName;

        // Precio con formato
        const precioElement = document.getElementById('ver-precio');
        precioElement.textContent = data.precio_base ? '$' + Number(data.precio_base).toLocaleString() : 'Consultar precio';

        // Información de contacto
        document.getElementById('ver-direccion').textContent = data.direccion || 'Ubicación por definir';
        document.getElementById('ver-telefono').textContent = data.telefono || 'No especificado';
        document.getElementById('ver-horario').textContent = data.horario_atencion || 'Horarios flexibles';

        // Galería de imágenes
        const galeria = document.getElementById('ver-galeria');
        galeria.innerHTML = '';
        if (data.galeria_imagenes && data.galeria_imagenes.length > 0) {
            data.galeria_imagenes.forEach((img, index) => {
                const imgContainer = document.createElement('div');
                imgContainer.className = 'relative cursor-pointer group';

                const imgEl = document.createElement('img');
                imgEl.src = '/storage/' + img;
                imgEl.className = 'w-full h-20 object-cover rounded-lg shadow group-hover:opacity-75 transition-opacity';
                imgEl.alt = `Galería ${index + 1}`;

                // Click para ampliar imagen
                imgEl.addEventListener('click', () => {
                    showImageModal(imgEl.src, `${data.nombre_servicio} - Imagen ${index + 1}`);
                });

                imgContainer.appendChild(imgEl);
                galeria.appendChild(imgContainer);
            });
        } else {
            galeria.innerHTML = '<div class="text-gray-500 text-sm col-span-3 text-center">No hay más imágenes disponibles</div>';
        }
    }

    // Función para mostrar imagen ampliada
    function showImageModal(src, alt) {
        const modal = document.createElement('div');
        modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
        modal.innerHTML = `
            <div class="relative max-w-4xl max-h-full">
                <img src="${src}" alt="${alt}" class="max-w-full max-h-full object-contain rounded-lg">
                <button class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full w-10 h-10 flex items-center justify-center hover:bg-opacity-75 transition-all" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Click fuera para cerrar
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.remove();
            }
        });

        document.body.appendChild(modal);
    }

    // Llenar modal editar
    function fillEditModal(data) {
        document.getElementById('edit-id').value = data.id;
        document.getElementById('edit-nombre').value = data.nombre_servicio || '';

        // Manejar categoría (puede ser string u objeto)
        let categoriaValue = '';
        if (typeof data.categoria === 'object' && data.categoria !== null) {
            categoriaValue = data.categoria.slug || data.categoria.name || '';
        } else {
            categoriaValue = data.categoria || '';
        }
        document.getElementById('edit-categoria').value = categoriaValue;

        document.getElementById('edit-descripcion').value = data.descripcion || '';
        document.getElementById('edit-direccion').value = data.direccion || '';
        document.getElementById('edit-telefono').value = data.telefono || '';
        document.getElementById('edit-precio').value = data.precio_base || '';
        document.getElementById('edit-horario').value = data.horario_atencion || '';
        document.getElementById('edit-imagen').value = '';
        document.getElementById('edit-galeria').value = '';
        document.getElementById('edit-errors').innerHTML = '';

        // Limpiar previews
        document.getElementById('edit-main-preview').innerHTML = '';
        document.getElementById('edit-gallery-preview').innerHTML = '';
    }

    // Editar servicio (submit)
    document.getElementById('form-editar-servicio').addEventListener('submit', async function(e) {
        e.preventDefault();
        const id = document.getElementById('edit-id').value;
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Mostrar loading
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...';

        const formData = new FormData(form);
        // Añadir method PUT para actualización
        formData.append('_method', 'PUT');

        try {
            // Usar ServicesManager si está disponible
            if (window.ServicesManager && typeof window.ServicesManager.saveService === 'function') {
                const result = await window.ServicesManager.saveService(formData, `/servicios/${id}`);

                if (result.success) {
                    closeModal('modal-editar');
                    loadMisServicios();
                    showSuccessToast('Servicio actualizado exitosamente');

                    // Recargar la vista pública si existe
                    if (window.ServicesManager && typeof window.ServicesManager.loadServicios === 'function') {
                        window.ServicesManager.loadServicios();
                    }
                } else {
                    showEditErrors(result.errors || {'general': [result.message || 'Error al actualizar servicio']});
                }
            } else {
                // Fallback al método original
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
                    showSuccessToast('Servicio actualizado exitosamente');
                } else {
                    showEditErrors(data.errors || {'general': [data.message || 'Error al actualizar servicio']});
                }
            }
        } catch (e) {
            console.error('Error al actualizar servicio:', e);
            showEditErrors({'general': ['Error inesperado al actualizar el servicio']});
        } finally {
            // Restaurar botón
            submitButton.disabled = false;
            submitButton.innerHTML = originalButtonText;
        }
    });

    function showEditErrors(errors) {
        const div = document.getElementById('edit-errors');
        let html = '';
        if (typeof errors === 'object' && errors !== null) {
            for (const key in errors) {
                if (Array.isArray(errors[key])) {
                    html += errors[key].join('<br>') + '<br>';
                } else {
                    html += errors[key] + '<br>';
                }
            }
        } else {
            html = errors || 'Error desconocido';
        }
        div.innerHTML = html;
    }

    function showSuccessToast(message) {
        // Crear toast de éxito
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        toast.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
        document.body.appendChild(toast);

        // Animación de entrada
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);

        // Animación de salida
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Setup drag & drop functionality para el modal de edición
    function setupEditImageUpload() {
        // Imagen principal
        const mainDropzone = document.getElementById('edit-main-dropzone');
        const mainInput = document.getElementById('edit-imagen');
        const mainPreview = document.getElementById('edit-main-preview');

        if (mainDropzone && mainInput && mainPreview) {
            setupDropzone(mainDropzone, mainInput, mainPreview, false);
        }

        // Galería
        const galleryDropzone = document.getElementById('edit-gallery-dropzone');
        const galleryInput = document.getElementById('edit-galeria');
        const galleryPreview = document.getElementById('edit-gallery-preview');

        if (galleryDropzone && galleryInput && galleryPreview) {
            setupDropzone(galleryDropzone, galleryInput, galleryPreview, true);
        }
    }

    function setupDropzone(dropzone, input, preview, isMultiple) {
        // Click para abrir selector
        dropzone.addEventListener('click', (e) => {
            if (e.target.tagName !== 'BUTTON') {
                input.click();
            }
        });

        // Drag & Drop
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
            handleImageFiles(files, preview, input, isMultiple);
        });

        // Input change
        input.addEventListener('change', (e) => {
            handleImageFiles(e.target.files, preview, input, isMultiple);
        });
    }

    function handleImageFiles(files, preview, input, isMultiple) {
        if (!files || files.length === 0) return;

        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if (!isMultiple) {
            // Imagen principal - solo una
            const file = files[0];
            if (!allowedTypes.includes(file.type)) {
                alert('Solo se permiten imágenes JPG o PNG');
                input.value = '';
                return;
            }
            if (file.size > maxSize) {
                alert('La imagen debe pesar máximo 2MB');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                preview.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${e.target.result}" alt="Preview" class="w-32 h-32 object-cover rounded-lg border shadow">
                        <button type="button" onclick="this.parentElement.remove(); document.getElementById('edit-imagen').value = '';"
                                class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            // Galería - múltiples
            preview.innerHTML = '';
            const validFiles = Array.from(files).filter(file => {
                if (!allowedTypes.includes(file.type)) {
                    return false;
                }
                if (file.size > maxSize) {
                    return false;
                }
                return true;
            });

            if (validFiles.length !== files.length) {
                alert('Algunas imágenes no son válidas (solo JPG/PNG, máx 2MB cada una)');
            }

            validFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border shadow">
                        <button type="button" onclick="this.parentElement.remove();"
                                class="absolute -top-1 -right-1 bg-red-500 hover:bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    }

    // Configurar funcionalidad cuando se abre el modal de edición
    const originalOpenModal = openModal;
    openModal = function(id) {
        originalOpenModal(id);
        if (id === 'modal-editar') {
            setTimeout(setupEditImageUpload, 100);
        }

        // Asegurar que los botones de cerrar funcionen
        setTimeout(() => {
            const modal = document.getElementById(id);
            if (modal) {
                const closeBtn = modal.querySelector('.modal-close-btn');
                if (closeBtn) {
                    closeBtn.onclick = function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeModal(id);
                    };
                }
            }
        }, 50);
    };

    // Configurar eventos de cierre de modales
    setupModalCloseOnOutsideClick();

    // Configurar botones de cerrar existentes
    function setupCloseButtons() {
        document.querySelectorAll('.modal-close-btn').forEach(button => {
            const modalId = button.closest('[id^="modal-"]').id;
            button.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                closeModal(modalId);
            };
        });
    }

    // Configurar botones de cerrar inmediatamente
    setTimeout(setupCloseButtons, 100);

    // Exponer globalmente para recarga desde otros scripts
    window.loadMisServicios = loadMisServicios;
    window.closeModal = window.closeModal; // Asegurar acceso global
    loadMisServicios();
});
