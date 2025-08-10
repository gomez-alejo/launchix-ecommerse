// Función para mostrar secciones - ahora accesible globalmente
window.showSection = function(sectionId) {
    // Hide all sections
    const sections = document.querySelectorAll('.section-content');
    sections.forEach(section => {
        section.classList.remove('active');
    });
    
    // Show selected section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }
    
    // Update menu active state
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach(item => {
        item.classList.remove('active');
    });
    
    // Find and activate the clicked menu item
    const clickedItem = event?.target?.closest('.menu-item');
    if (clickedItem) {
        clickedItem.classList.add('active');
    }
};

// Image upload functionality for services
function setupImageUpload(dropzoneId, inputId, previewId) {
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
        handleFiles(files, preview, inputId);
    });
    
    input.addEventListener('change', (e) => {
        handleFiles(e.target.files, preview, inputId);
    });
}

function handleFiles(files, preview, inputId) {
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
                    updateFileInput(input, file);
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
}

function updateFileInput(input, fileToRemove) {
    const dataTransfer = new DataTransfer();
    Array.from(input.files).forEach(file => {
        if (file !== fileToRemove) {
            dataTransfer.items.add(file);
        }
    });
    input.files = dataTransfer.files;
}

// Función para validar formulario de servicio
function validateServiceForm(formData) {
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
}

// Función para mostrar mensajes de error
function showErrors(errors) {
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
}

// Función para mostrar loading
function showLoading(button) {
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Guardando...
    `;
}

// Función para ocultar loading
function hideLoading(button, originalText = 'Publicar Servicio') {
    button.disabled = false;
    button.innerHTML = originalText;
}

// Función principal para guardar servicio en Laravel
async function saveService(formData) {
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
}

// Función para limpiar formulario
function clearServiceForm() {
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
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    console.log('Script de servicios cargado correctamente');
    
    // Setup image upload functionality para servicios
    setupImageUpload('service-main-dropzone', 'service-main-image', 'service-main-preview');
    setupImageUpload('service-gallery-dropzone', 'service-gallery-images', 'service-gallery-preview');
    
    // Form submission handler para servicios
    const servicioForm = document.getElementById('servicio-form');
    if (servicioForm) {
        servicioForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitButton = e.target.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            
            // Crear FormData con todos los campos del formulario
            const formData = new FormData();
            
            // Obtener todos los campos del formulario
            const inputs = servicioForm.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                if (input.type === 'file') {
                    // Manejar archivos
                    if (input.files.length > 0) {
                        if (input.multiple) {
                            // Para múltiples archivos (galería)
                            Array.from(input.files).forEach((file, index) => {
                                formData.append(`${input.name || input.id}[]`, file);
                            });
                        } else {
                            // Para un solo archivo (imagen principal)
                            formData.append(input.name || input.id, input.files[0]);
                        }
                    }
                } else if (input.value) {
                    // Para campos de texto, select, etc.
                    formData.append(input.name || input.id, input.value);
                }
            });
            
            // Mapear los IDs a nombres esperados por Laravel
            const fieldMapping = {
                'service-main-image': 'imagen_principal',
                'service-gallery-images': 'galeria_imagenes'
            };
            
            // Renombrar campos si es necesario
            for (const [oldName, newName] of Object.entries(fieldMapping)) {
                if (formData.has(oldName)) {
                    const values = formData.getAll(oldName);
                    formData.delete(oldName);
                    values.forEach(value => formData.append(newName, value));
                }
            }
            
            // Asignar nombres correctos a los campos principales
            const serviceNameInput = servicioForm.querySelector('input[type="text"]');
            if (serviceNameInput && serviceNameInput.value) {
                formData.set('nombre_servicio', serviceNameInput.value);
            }
            
            const categorySelect = servicioForm.querySelector('select');
            if (categorySelect && categorySelect.value) {
                formData.set('categoria', categorySelect.value);
            }
            
            const descriptionTextarea = servicioForm.querySelector('textarea');
            if (descriptionTextarea && descriptionTextarea.value) {
                formData.set('descripcion', descriptionTextarea.value);
            }
            
            const addressInput = servicioForm.querySelector('input[type="text"]:nth-of-type(2)');
            if (addressInput && addressInput.value) {
                formData.set('direccion', addressInput.value);
            }
            
            const phoneInput = servicioForm.querySelector('input[type="tel"]');
            if (phoneInput && phoneInput.value) {
                formData.set('telefono', phoneInput.value);
            }
            
            const priceInput = servicioForm.querySelector('input[type="number"]');
            if (priceInput && priceInput.value) {
                formData.set('precio_base', priceInput.value);
            }
            
            const scheduleInput = servicioForm.querySelector('input[placeholder*="Lun-Vie"]');
            if (scheduleInput && scheduleInput.value) {
                formData.set('horario_atencion', scheduleInput.value);
            }
            
            // Validar formulario
            const errors = validateServiceForm(formData);
            if (!showErrors(errors)) {
                return;
            }
            
            // Mostrar loading
            showLoading(submitButton);
            
            try {
                // Log detallado antes de enviar
                console.log('=== INICIO DEBUG SERVICIO ===');
                console.log('FormData entries:', Array.from(formData.entries()));
                console.log('URL objetivo:', '/servicios');
                
                // Verificar CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    showErrors(['Error: No se encontró el token CSRF. Asegúrate de tener <meta name="csrf-token" content="{{ csrf_token() }}"> en tu HTML']);
                    return;
                }
                console.log('CSRF Token encontrado:', csrfToken.getAttribute('content').substring(0, 10) + '...');
                
                // Guardar servicio
                const result = await saveService(formData);
                console.log('Resultado final:', result);
                console.log('=== FIN DEBUG SERVICIO ===');
                
                if (result.success) {
                    // Mostrar mensaje de éxito
                    alert(result.message);
                    
                    // Limpiar formulario
                    clearServiceForm();
                    
                    // Regresar a la vista de servicios
                    if (window.showSection) {
                        window.showSection('servicios');
                    }
                } else {
                    // Mostrar errores del servidor
                    console.error('Errores del servidor:', result.errors);
                    showErrors(result.errors);
                }
            } catch (error) {
                console.error('Error al procesar:', error);
                showErrors([
                    'Error inesperado al procesar la solicitud',
                    `Tipo: ${error.name}`,
                    `Mensaje: ${error.message}`,
                    'Revisa la consola del navegador para más detalles'
                ]);
            } finally {
                // Ocultar loading
                hideLoading(submitButton, originalButtonText);
            }
        });
    }
});