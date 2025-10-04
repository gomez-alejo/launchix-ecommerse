/**
 * Products Manager
 * Maneja toda la funcionalidad relacionada con productos:
 * - Subida de imágenes
 * - Validación de formularios
 * - CRUD de productos
 * - Visualización de productos
 */
window.ProductsManager = {

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
            if (e.target.files && e.target.files.length > 0) {
                this.handleFiles(e.target.files, preview, inputId);
            }
        });
    },

    /**
     * Manejar archivos seleccionados
     */
    /**
 * Manejar archivos seleccionados
 */
handleFiles(files, preview, inputId) {
    if (!files || files.length === 0) return;
    
    preview.innerHTML = '';

    Array.from(files).forEach((file, index) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'image-preview w-full h-32 object-cover rounded';

                const container = document.createElement('div');
                container.className = 'relative';
                container.setAttribute('data-file-index', index);

                const deleteBtn = document.createElement('button');
                deleteBtn.innerHTML = '×';
                deleteBtn.className = 'absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm hover:bg-red-600';
                deleteBtn.type = 'button';
                deleteBtn.onclick = () => {
                    container.remove();
                };

                container.appendChild(img);
                container.appendChild(deleteBtn);
                preview.appendChild(container);
            };
            reader.readAsDataURL(file);
        }
    });
},

    /**
     * Actualizar input de archivos removiendo un archivo específico
     */
    // updateFileInput(input, fileToRemove) {
    //     const dataTransfer = new DataTransfer();
    //     Array.from(input.files).forEach(file => {
    //         if (file !== fileToRemove) {
    //             dataTransfer.items.add(file);
    //         }
    //     });
    //     input.files = dataTransfer.files;
    // },

    // ========== VALIDACIÓN Y MANEJO DE FORMULARIOS ==========

    /**
     * Validar formulario de producto
     */
    validateProductForm(formData) {
        const errors = [];

        if (!formData.get('name')) {
            errors.push('El nombre del producto es obligatorio');
        }

        if (!formData.get('category')) {
            errors.push('La categoría es obligatoria');
        }

        if (!formData.get('description')) {
            errors.push('La descripción es obligatoria');
        }

        if (!formData.get('price')) {
        errors.push('El precio es obligatorio');
        } else {
            const price = parseFloat(formData.get('price'));
            if (isNaN(price) || price < 0) {
                errors.push('El precio debe ser un número válido mayor o igual a 0');
            } else if (price > 99999999.99) {
                errors.push('El precio no puede ser mayor a $99,999,999.99');
            }
        } 

        if (!formData.get('stock')) {
            errors.push('El stock es obligatorio');
        } else if (isNaN(parseInt(formData.get('stock'))) || parseInt(formData.get('stock')) < 0) {
            errors.push('El stock debe ser un número entero mayor o igual a 0');
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

            const form = document.getElementById('producto-form');
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
    hideLoading(button, originalText = 'Publicar Producto') {
        button.disabled = false;
        button.innerHTML = originalText;
    },

    /**
     * Limpiar formulario de producto
     */
    clearProductForm() {
        const form = document.getElementById('producto-form');
        form.reset();

        // Limpiar previsualizaciones de imágenes
        const mainPreview = document.getElementById('product-main-preview');
        const galleryPreview = document.getElementById('product-gallery-preview');

        if (mainPreview) mainPreview.innerHTML = '';
        if (galleryPreview) galleryPreview.innerHTML = '';

        // Remover mensajes de error
        const existingErrors = document.querySelectorAll('.error-message');
        existingErrors.forEach(error => error.remove());

        // Resetear título y botón
        const formTitle = document.getElementById('form-title');
        const submitButton = document.getElementById('submit-button');

        if (formTitle) formTitle.textContent = 'Publicar Producto';
        if (submitButton) submitButton.textContent = 'Publicar Producto';

        // Remover método PUT si existe
        const methodInput = form.querySelector('input[name="_method"]');
        if (methodInput) {
            methodInput.remove();
        }

        // Resetear acción del formulario
        form.action = '/productos';
    },

    // ========== COMUNICACIÓN CON EL SERVIDOR ==========

    /**
     * Guardar producto en Laravel
     */
    async saveProduct(formData, method = 'POST', productId = null) {
        const url = productId ? `/productos/${productId}` : '/productos';
        const fetchMethod = method === 'PUT' ? 'POST' : method;

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

            // Agregar _method para PUT si es necesario
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }

            const response = await fetch(url, {
                method: fetchMethod,
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
                    message: result.message || 'Producto guardado exitosamente'
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

    // ========== VISUALIZACIÓN Y CARGA DE PRODUCTOS ==========

    /**
     * Cargar productos desde el servidor
     */
    async loadProducts() {
        try {
            console.log('Cargando productos...');

            // Mostrar loading
            const loadingElement = document.getElementById('productos-loading');
            const productosContainer = document.querySelector('#productos .grid');

            if (loadingElement) {
                loadingElement.classList.remove('hidden');
            }
            if (productosContainer) {
                productosContainer.innerHTML = '';
            }

            const response = await fetch('/productos', {
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
                this.displayProducts(result.data);
            } else {
                console.error('Error al cargar productos:', result);
                this.showProductsError('Error al cargar los productos');
            }
        } catch (error) {
            console.error('Error al cargar productos:', error);

            // Ocultar loading en caso de error
            const loadingElement = document.getElementById('productos-loading');
            if (loadingElement) {
                loadingElement.classList.add('hidden');
            }

            this.showProductsError('Error de conexión al cargar productos');
        }
    },

    /**
     * Mostrar productos en la vista
     */
    displayProducts(products) {
        const productosContainer = document.querySelector('#productos .grid');

        if (!productosContainer) {
            console.warn('Contenedor de productos no encontrado');
            return;
        }

        // Limpiar contenido anterior
        productosContainer.innerHTML = '';

        if (products.length === 0) {
            productosContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No tienes productos publicados</h3>
                    <p class="text-gray-500 mb-6">Comienza publicando tu primer producto</p>
                    <button class="btn-primary text-white px-6 py-2 rounded-lg" onclick="ProductsManager.clearProductForm(); showSection('publicar-producto')">
                        Publicar mi primer producto
                    </button>
                </div>
            `;
            return;
        }

        // Crear cards para cada producto
        products.forEach(product => {
            const productCard = this.createProductCard(product);
            productosContainer.appendChild(productCard);
        });
    },

    /**
     * Crear una card de producto
     */
    createProductCard(product) {
        const card = document.createElement('div');
        card.className = 'product-card bg-white border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow';

        const precioTexto = product.price
            ? `$${parseFloat(product.price).toLocaleString(undefined, { minimumFractionDigits: 2 })}`
            : 'Precio no disponible';

        card.innerHTML = `
            <div class="h-48 bg-gray-200 flex items-center justify-center overflow-hidden">
                ${product.main_image
                    ? `<img src="${product.main_image}" alt="${product.name}" class="w-full h-full object-cover">`
                    : `<svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                    </svg>`
                }
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="font-semibold text-gray-800 truncate">${product.name}</h3>
                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">${typeof product.category === 'object' ? product.category.name : product.category}</span>
                </div>
                <p class="text-gray-600 text-sm mb-2 line-clamp-2">${product.description}</p>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-primary font-bold">${precioTexto}</span>
                    <span class="text-sm text-gray-500">Stock: ${product.stock}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs bg-gray-100 px-2 py-1 rounded">${new Date(product.created_at).toLocaleDateString()}</span>
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-800 text-sm font-medium btn-editar"
                                data-product-id="${product.id}">
                            Editar
                        </button>
                        <button class="text-red-600 hover:text-red-800 text-sm font-medium btn-eliminar"
                                data-product-id="${product.id}"
                                data-product-name="${product.name}">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        `;

        return card;
    },

    /**
     * Mostrar errores en la vista de productos
     */
    showProductsError(mensaje) {
        const productosContainer = document.querySelector('#productos .grid');
        if (productosContainer) {
            productosContainer.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">${mensaje}</p>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg" onclick="ProductsManager.loadProducts()">
                        Reintentar
                    </button>
                </div>
            `;
        }
    },

    // ========== OPERACIONES CRUD ==========

    /**
     * Eliminar producto
     */
    async deleteProduct(id, name) {
        // Crear modal de confirmación personalizado
        const confirmed = await window.showConfirmDialog({
            title: '¿Eliminar producto?',
            message: `¿Estás seguro de que deseas eliminar el producto "${name}"? Esta acción no se puede deshacer y se eliminarán todas las imágenes asociadas.`,
            confirmText: 'Sí, eliminar',
            cancelText: 'Cancelar',
            type: 'danger'
        });

        if (!confirmed) {
            return;
        }

        // Mostrar loading en el botón
        const deleteButtons = document.querySelectorAll(`button[data-product-id="${id}"]`);
        deleteButtons.forEach(btn => {
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-3 h-3 inline" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        });

        try {
            const response = await fetch(`/productos/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();

            if (result.success) {
                window.showSuccessMessage('Producto eliminado exitosamente');
                // Recargar la lista de productos
                this.loadProducts();
            } else {
                window.showErrorMessage('Error al eliminar el producto: ' + (result.message || 'Error desconocido'));
            }
        } catch (error) {
            console.error('Error al eliminar producto:', error);
            window.showErrorMessage('Error de conexión al eliminar el producto');
        } finally {
            // Restaurar botones
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.innerHTML = 'Eliminar';
            });
        }
    },

    /**
     * Cargar datos de un producto específico para edición
     */
    async loadProductData(id) {
        try {
            // Mostrar loading mientras carga los datos
            this.showEditLoading();

            // Obtener datos del producto
            const productData = await this.getProductById(id);

            if (productData.success) {
                // Mostrar modal de edición con los datos
                this.showEditModal(productData.data);
            } else {
                window.showErrorMessage('Error al cargar los datos del producto');
                console.error('Error:', productData);
            }
        } catch (error) {
            console.error('Error al cargar producto para editar:', error);
            window.showErrorMessage('Error de conexión al cargar el producto');
        }
    },

    /**
     * Obtener datos de un producto específico
     */
    async getProductById(id) {
        try {
            const response = await fetch(`/productos/${id}`, {
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
                    message: result.message || 'Error al obtener el producto'
                };
            }
        } catch (error) {
            console.error('Error en getProductById:', error);
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
                <p class="text-gray-600">Cargando datos del producto...</p>
            </div>
        `;
        document.body.appendChild(loadingModal);
    },

    /**
     * Mostrar modal de edición con los datos del producto
     */
    showEditModal(product) {
        // Remover modal de loading si existe
        const loadingModal = document.getElementById('edit-loading-modal');
        if (loadingModal) {
            document.body.removeChild(loadingModal);
        }

        // Crear modal de edición
        const editModal = document.createElement('div');
        editModal.id = 'edit-product-modal';
        editModal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4';

        editModal.innerHTML = `
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-800">Editar Producto</h2>
                    <button id="close-edit-modal" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <form id="edit-product-form" class="p-6">
                    <input type="hidden" id="edit-product-id" value="${product.id}">

                    <!-- Grid de dos columnas -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        <!-- Columna izquierda: Información básica -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Información Básica</h3>

                            <!-- Nombre del producto -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                                <input type="text" id="edit-name" value="${product.name || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Ej: Camiseta de algodón" required>
                            </div>

                            <!-- Categoría -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                                <select id="edit-category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="ropa" ${product.category === 'ropa' ? 'selected' : ''}>Ropa</option>
                                    <option value="calzado" ${product.category === 'calzado' ? 'selected' : ''}>Calzado</option>
                                    <option value="accesorios" ${product.category === 'accesorios' ? 'selected' : ''}>Accesorios</option>
                                    <option value="hogar" ${product.category === 'hogar' ? 'selected' : ''}>Hogar</option>
                                    <option value="electronica" ${product.category === 'electronica' ? 'selected' : ''}>Electrónica</option>
                                    <option value="deportes" ${product.category === 'deportes' ? 'selected' : ''}>Deportes</option>
                                    <option value="belleza" ${product.category === 'belleza' ? 'selected' : ''}>Belleza y Cuidado Personal</option>
                                    <option value="juguetes" ${product.category === 'juguetes' ? 'selected' : ''}>Juguetes</option>
                                    <option value="libros" ${product.category === 'libros' ? 'selected' : ''}>Libros</option>
                                    <option value="otros" ${product.category === 'otros' ? 'selected' : ''}>Otros</option>
                                </select>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                                <textarea id="edit-description" rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Describe tu producto en detalle..." required>${product.description || ''}</textarea>
                            </div>

                            <!-- Precio -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" id="edit-price" value="${product.price || ''}"
                                           class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                           placeholder="0.00" min="0" step="0.01" required>
                                </div>
                            </div>

                            <!-- Stock -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Stock *</label>
                                <input type="number" id="edit-stock" value="${product.stock || ''}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="0" min="0" required>
                            </div>
                        </div>

                        <!-- Columna derecha: Imágenes -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Imágenes del Producto</h3>

                            <!-- Imagen actual -->
                            <div>
                                <h4 class="text-md font-medium text-gray-700 mb-2">Imagen Actual</h4>
                                <div id="edit-current-images" class="grid grid-cols-1 gap-2">
                                    ${this.renderCurrentImages(product)}
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

                            <!-- Galería de imágenes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nuevas Imágenes para Galería</label>
                                <div id="edit-gallery-dropzone" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-gray-400 transition-colors cursor-pointer">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Click o arrastra para agregar imágenes a la galería</p>
                                </div>
                                <input type="file" id="edit-gallery-images" accept="image/*" class="hidden" multiple>
                                <div id="edit-gallery-preview" class="mt-2 grid grid-cols-2 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" id="cancel-edit" class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Actualizar Producto
                        </button>
                    </div>
                </form>
            </div>
        `;
        document.body.appendChild(editModal);

        // Configurar eventos del modal
        this.setupEditModalEvents(editModal, product);
    },

    /**
     * Renderizar imágenes actuales del producto
     */
    renderCurrentImages(product) {
        let imagesHtml = '';

        // Imagen principal
        if (product.main_image) {
            imagesHtml += `
                <div class="relative group">
                    <img src="${product.main_image}" alt="Imagen principal" class="w-full h-20 object-cover rounded">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity rounded flex items-center justify-center">
                        <span class="text-white text-xs">Principal</span>
                    </div>
                </div>
            `;
        }

        // Galería de imágenes
        if (product.gallery_images && product.gallery_images.length > 0) {
            product.gallery_images.forEach((image, index) => {
                imagesHtml += `
                    <div class="relative group">
                        <img src="${image}" alt="Imagen ${index + 1}" class="w-full h-20 object-cover rounded">
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
    setupEditModalEvents(modal, product) {
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
        this.setupImageUpload('edit-gallery-dropzone', 'edit-gallery-images', 'edit-gallery-preview');

        // Manejar envío del formulario
        const form = modal.querySelector('#edit-product-form');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.updateProduct(product.id, closeModal);
        });
    },

    /**
     * Actualizar producto en el servidor
     */
    async updateProduct(id, closeModalCallback) {
        const form = document.getElementById('edit-product-form');
        const submitButton = form.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.innerHTML;

        // Crear FormData
        const formData = new FormData();

        // Datos básicos
        formData.append('name', document.getElementById('edit-name').value);
        formData.append('category', document.getElementById('edit-category').value);
        formData.append('description', document.getElementById('edit-description').value);
        formData.append('price', document.getElementById('edit-price').value);
        formData.append('stock', document.getElementById('edit-stock').value);

        // Nueva imagen principal si se seleccionó
        const mainImageInput = document.getElementById('edit-main-image');
        if (mainImageInput.files[0]) {
            formData.append('main_image', mainImageInput.files[0]);
        }

        // Nuevas imágenes de galería si se seleccionaron
        const galleryImagesInput = document.getElementById('edit-gallery-images');
        if (galleryImagesInput.files.length > 0) {
            Array.from(galleryImagesInput.files).forEach(file => {
                formData.append('gallery_images[]', file);
            });
        }

        // Método HTTP para Laravel (PUT/PATCH via POST)
        formData.append('_method', 'PUT');

        // Validar datos básicos
        const errors = [];
        if (!formData.get('name')) errors.push('El nombre del producto es obligatorio');
        if (!formData.get('category')) errors.push('La categoría es obligatoria');
        if (!formData.get('description')) errors.push('La descripción es obligatoria');
        if (!formData.get('price') || isNaN(parseFloat(formData.get('price'))) || parseFloat(formData.get('price')) < 0) {
            errors.push('El precio es obligatorio y debe ser un número válido mayor o igual a 0');
        }
        if (!formData.get('stock') || isNaN(parseInt(formData.get('stock'))) || parseInt(formData.get('stock')) < 0) {
            errors.push('El stock es obligatorio y debe ser un número entero mayor o igual a 0');
        }

        if (errors.length > 0) {
            window.showErrorMessage(errors.join(', '));
            return;
        }

        // Mostrar loading
        this.showLoading(submitButton);

        try {
            const response = await fetch(`/productos/${id}`, {
                method: 'POST', // Laravel usa POST con _method=PUT
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                window.showSuccessMessage('Producto actualizado exitosamente');
                closeModalCallback();
                // Recargar lista de productos
                this.loadProducts();
            } else {
                const errorMessage = result.message || 'Error al actualizar el producto';
                window.showErrorMessage(errorMessage);
                console.error('Error del servidor:', result);
            }
        } catch (error) {
            console.error('Error al actualizar producto:', error);
            window.showErrorMessage('Error de conexión al actualizar el producto');
        } finally {
            this.hideLoading(submitButton, originalButtonText);
        }
    },

    // ========== INICIALIZACIÓN ==========

    /**
     * Inicializar el manager de productos
     */
    init() {
        console.log('Products Manager initialized successfully');

        // Setup image upload functionality para productos
        this.setupImageUpload('product-main-dropzone', 'product-main-image', 'product-main-preview');
        this.setupImageUpload('product-gallery-dropzone', 'product-gallery-images', 'product-gallery-preview');

        // Cargar productos al inicio
        this.loadProducts();

        // Form submission handler para productos
        this.setupFormHandler();
    },

    /**
     * Configurar manejador del formulario
     */
    setupFormHandler() {
        const productForm = document.getElementById('producto-form');
        if (productForm) {
            productForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const submitButton = e.target.querySelector('button[type="submit"]');
                const originalButtonText = submitButton.innerHTML;

                // Crear FormData con todos los campos del formulario
                const formData = new FormData(productForm);

                // Validar formulario
                const errors = this.validateProductForm(formData);
                if (!this.showErrors(errors)) {
                    return;
                }

                // Mostrar loading
                this.showLoading(submitButton);

                try {
                    // Log detallado antes de enviar
                    console.log('=== INICIO DEBUG PRODUCTO ===');
                    console.log('FormData entries:', Array.from(formData.entries()));

                    // Verificar CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (!csrfToken) {
                        this.showErrors(['Error: No se encontró el token CSRF. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en tu HTML']);
                        return;
                    }
                    console.log('CSRF Token encontrado:', csrfToken.getAttribute('content').substring(0, 10) + '...');

                    // Determinar si es creación o actualización
                    const methodInput = productForm.querySelector('input[name="_method"]');
                    const method = methodInput ? methodInput.value : 'POST';
                    const productIdInput = document.getElementById('product-id');
                    const productId = productIdInput ? productIdInput.value : null;

                    // Guardar producto
                    const result = await this.saveProduct(formData, method, productId);
                    console.log('Resultado final:', result);
                    console.log('=== FIN DEBUG PRODUCTO ===');

                    if (result.success) {
                        // Mostrar mensaje de éxito
                        window.showSuccessMessage(result.message);

                        // Limpiar formulario
                        this.clearProductForm();

                        // Regresar a la vista de productos
                        if (window.showSection) {
                            window.showSection('productos');
                            // Recargar los productos para mostrar los cambios
                            setTimeout(() => {
                                this.loadProducts();
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
    }
};

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    ProductsManager.init();

    // Configurar botones de edición y eliminación
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-editar')) {
            e.preventDefault();
            const productId = e.target.getAttribute('data-product-id');
            if (productId) {
                ProductsManager.loadProductData(productId);
            }
        }

        if (e.target.classList.contains('btn-eliminar')) {
            e.preventDefault();
            const productId = e.target.getAttribute('data-product-id');
            const productName = e.target.getAttribute('data-product-name');
            if (productId && productName) {
                ProductsManager.deleteProduct(productId, productName);
            }
        }
    });
});

// Funciones globales para mantener compatibilidad con onclick en HTML
window.loadProductData = (id) => ProductsManager.loadProductData(id);
window.deleteProduct = (id, name) => ProductsManager.deleteProduct(id, name);
window.resetProductForm = () => {
    ProductsManager.clearProductForm();
    if (window.showSection) {
        window.showSection('publicar-producto');
    }
};
window.cancelProductForm = () => {
    ProductsManager.clearProductForm();
    if (window.showSection) {
        window.showSection('productos');
    }
};
