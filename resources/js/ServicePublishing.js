/**
 * Services Manager
 * Maneja toda la funcionalidad relacionada con servicios:
 * - Subida de imágenes
 * - Validación de formularios
 * - CRUD de servicios
 * - Visualización de servicios
 */

window.ServicesManager = {

    // ========== CONFIGURACIÓN DE SUBIDA DE IMÁGENES ==========

    /**
     * Configurar funcionalidad de subida de imágenes
     */
    setupImageUpload(dropzoneId, inputId, previewId) {
        const dropzone = document.getElementById(dropzoneId);
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);

        // Verificar que los elementos existan
        if (!dropzone || !input || !preview) {
            console.warn(`Elementos no encontrados: ${dropzoneId}, ${inputId}, ${previewId}`);
            return;
        }

        dropzone.addEventListener('click', () => input.click());

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('drag-over');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('drag-over');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('drag-over');
            const files = e.dataTransfer.files;
            this.handleFiles(files, preview, inputId);
        });

        input.addEventListener('change', (e) => {
            this.handleFiles(e.target.files, preview, inputId);
        });
    },

    /**
     * Manejar archivos seleccionados
     */
    handleFiles(files, preview, inputId) {
        preview.innerHTML = '';
        const input = document.getElementById(inputId);

        // Crear un nuevo DataTransfer para manejar los archivos
        const dataTransfer = new DataTransfer();

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                dataTransfer.items.add(file);

                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'image-preview w-full h-32 object-cover rounded';

                    const container = document.createElement('div');
                    container.className = 'relative';

                    const deleteBtn = document.createElement('button');
                    deleteBtn.innerHTML = '×';
                    deleteBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600';
                    deleteBtn.type = 'button';
                    deleteBtn.onclick = () => {
                        container.remove();
                        // Actualizar el input file removiendo este archivo
                        this.updateFileInput(input, file);
                    };

                    container.appendChild(img);
                    container.appendChild(deleteBtn);
                    preview.appendChild(container);
                };
                reader.readAsDataURL(file);
            }
        });

        // Actualizar el input con los archivos válidos
        input.files = dataTransfer.files;
    },

    /**
     * Actualizar input de archivos removiendo un archivo específico
     */
    updateFileInput(input, fileToRemove) {
        const dataTransfer = new DataTransfer();
        Array.from(input.files).forEach(file => {
            if (file !== fileToRemove) {
                dataTransfer.items.add(file);
            }
        });
        input.files = dataTransfer.files;
    },

    // ========== VALIDACIÓN Y MANEJO DE FORMULARIOS ==========

    /**
     * Validar formulario de servicio
     */
    validateServiceForm(formData) {
        const errors = [];

        if (!formData.get('nombre_servicio')) {
            errors.push('El nombre del servicio es obligatorio');
        }

        if (!formData.get('categoria')) {
            errors.push('La categoría es obligatoria');
        }

        if (!formData.get('descripcion')) {
            errors.push('La descripción es obligatoria');
        }

        if (!formData.get('direccion')) {
            errors.push('La dirección es obligatoria');
        }

        if (!formData.get('telefono')) {
            errors.push('El teléfono es obligatorio');
        }

        return errors;
    },

    /**
     * Mostrar errores de validación
     */
    showErrors(errors) {
        // Remover errores anteriores
        const existingErrors = document.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());

        if (errors.length > 0) {
            const errorContainer = document.createElement('div');
            errorContainer.className = 'error-message bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';

            // Título del error
            const errorTitle = document.createElement('h4');
            errorTitle.className = 'font-bold mb-2';
            errorTitle.textContent = 'Se encontraron los siguientes errores:';
            errorContainer.appendChild(errorTitle);

            const errorList = document.createElement('ul');
            errorList.className = 'list-disc list-inside space-y-1';

            errors.forEach(error => {
                const li = document.createElement('li');
                li.className = 'text-sm';
                li.textContent = error;
                errorList.appendChild(li);
            });

            errorContainer.appendChild(errorList);

            // Botón para copiar errores al portapapeles (para debugging)
            const copyButton = document.createElement('button');
            copyButton.type = 'button';
            copyButton.className = 'mt-2 text-xs bg-red-200 hover:bg-red-300 px-2 py-1 rounded';
            copyButton.textContent = 'Copiar errores para soporte';
            copyButton.onclick = () => {
                navigator.clipboard.writeText(errors.join('\n')).then(() => {
                    copyButton.textContent = 'Copiado!';
                    setTimeout(() => {
                        copyButton.textContent = 'Copiar errores para soporte';
                    }, 2000);
                });
            };
            errorContainer.appendChild(copyButton);

            const form = document.getElementById('servicio-form');
            form.insertBefore(errorContainer, form.firstChild);

            // Scroll to top of form to show errors
            form.scrollIntoView({ behavior: 'smooth' });

            return false;
        }

        return true;
    },

    /**
     * Mostrar estado de carga en botón
     */
    showLoading(button) {
        button.disabled = true;
        button.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Guardando...
        `;
    },

    /**
     * Ocultar estado de carga en botón
     */
    hideLoading(button, originalText = 'Publicar Servicio') {
        button.disabled = false;
        button.innerHTML = originalText;
    },

    /**
     * Limpiar formulario de servicio
     */
    clearServiceForm() {
        const form = document.getElementById('servicio-form');
        form.reset();

        // Limpiar previsualizaciones de imágenes
        const mainPreview = document.getElementById('service-main-preview');
        const galleryPreview = document.getElementById('service-gallery-preview');

        if (mainPreview) mainPreview.innerHTML = '';
        if (galleryPreview) galleryPreview.innerHTML = '';

        // Remover mensajes de error
        const existingErrors = document.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());
    },

    // ========== COMUNICACIÓN CON EL SERVIDOR ==========

    /**
     * Guardar servicio en Laravel
     */
    async saveService(formData) {
        try {
            // Log para debugging - mostrar qué datos se están enviando
            console.log('Datos que se envían:');
            for (let [key, value] of formData.entries()) {
                if (value instanceof File) {
                    console.log(`${key}: [Archivo] ${value.name} (${value.size} bytes)`);
                } else {
                    console.log(`${key}: ${value}`);
                }
            }

            const response = await fetch('/servicios', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

            console.log('Status de respuesta:', response.status);
            console.log('Headers de respuesta:', response.headers);

            let result;
            const contentType = response.headers.get('content-type');

            if (contentType && contentType.includes('application/json')) {
                result = await response.json();
            } else {
                // Si no es JSON, obtener el texto para ver qué está devolviendo
                const textResult = await response.text();
                console.error('Respuesta no JSON:', textResult);

                return {
                    success: false,
                    errors: [
                        `Error del servidor (${response.status})`,
                        'La respuesta no es JSON válido',
                        `Contenido: ${textResult.substring(0, 200)}...`
                    ],
                    message: 'Error en la respuesta del servidor'
                };
            }

            console.log('Resultado del servidor:', result);

            if (response.ok) {
                return {
                    success: true,
                    data: result,
                    message: result.message || 'Servicio guardado exitosamente'
                };
            } else {
                // Manejo detallado de errores según el tipo
                let detailedErrors = [];

                // Errores de validación de Laravel
                if (result.errors && typeof result.errors === 'object') {
                    Object.keys(result.errors).forEach(field => {
                        const fieldErrors = Array.isArray(result.errors[field])
                            ? result.errors[field]
                            : [result.errors[field]];

                        fieldErrors.forEach(error => {
                            detailedErrors.push(`${field}: ${error}`);
                        });
                    });
                }
                // Si errors es un array
                else if (result.errors && Array.isArray(result.errors)) {
                    detailedErrors = result.errors;
                }
                // Si no hay estructura de errores conocida
                else {
                    detailedErrors = [
                        result.message || 'Error desconocido del servidor',
                        `Código de estado: ${response.status}`,
                        `Respuesta completa: ${JSON.stringify(result)}`
                    ];
                }

                return {
                    success: false,
                    errors: detailedErrors,
                    message: result.message || `Error del servidor (${response.status})`
                };
            }
        } catch (error) {
            console.error('Error completo:', error);
            console.error('Stack trace:', error.stack);

            return {
                success: false,
                errors: [
                    'Error de conexión o procesamiento',
                    `Tipo: ${error.name}`,
                    `Mensaje: ${error.message}`,
                    'Revisa la consola del navegador para más detalles'
                ],
                message: 'Error de conexión'
            };
        }
    },

    // ========== VISUALIZACIÓN Y CARGA DE SERVICIOS ==========

    /**
     * Cargar servicios desde el servidor
     */
    async loadServicios() {
        try {
            console.log('Cargando servicios...');

            // Mostrar loading
            const loadingElement = document.getElementById('servicios-loading');
            const serviciosContainer = document.querySelector('#servicios .grid');

            if (loadingElement) {
                loadingElement.classList.remove('hidden');
            }
            if (serviciosContainer) {
                serviciosContainer.innerHTML = '';
            }

            const response = await fetch('/servicios', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            // Ocultar loading
            if (loadingElement) {
                loadingElement.classList.add('hidden');
            }

            if (result.success) {
                // Solo intentar renderizar si existe el contenedor
                const serviciosContainer = document.getElementById('services-grid');
                if (serviciosContainer) {
                    this.displayServicios(result.data);
                }
                // Si existe la función global para recargar 'Mis Servicios', llamarla
                if (window.loadMisServicios) {
                    window.loadMisServicios();
                }
            } else {
                console.error('Error al cargar servicios:', result);
                this.showServiciosError('Error al cargar los servicios');
            }
        } catch (error) {
            console.error('Error al cargar servicios:', error);

            // Ocultar loading en caso de error
            const loadingElement = document.getElementById('servicios-loading');
            if (loadingElement) {
                loadingElement.classList.add('hidden');
            }

            this.showServiciosError('Error de conexión al cargar servicios');
        }
    },

    /**
     * Mostrar servicios en la vista
     */
    displayServicios(servicios) {
    const serviciosContainer = document.getElementById('services-grid');

        if (!serviciosContainer) {
            console.warn('Contenedor de servicios no encontrado');
            return;
        }

        // Limpiar contenido anterior
        serviciosContainer.innerHTML = '';

        if (servicios.length === 0) {
            serviciosContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No tienes servicios publicados</h3>
                    <p class="text-gray-500 mb-6">Comienza publicando tu primer servicio</p>
                    <button class="btn-primary text-white px-6 py-2 rounded-lg" onclick="showSection('publicar-servicio')">
                        Publicar mi primer servicio
                    </button>
                </div>
            `;
            return;
        }

        // Crear cards para cada servicio
        servicios.forEach(servicio => {
            const servicioCard = this.createServicioCard(servicio);
            serviciosContainer.appendChild(servicioCard);
        });
    },

    /**
     * Crear una card de servicio
     */
    createServicioCard(servicio) {
        const card = document.createElement('div');
        card.className = 'product-card bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';

        const precioTexto = servicio.precio_base
            ? `Desde ${parseInt(servicio.precio_base).toLocaleString()}`
            : 'Precio por consultar';

        const imagenSrc = servicio.imagen_principal
            ? servicio.imagen_principal
            : null;

        card.innerHTML = `
            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                ${imagenSrc ?
                    `<img src="${imagenSrc}" alt="${servicio.nombre_servicio}" class="w-full h-full object-cover">` :
                    `<svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>`
                }
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800 truncate">${servicio.nombre_servicio}</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">${servicio.categoria}</span>
                </div>
                <p class="text-gray-600 text-sm mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    ${servicio.direccion}
                </p>
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">${servicio.descripcion}</p>
                ${servicio.horario_atencion ?
                    `<p class="text-gray-500 text-xs mb-3">
                        <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ${servicio.horario_atencion}
                    </p>` : ''
                }
                <div class="flex justify-between items-center">
                    <span class="text-primary font-bold">${precioTexto}</span>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium" onclick="ServicesManager.editarServicio(${servicio.id})">
                            Editar
                        </button>
                        <button class="text-red-600 hover:text-red-800 text-sm font-medium" onclick="ServicesManager.eliminarServicio(${servicio.id})">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;

        return card;
    },
        /**
         * Modal para galería de imágenes (slider)
         */
        showGalleryModal(servicioId, startIdx = 0) {
            // Buscar el servicio en la lista cargada
            const servicio = this._lastServicios?.find(s => s.id === servicioId);
            if (!servicio || !servicio.galeria_imagenes || !servicio.galeria_imagenes.length) return;

            let currentIdx = startIdx;

            // Crear modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50';
            modal.style.animation = 'fadeIn .2s';
            modal.innerHTML = `
                <div class="bg-white rounded-lg shadow-lg p-4 max-w-lg w-full relative">
                    <button class="absolute top-2 right-2 text-gray-500 hover:text-red-500 text-2xl font-bold" onclick="document.body.removeChild(this.closest('.fixed'))">&times;</button>
                    <div class="flex items-center justify-center mb-4">
                        <button id="gallery-prev" class="text-2xl px-2 py-1 text-gray-400 hover:text-primary">&#8592;</button>
                        <img id="gallery-img" src="${servicio.galeria_imagenes[currentIdx]}" alt="Galería" class="max-h-80 rounded shadow mx-4">
                        <button id="gallery-next" class="text-2xl px-2 py-1 text-gray-400 hover:text-primary">&#8594;</button>
                    </div>
                    <div class="flex justify-center space-x-2">
                        ${servicio.galeria_imagenes.map((img, idx) =>
                            `<img src="${img}" alt="Miniatura" class="w-10 h-10 object-cover rounded border ${idx === currentIdx ? 'border-primary' : 'border-gray-200'} cursor-pointer" data-idx="${idx}">`
                        ).join('')}
                    </div>
                </div>
            `;
            document.body.appendChild(modal);

            // Slider funcionalidad
            const updateImg = (idx) => {
                currentIdx = idx;
                modal.querySelector('#gallery-img').src = servicio.galeria_imagenes[currentIdx];
                modal.querySelectorAll('img[data-idx]').forEach((thumb, i) => {
                    thumb.classList.toggle('border-primary', i === currentIdx);
                    thumb.classList.toggle('border-gray-200', i !== currentIdx);
                });
            };
            modal.querySelector('#gallery-prev').onclick = () => {
                if (currentIdx > 0) updateImg(currentIdx - 1);
            };
            modal.querySelector('#gallery-next').onclick = () => {
                if (currentIdx < servicio.galeria_imagenes.length - 1) updateImg(currentIdx + 1);
            };
            modal.querySelectorAll('img[data-idx]').forEach(thumb => {
                thumb.onclick = () => updateImg(Number(thumb.dataset.idx));
            });
        },

    /**
     * Mostrar errores en la vista de servicios
     */
    showServiciosError(mensaje) {
        const serviciosContainer = document.querySelector('#servicios .grid');
        if (serviciosContainer) {
            serviciosContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">${mensaje}</p>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg" onclick="ServicesManager.loadServicios()">
                        Reintentar
                    </button>
                </div>
            `;
        }
    },

    // ========== OPERACIONES CRUD ==========

    /**
     * Eliminar servicio
     */
    async eliminarServicio(id) {
        // Crear modal de confirmación personalizado
        const confirmed = await window.showConfirmDialog({
            title: '¿Eliminar servicio?',
            message: '¿Estás seguro de que deseas eliminar este servicio? Esta acción no se puede deshacer y se eliminarán todas las imágenes asociadas.',
            confirmText: 'Sí, eliminar',
            cancelText: 'Cancelar',
            type: 'danger'
        });

        if (!confirmed) {
            return;
        }

        // Mostrar loading en el botón
        const deleteButtons = document.querySelectorAll(`button[onclick="ServicesManager.eliminarServicio(${id})"]`);
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-3 h-3 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        });

        try {
            const response = await fetch(`/servicios/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                window.showSuccessMessage('Servicio eliminado exitosamente');
                // Recargar la lista de servicios
                this.loadServicios();
            } else {
                window.showErrorMessage('Error al eliminar el servicio: ' + (result.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error al eliminar servicio:', error);
            window.showErrorMessage('Error de conexión al eliminar el servicio');
        } finally {
            // Restaurar botones
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = 'Eliminar';
            });
        }
    },

    /**
     * Editar servicio - Abre modal de edición
     */
    async editarServicio(id) {
        try {
            // Mostrar loading mientras carga los datos
            this.showEditLoading();

            // Obtener datos del servicio
            const serviceData = await this.getServicioById(id);

            if (serviceData.success) {
                // Mostrar modal de edición con los datos
                this.showEditModal(serviceData.data);
            } else {
                window.showErrorMessage('Error al cargar los datos del servicio');
                console.error('Error:', serviceData);
            }
        } catch (error) {
            console.error('Error al cargar servicio para editar:', error);
            window.showErrorMessage('Error de conexión al cargar el servicio');
        }
    },

    /**
     * Obtener datos de un servicio específico
     */
    async getServicioById(id) {
        try {
            const response = await fetch(`/servicios/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (response.ok) {
                return {
                    success: true,
                    data: result.data || result
                };
            } else {
                return {
                    success: false,
                    message: result.message || 'Error al obtener el servicio'
                };
            }
        } catch (error) {
            console.error('Error en getServicioById:', error);
            return {
                success: false,
                message: 'Error de conexión'
            };
        }
    },

    /**
     * Mostrar loading mientras carga datos para editar
     */
    showEditLoading() {
        // Crear un modal simple de loading
        const loadingModal = document.createElement('div');
        loadingModal.id = 'edit-loading-modal';
        loadingModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingModal.innerHTML = `
            <div class="bg-white rounded-lg p-6 text-center">
                <svg class="animate-spin w-8 h-8 text-blue-600 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-600">Cargando datos del servicio...</p>
            </div>
        `;
        document.body.appendChild(loadingModal);
    },

    /**
     * Mostrar modal de edición con los datos del servicio
     */
    showEditModal(servicio) {
        // Remover modal de loading si existe
        const loadingModal = document.getElementById('edit-loading-modal');
        if (loadingModal) {
            document.body.removeChild(loadingModal);
        }

        // Crear modal de edición
        const editModal = document.createElement('div');
        editModal.id = 'edit-service-modal';
        editModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';

        editModal.innerHTML = `
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Editar Servicio</h2>
                    <button id="close-edit-modal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <form id="edit-service-form" class="p-6">
                    <input type="hidden" id="edit-service-id" value="${servicio.id}">

                    <!-- Grid de dos columnas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Columna izquierda: Información básica -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Información Básica</h3>

                            <!-- Nombre del servicio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Servicio *</label>
                                <input type="text" id="edit-nombre-servicio" value="${servicio.nombre_servicio || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ej: Reparación de celulares" required>
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                                <select id="edit-categoria" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="Tecnología" ${servicio.categoria === 'Tecnología' ? 'selected' : ''}>Tecnología</option>
                                    <option value="Hogar y Jardinería" ${servicio.categoria === 'Hogar y Jardinería' ? 'selected' : ''}>Hogar y Jardinería</option>
                                    <option value="Automotriz" ${servicio.categoria === 'Automotriz' ? 'selected' : ''}>Automotriz</option>
                                    <option value="Belleza y Cuidado Personal" ${servicio.categoria === 'Belleza y Cuidado Personal' ? 'selected' : ''}>Belleza y Cuidado Personal</option>
                                    <option value="Educación y Tutorías" ${servicio.categoria === 'Educación y Tutorías' ? 'selected' : ''}>Educación y Tutorías</option>
                                    <option value="Salud y Bienestar" ${servicio.categoria === 'Salud y Bienestar' ? 'selected' : ''}>Salud y Bienestar</option>
                                    <option value="Eventos y Entretenimiento" ${servicio.categoria === 'Eventos y Entretenimiento' ? 'selected' : ''}>Eventos y Entretenimiento</option>
                                    <option value="Construcción y Reformas" ${servicio.categoria === 'Construcción y Reformas' ? 'selected' : ''}>Construcción y Reformas</option>
                                    <option value="Transporte y Logística" ${servicio.categoria === 'Transporte y Logística' ? 'selected' : ''}>Transporte y Logística</option>
                                    <option value="Servicios Profesionales" ${servicio.categoria === 'Servicios Profesionales' ? 'selected' : ''}>Servicios Profesionales</option>
                                    <option value="Otros" ${servicio.categoria === 'Otros' ? 'selected' : ''}>Otros</option>
                                </select>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                                <textarea id="edit-descripcion" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Describe tu servicio en detalle..." required>${servicio.descripcion || ''}</textarea>
                            </div>

                            <!-- Precio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio Base</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" id="edit-precio-base" value="${servicio.precio_base || ''}"
                                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0" min="0" step="1000">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Precio base o desde. Déjalo vacío si prefieres "Precio por consultar"</p>
                            </div>
                        </div>

                        <!-- Columna derecha: Información de contacto -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Información de Contacto</h3>

                            <!-- Dirección -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <input type="text" id="edit-direccion" value="${servicio.direccion || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Dirección completa donde ofreces el servicio" required>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                <input type="tel" id="edit-telefono" value="${servicio.telefono || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ej: +57 300 123 4567" required>
                            </div>

                            <!-- Horario de atención -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Horario de Atención</label>
                                <input type="text" id="edit-horario-atencion" value="${servicio.horario_atencion || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ej: Lun-Vie 8:00-17:00, Sáb 9:00-12:00">
                            </div>

                            <!-- Imágenes actuales -->
                            <div>
                                <h4 class="text-md font-medium text-gray-700 mb-2">Imágenes Actuales</h4>
                                <div id="edit-current-images" class="grid grid-cols-2 gap-2">
                                    ${this.renderCurrentImages(servicio)}
                                </div>
                            </div>

                            <!-- Nueva imagen principal -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Imagen Principal</label>
                                <div id="edit-main-dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click o arrastra para cambiar imagen principal</p>
                                </div>
                                <input type="file" id="edit-main-image" accept="image/*" class="hidden">
                                <div id="edit-main-preview" class="mt-2 grid grid-cols-1 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" id="cancel-edit" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Actualizar Servicio
                        </button>
                    </div>
                </form>
            </div>
        `;

        document.body.appendChild(editModal);

        // Configurar eventos del modal
        this.setupEditModalEvents(editModal, servicio);
    },

    /**
     * Renderizar imágenes actuales del servicio
     */
    renderCurrentImages(servicio) {
        let imagesHtml = '';

        // Imagen principal
        if (servicio.imagen_principal) {
            imagesHtml += `
                <div class="relative group">
                    <img src="${servicio.imagen_principal}" alt="Imagen principal" class="w-full h-20 object-cover rounded">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                        <span class="text-white text-xs">Principal</span>
                    </div>
                </div>
            `;
        }

        // Galería de imágenes
        if (servicio.galeria_imagenes && servicio.galeria_imagenes.length > 0) {
            servicio.galeria_imagenes.forEach((imagen, index) => {
                imagesHtml += `
                    <div class="relative group">
                        <img src="${imagen}" alt="Imagen ${index + 1}" class="w-full h-20 object-cover rounded">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                            <span class="text-white text-xs">Galería ${index + 1}</span>
                        </div>
                    </div>
                `;
            });
        }

        if (!imagesHtml) {
            imagesHtml = '<p class="text-gray-400 text-sm col-span-2 text-center py-4">No hay imágenes</p>';
        }

        return imagesHtml;
    },

    /**
     * Configurar eventos del modal de edición
     */
    setupEditModalEvents(modal, servicio) {
        // Cerrar modal
        const closeBtn = modal.querySelector('#close-edit-modal');
        const cancelBtn = modal.querySelector('#cancel-edit');

        const closeModal = () => {
            document.body.removeChild(modal);
        };

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Cerrar con ESC
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                document.removeEventListener('keydown', handleKeyDown);
            }
        };
        document.addEventListener('keydown', handleKeyDown);

        // Configurar drag & drop para imagen principal
        this.setupImageUpload('edit-main-dropzone', 'edit-main-image', 'edit-main-preview');

        // Manejar envío del formulario
        const form = modal.querySelector('#edit-service-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.updateServicio(servicio.id, closeModal);
        });
    },

    /**
     * Actualizar servicio en el servidor
     */
    async updateServicio(id, closeModalCallback) {
        const form = document.getElementById('edit-service-form');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Crear FormData
        const formData = new FormData();

        // Datos básicos
        formData.append('nombre_servicio', document.getElementById('edit-nombre-servicio').value);
        formData.append('categoria', document.getElementById('edit-categoria').value);
        formData.append('descripcion', document.getElementById('edit-descripcion').value);
        formData.append('direccion', document.getElementById('edit-direccion').value);
        formData.append('telefono', document.getElementById('edit-telefono').value);

        // Datos opcionales
        const precioBase = document.getElementById('edit-precio-base').value;
        if (precioBase) {
            formData.append('precio_base', precioBase);
        }

        const horarioAtencion = document.getElementById('edit-horario-atencion').value;
        if (horarioAtencion) {
            formData.append('horario_atencion', horarioAtencion);
        }

        // Nueva imagen principal si se seleccionó
        const mainImageInput = document.getElementById('edit-main-image');
        if (mainImageInput.files[0]) {
            formData.append('imagen_principal', mainImageInput.files[0]);
        }

        // Método HTTP para Laravel (PUT/PATCH via POST)
        formData.append('_method', 'PUT');

        // Validar datos básicos
        const errors = [];
        if (!formData.get('nombre_servicio')) errors.push('El nombre del servicio es obligatorio');
        if (!formData.get('categoria')) errors.push('La categoría es obligatoria');
        if (!formData.get('descripcion')) errors.push('La descripción es obligatoria');
        if (!formData.get('direccion')) errors.push('La dirección es obligatoria');
        if (!formData.get('telefono')) errors.push('El teléfono es obligatorio');

        if (errors.length > 0) {
            window.showErrorMessage(errors.join(', '));
            return;
        }

        // Mostrar loading
        this.showLoading(submitButton);

        try {
            const response = await fetch(`/servicios/${id}`, {
                method: 'POST', // Laravel usa POST con _method=PUT
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.showSuccessMessage('Servicio actualizado exitosamente');
                closeModalCallback();
                // Recargar lista de servicios
                this.loadServicios();
            } else {
                const errorMessage = result.message || 'Error al actualizar el servicio';
                window.showErrorMessage(errorMessage);
                console.error('Error del servidor:', result);
            }
        } catch (error) {
            console.error('Error al actualizar servicio:', error);
            window.showErrorMessage('Error de conexión al actualizar el servicio');
        } finally {
            this.hideLoading(submitButton, originalButtonText);
        }
    },

    // ========== INICIALIZACIÓN ==========

    /**
     * Inicializar el manager de servicios
     */
    init() {
        console.log('Services Manager initialized successfully');

        // Setup image upload functionality para servicios
        this.setupImageUpload('service-main-dropzone', 'service-main-image', 'service-main-preview');
        this.setupImageUpload('service-gallery-dropzone', 'service-gallery-images', 'service-gallery-preview');

        // Cargar servicios al inicio
        this.loadServicios();

        // Form submission handler para servicios
        this.setupFormHandler();
    },

    /**
     * Configurar manejador del formulario
     */
    setupFormHandler() {
        const servicioForm = document.getElementById('servicio-form');
        if (servicioForm) {
            servicioForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const submitButton = e.target.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                // Crear FormData con todos los campos del formulario
                const formData = this.buildFormData(servicioForm);

                // Validar formulario
                const errors = this.validateServiceForm(formData);
                if (!this.showErrors(errors)) {
                    return;
                }

                // Mostrar loading
                this.showLoading(submitButton);

                try {
                    // Log detallado antes de enviar
                    console.log('=== INICIO DEBUG SERVICIO ===');
                    console.log('FormData entries:', Array.from(formData.entries()));
                    console.log('URL objetivo:', '/servicios');

                    // Verificar CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        this.showErrors(['Error: No se encontró el token CSRF. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en tu HTML']);
                        return;
                    }
                    console.log('CSRF Token encontrado:', csrfToken.getAttribute('content').substring(0, 10) + '...');

                    // Guardar servicio
                    const result = await this.saveService(formData);
                    console.log('Resultado final:', result);
                    console.log('=== FIN DEBUG SERVICIO ===');

                    if (result.success) {
                        // Mostrar notificación visual tipo toast
                        this.showToast(result.message || 'Servicio guardado exitosamente', 'success');

                        // Limpiar formulario
                        this.clearServiceForm();

                        // Regresar a la vista de servicios
                        if (window.showSection) {
                            window.showSection('servicios');
                            // Recargar los servicios para mostrar el nuevo
                            setTimeout(() => {
                                this.loadServicios();
                            }, 500);
                        }
                    } else {
                        // Mostrar errores del servidor
                        console.error('Errores del servidor:', result.errors);
                        this.showErrors(result.errors);
                    }
                } catch (error) {
                    console.error('Error al procesar:', error);
                    this.showErrors([
                        'Error inesperado al procesar la solicitud',
                        `Tipo: ${error.name}`,
                        `Mensaje: ${error.message}`,
                        'Revisa la consola del navegador para más detalles'
                    ]);
                } finally {
                    // Ocultar loading
                    this.hideLoading(submitButton, originalButtonText);
                }
            });
        }
    },

    /**
     * Mostrar notificación tipo toast
     */
    showToast(message, type = 'success') {
        let toast = document.getElementById('service-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'service-toast';
            toast.style.position = 'fixed';
            toast.style.top = '32px';
            toast.style.right = '32px';
            toast.style.zIndex = '9999';
            toast.style.minWidth = '220px';
            if (servicioForm) {
                // Elimina listeners previos para evitar doble submit
                servicioForm.onsubmit = null;
                servicioForm.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    if (servicioForm.dataset.submitting === 'true') return;
                    servicioForm.dataset.submitting = 'true';

                    const submitButton = e.target.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;

                    // Verificar input file antes de manipular
                    const mainImageInput = document.getElementById('service-main-image');
                    if (mainImageInput) {
                        console.log('Archivo seleccionado en service-main-image:', mainImageInput.files[0]);
                        if (!mainImageInput.files[0] || mainImageInput.files[0].size === 0) {
                            console.warn('No hay archivo seleccionado o el archivo tiene tamaño 0.');
                        }
                    } else {
                        console.warn('No se encontró el input service-main-image');
                    }

                    // Crear FormData con todos los campos del formulario (sin manipular el input file antes)
                    const formData = this.buildFormData(servicioForm);

                    // Validar formulario
                    const errors = this.validateServiceForm(formData);
                    if (!this.showErrors(errors)) {
                        servicioForm.dataset.submitting = 'false';
                        return;
                    }

                    // Mostrar loading
                    this.showLoading(submitButton);

                    try {
                        // Log detallado antes de enviar
                        console.log('=== INICIO DEBUG SERVICIO ===');
                        for (let [key, value] of formData.entries()) {
                            if (value instanceof File) {
                                console.log(`${key}: [Archivo] ${value.name} (${value.size} bytes)`);
                            } else {
                                console.log(`${key}: ${value}`);
                            }
                        }
                        console.log('URL objetivo:', '/servicios');

                        // Verificar CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (!csrfToken) {
                            this.showErrors(['Error: No se encontró el token CSRF. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en tu HTML']);
                            servicioForm.dataset.submitting = 'false';
                            return;
                        }
                        console.log('CSRF Token encontrado:', csrfToken.getAttribute('content').substring(0, 10) + '...');

                        // Guardar servicio
                        const result = await this.saveService(formData);
                        console.log('Resultado final:', result);
                        console.log('=== FIN DEBUG SERVICIO ===');

                        if (result.success) {
                            // Mostrar notificación visual tipo toast
                            this.showToast(result.message || 'Servicio guardado exitosamente', 'success');

                            // Limpiar formulario
                            this.clearServiceForm();

                            // Regresar a la vista de servicios
                            if (window.showSection) {
                                window.showSection('servicios');
                            }
                            // Recargar ambas vistas si existen
                            if (window.ServicesManager && typeof window.ServicesManager.loadServicios === 'function') {
                                window.ServicesManager.loadServicios();
                            }
                            if (window.loadMisServicios) {
                                window.loadMisServicios();
                            }
                        } else {
                            // Mostrar errores del servidor
                            console.error('Errores del servidor:', result.errors);
                            this.showErrors(result.errors);
                        }
                    } catch (error) {
                        console.error('Error al procesar:', error);
                        this.showErrors([
                            'Error inesperado al procesar la solicitud',
                            `Tipo: ${error.name}`,
                            `Mensaje: ${error.message}`,
                            'Revisa la consola del navegador para más detalles'
                        ]);
                    } finally {
                        // Ocultar loading
                        this.hideLoading(submitButton, originalButtonText);
                        servicioForm.dataset.submitting = 'false';
                    }
                });
            }
            formData.set('precio_base', priceInput.value);
        }

        const scheduleInput = servicioForm.querySelector('input[placeholder*="Lun-Vie"]');
        if (scheduleInput && scheduleInput.value) {
            formData.set('horario_atencion', scheduleInput.value);
        }

        return formData;
    }
};

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    ServicesManager.init();
});

// Funciones globales para mantener compatibilidad con onclick en HTML
window.editarServicio = (id) => ServicesManager.editarServicio(id);
window.eliminarServicio = (id) => ServicesManager.eliminarServicio(id);
