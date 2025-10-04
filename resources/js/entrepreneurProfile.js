// entrepreneurProfile.js - CON LOGGING DETALLADO PARA DEBUGGING
console.log('🟢 [INIT] Cargando entrepreneurProfile.js');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🟢 [DOM] DOM Content Loaded - Inicializando perfil');
    
    // ============================================
    // VARIABLES Y ELEMENTOS DEL DOM
    // ============================================
    
    const profileForm = document.getElementById('profile-form');
    const avatarInput = document.getElementById('avatar-input');
    const avatarPreview = document.getElementById('avatar-preview');
    const deleteAvatarBtn = document.getElementById('delete-avatar-btn');
    const submitBtn = document.getElementById('submit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const charCounter = document.getElementById('char-counter');
    const descriptionTextarea = document.getElementById('description');
    
    // Verificar elementos
    console.log('🔍 [DOM] Elementos encontrados:', {
        profileForm: !!profileForm,
        avatarInput: !!avatarInput,
        avatarPreview: !!avatarPreview,
        deleteAvatarBtn: !!deleteAvatarBtn,
        submitBtn: !!submitBtn,
        cancelBtn: !!cancelBtn,
        charCounter: !!charCounter,
        descriptionTextarea: !!descriptionTextarea
    });
    
    // Datos originales del perfil
    let originalData = {};
    let currentAvatarUrl = '';
    let hasCustomAvatar = false;

    // ============================================
    // CARGAR DATOS DEL PERFIL
    // ============================================
    
    window.loadEntrepreneurProfile = function() {
        console.log('🔵 [LOAD] Iniciando carga de datos del perfil');
        loadProfileData();
    };
    
    function loadProfileData() {
        console.log('🔵 [API] GET /entrepreneur/profile/data - Iniciando petición');
        showLoading(true);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        console.log('🔍 [CSRF] Token encontrado:', !!csrfToken);
        
        if (!csrfToken) {
            console.error('❌ [CSRF] No se encontró el meta tag csrf-token');
            showAlert('Error: Token CSRF no encontrado', 'error');
            showLoading(false);
            return;
        }
        
        fetch('/entrepreneur/profile/data', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('📥 [API] Respuesta recibida:', {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok,
                headers: Object.fromEntries(response.headers.entries())
            });
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('❌ [API] Error en respuesta:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('📦 [API] Datos recibidos:', data);
            
            if (data.success) {
                console.log('✅ [DATA] Datos del perfil cargados correctamente');
                populateProfileData(data.data);
                originalData = { ...data.data };
            } else {
                console.error('❌ [DATA] La respuesta no contiene success:true:', data);
                showAlert('Error al cargar los datos del perfil', 'error');
            }
        })
        .catch(error => {
            console.error('❌ [API] Error en la petición:', {
                message: error.message,
                stack: error.stack
            });
            showAlert('Error de conexión: ' + error.message, 'error');
        })
        .finally(() => {
            showLoading(false);
            console.log('🔵 [LOAD] Carga de datos finalizada');
        });
    }
    
    function populateProfileData(data) {
        console.log('🔵 [POPULATE] Rellenando datos en el DOM:', data);
        
        try {
            // Header
            const profileName = document.getElementById('profile-name');
            const profileEmail = document.getElementById('profile-email');
            const profileCity = document.getElementById('profile-city');
            const profileRegistered = document.getElementById('profile-registered');
            const emailVerifiedBadge = document.getElementById('email-verified-badge');
            
            if (profileName) profileName.textContent = data.full_name;
            if (profileEmail) profileEmail.textContent = data.email;
            if (profileCity) {
                const citySpan = profileCity.querySelector('span');
                if (citySpan) citySpan.textContent = data.city || 'No especificada';
            }
            if (profileRegistered) {
                const regSpan = profileRegistered.querySelector('span');
                if (regSpan) regSpan.textContent = `Miembro desde ${data.created_at}`;
            }
            
            // Badge de email verificado
            if (data.email_verified && emailVerifiedBadge) {
                emailVerifiedBadge.classList.remove('hidden');
            }
            
            // Avatar
            currentAvatarUrl = data.avatar;
            if (avatarPreview) {
                avatarPreview.src = currentAvatarUrl;
                console.log('🖼️ [AVATAR] URL del avatar:', currentAvatarUrl);
            }
            hasCustomAvatar = !currentAvatarUrl.includes('ui-avatars.com');
            
            // Mostrar botón de eliminar si tiene foto personalizada
            if (hasCustomAvatar && deleteAvatarBtn) {
                deleteAvatarBtn.classList.remove('hidden');
            }
            
            // Sidebar
            const sidebarEmail = document.getElementById('sidebar-email');
            const sidebarPhone = document.getElementById('sidebar-phone');
            const sidebarAddress = document.getElementById('sidebar-address');
            const sidebarDescription = document.getElementById('sidebar-description');
            
            if (sidebarEmail) sidebarEmail.textContent = data.email;
            if (sidebarPhone) sidebarPhone.textContent = data.phone || 'No especificado';
            if (sidebarAddress) sidebarAddress.textContent = data.address || 'No especificada';
            if (sidebarDescription) sidebarDescription.textContent = data.description || 'Sin descripción';
            
            // Formulario
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const email = document.getElementById('email');
            const phone = document.getElementById('phone');
            const city = document.getElementById('city');
            const address = document.getElementById('address');
            const description = document.getElementById('description');
            
            if (firstName) firstName.value = data.first_name;
            if (lastName) lastName.value = data.last_name;
            if (email) email.value = data.email;
            if (phone) phone.value = data.phone || '';
            if (city) city.value = data.city || '';
            if (address) address.value = data.address || '';
            if (description) description.value = data.description || '';
            
            // Actualizar contador de caracteres
            updateCharCounter();
            
            console.log('✅ [POPULATE] Datos poblados correctamente');
        } catch (error) {
            console.error('❌ [POPULATE] Error al poblar datos:', error);
        }
    }

    // ============================================
    // ACTUALIZAR PERFIL
    // ============================================
    
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('🔵 [FORM] Formulario enviado');
            
            // Limpiar errores previos
            clearErrors();
            
            const formData = new FormData(profileForm);
            
            console.log('📤 [FORM] Datos del formulario:', {
                first_name: formData.get('first_name'),
                last_name: formData.get('last_name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                city: formData.get('city'),
                address: formData.get('address'),
                description: formData.get('description')
            });
            
            // Deshabilitar botón de submit
            setSubmitButtonState(true);
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('❌ [CSRF] Token no encontrado');
                showAlert('Error: Token CSRF no encontrado', 'error');
                setSubmitButtonState(false);
                return;
            }
            
            console.log('🔵 [API] POST /entrepreneur/profile/update - Enviando actualización');
            
            fetch('/entrepreneur/profile/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('📥 [API] Respuesta recibida:', {
                    status: response.status,
                    statusText: response.statusText,
                    ok: response.ok
                });
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('❌ [API] Error en respuesta:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('📦 [API] Respuesta procesada:', data);
                
                if (data.success) {
                    console.log('✅ [UPDATE] Perfil actualizado correctamente');
                    showAlert('Perfil actualizado correctamente', 'success');
                    populateProfileData(data.data);
                    originalData = { ...data.data };
                } else {
                    console.error('❌ [UPDATE] Actualización fallida:', data);
                    if (data.errors) {
                        console.log('📋 [VALIDATION] Errores de validación:', data.errors);
                        displayErrors(data.errors);
                    } else {
                        showAlert(data.message || 'Error al actualizar el perfil', 'error');
                    }
                }
            })
            .catch(error => {
                console.error('❌ [API] Error en la petición:', {
                    message: error.message,
                    stack: error.stack
                });
                showAlert('Error de conexión: ' + error.message, 'error');
            })
            .finally(() => {
                setSubmitButtonState(false);
                console.log('🔵 [UPDATE] Actualización finalizada');
            });
        });
    } else {
        console.error('❌ [DOM] No se encontró el formulario de perfil');
    }

    // ============================================
    // SUBIR AVATAR
    // ============================================
    
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            console.log('🔵 [AVATAR] Archivo seleccionado');
            const file = e.target.files[0];
            
            if (!file) {
                console.log('⚠️ [AVATAR] No se seleccionó ningún archivo');
                return;
            }
            
            console.log('📄 [AVATAR] Detalles del archivo:', {
                name: file.name,
                type: file.type,
                size: file.size,
                sizeInMB: (file.size / (1024 * 1024)).toFixed(2) + ' MB'
            });
            
            // Validar tipo de archivo
            if (!file.type.match('image.*')) {
                console.error('❌ [AVATAR] Tipo de archivo no válido:', file.type);
                showAlert('Por favor selecciona una imagen válida', 'error');
                return;
            }
            
            // Validar tamaño (máximo 2MB)
            if (file.size > 2048 * 1024) {
                console.error('❌ [AVATAR] Archivo muy grande:', file.size);
                showAlert('La imagen no debe superar los 2MB', 'error');
                return;
            }
            
            // Vista previa inmediata
            const reader = new FileReader();
            reader.onload = function(e) {
                if (avatarPreview) {
                    avatarPreview.src = e.target.result;
                    console.log('🖼️ [AVATAR] Vista previa cargada');
                }
            };
            reader.readAsDataURL(file);
            
            // Subir imagen
            uploadAvatar(file);
        });
    } else {
        console.error('❌ [DOM] No se encontró el input de avatar');
    }
    
    function uploadAvatar(file) {
        console.log('🔵 [AVATAR] Iniciando subida de avatar');
        const formData = new FormData();
        formData.append('avatar', file);
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('❌ [CSRF] Token no encontrado');
            showAlert('Error: Token CSRF no encontrado', 'error');
            if (avatarPreview) avatarPreview.src = currentAvatarUrl;
            return;
        }
        
        showLoading(true);
        
        console.log('🔵 [API] POST /entrepreneur/profile/avatar - Subiendo imagen');
        
        fetch('/entrepreneur/profile/avatar', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json'
            },
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            console.log('📥 [API] Respuesta recibida:', {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok
            });
            
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('❌ [API] Error en respuesta:', text);
                    throw new Error(`HTTP ${response.status}: ${text}`);
                });
            }
            
            return response.json();
        })
        .then(data => {
            console.log('📦 [API] Respuesta procesada:', data);
            
            if (data.success) {
                console.log('✅ [AVATAR] Avatar actualizado correctamente');
                showAlert('Foto de perfil actualizada', 'success');
                currentAvatarUrl = data.avatar_url;
                if (avatarPreview) avatarPreview.src = data.avatar_url;
                hasCustomAvatar = true;
                if (deleteAvatarBtn) deleteAvatarBtn.classList.remove('hidden');
            } else {
                console.error('❌ [AVATAR] Error al subir:', data);
                showAlert(data.message || 'Error al subir la imagen', 'error');
                if (avatarPreview) avatarPreview.src = currentAvatarUrl;
            }
        })
        .catch(error => {
            console.error('❌ [API] Error en la petición:', {
                message: error.message,
                stack: error.stack
            });
            showAlert('Error de conexión: ' + error.message, 'error');
            if (avatarPreview) avatarPreview.src = currentAvatarUrl;
        })
        .finally(() => {
            showLoading(false);
            if (avatarInput) avatarInput.value = '';
            console.log('🔵 [AVATAR] Subida finalizada');
        });
    }

    // ============================================
    // ELIMINAR AVATAR
    // ============================================
    
    if (deleteAvatarBtn) {
        deleteAvatarBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            console.log('🔵 [AVATAR] Intentando eliminar avatar');
            
            if (!confirm('¿Estás seguro de que deseas eliminar tu foto de perfil?')) {
                console.log('⚠️ [AVATAR] Eliminación cancelada por el usuario');
                return;
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('❌ [CSRF] Token no encontrado');
                showAlert('Error: Token CSRF no encontrado', 'error');
                return;
            }
            
            showLoading(true);
            
            console.log('🔵 [API] DELETE /entrepreneur/profile/avatar - Eliminando imagen');
            
            fetch('/entrepreneur/profile/avatar', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.content,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                console.log('📥 [API] Respuesta recibida:', {
                    status: response.status,
                    statusText: response.statusText,
                    ok: response.ok
                });
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.error('❌ [API] Error en respuesta:', text);
                        throw new Error(`HTTP ${response.status}: ${text}`);
                    });
                }
                
                return response.json();
            })
            .then(data => {
                console.log('📦 [API] Respuesta procesada:', data);
                
                if (data.success) {
                    console.log('✅ [AVATAR] Avatar eliminado correctamente');
                    showAlert('Foto de perfil eliminada', 'success');
                    loadProfileData(); // Recargar para obtener el avatar por defecto
                    if (deleteAvatarBtn) deleteAvatarBtn.classList.add('hidden');
                    hasCustomAvatar = false;
                } else {
                    console.error('❌ [AVATAR] Error al eliminar:', data);
                    showAlert(data.message || 'Error al eliminar la foto', 'error');
                }
            })
            .catch(error => {
                console.error('❌ [API] Error en la petición:', {
                    message: error.message,
                    stack: error.stack
                });
                showAlert('Error de conexión: ' + error.message, 'error');
            })
            .finally(() => {
                showLoading(false);
                console.log('🔵 [AVATAR] Eliminación finalizada');
            });
        });
    } else {
        console.error('❌ [DOM] No se encontró el botón de eliminar avatar');
    }

    // ============================================
    // CANCELAR CAMBIOS
    // ============================================
    
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            console.log('🔵 [CANCEL] Botón cancelar clickeado');
            if (hasFormChanged()) {
                if (confirm('¿Deseas descartar los cambios realizados?')) {
                    console.log('✅ [CANCEL] Cambios descartados');
                    resetForm();
                } else {
                    console.log('⚠️ [CANCEL] Cancelación cancelada');
                }
            } else {
                console.log('⚠️ [CANCEL] No hay cambios para descartar');
            }
        });
    } else {
        console.error('❌ [DOM] No se encontró el botón cancelar');
    }
    
    function resetForm() {
        console.log('🔵 [RESET] Reseteando formulario');
        populateProfileData(originalData);
        clearErrors();
    }
    
    function hasFormChanged() {
        const currentData = {
            first_name: document.getElementById('first_name')?.value || '',
            last_name: document.getElementById('last_name')?.value || '',
            email: document.getElementById('email')?.value || '',
            phone: document.getElementById('phone')?.value || '',
            city: document.getElementById('city')?.value || '',
            address: document.getElementById('address')?.value || '',
            description: document.getElementById('description')?.value || ''
        };
        
        const originalFormData = {
            first_name: originalData.first_name || '',
            last_name: originalData.last_name || '',
            email: originalData.email || '',
            phone: originalData.phone || '',
            city: originalData.city || '',
            address: originalData.address || '',
            description: originalData.description || ''
        };
        
        const changed = JSON.stringify(currentData) !== JSON.stringify(originalFormData);
        console.log('🔍 [CHANGED] Formulario modificado:', changed);
        return changed;
    }
    
    // Exponer función globalmente
    window.hasEntrepreneurFormChanged = hasFormChanged;

    // ============================================
    // CONTADOR DE CARACTERES
    // ============================================
    
    if (descriptionTextarea) {
        descriptionTextarea.addEventListener('input', updateCharCounter);
    }
    
    function updateCharCounter() {
        if (!descriptionTextarea || !charCounter) return;
        
        const length = descriptionTextarea.value.length;
        charCounter.textContent = `${length} / 500`;
        
        if (length >= 450) {
            charCounter.classList.add('text-warning');
        } else {
            charCounter.classList.remove('text-warning');
        }
    }

    // ============================================
    // UTILIDADES UI
    // ============================================
    
    function showAlert(message, type = 'info') {
        console.log(`🔔 [ALERT] ${type.toUpperCase()}: ${message}`);
        const alertContainer = document.getElementById('alert-container');
        
        if (!alertContainer) {
            console.error('❌ [ALERT] No se encontró el contenedor de alertas');
            return;
        }
        
        const colors = {
            success: 'bg-green-light text-green border-green',
            error: 'bg-red-50 text-error border-error',
            warning: 'bg-orange-light text-orange border-orange',
            info: 'bg-blue-50 text-info border-info'
        };
        
        const icons = {
            success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
            warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
            info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
        };
        
        const alert = document.createElement('div');
        alert.className = `${colors[type]} border-l-4 p-4 rounded-lg flex items-start animate-fade-in`;
        alert.innerHTML = `
            <svg class="w-5 h-5 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${icons[type]}
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <button onclick="this.parentElement.remove()" class="ml-3 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        alertContainer.innerHTML = '';
        alertContainer.appendChild(alert);
        
        // Auto remover después de 5 segundos
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.3s ease';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    }
    
    function showLoading(show) {
        if (show) {
            if (!document.getElementById('loading-overlay')) {
                console.log('🔄 [LOADING] Mostrando overlay de carga');
                const overlay = document.createElement('div');
                overlay.id = 'loading-overlay';
                overlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
                overlay.innerHTML = `
                    <div class="bg-white rounded-lg p-6 flex flex-col items-center">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
                        <p class="mt-4 text-dark font-medium">Cargando...</p>
                    </div>
                `;
                document.body.appendChild(overlay);
            }
        } else {
            console.log('✅ [LOADING] Ocultando overlay de carga');
            const overlay = document.getElementById('loading-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
    }
    
    function setSubmitButtonState(loading) {
        if (!submitBtn) return;
        
        const submitIcon = document.getElementById('submit-icon');
        const submitText = document.getElementById('submit-text');
        
        if (loading) {
            console.log('🔄 [SUBMIT] Deshabilitando botón');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            if (submitIcon) {
                submitIcon.innerHTML = '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
                submitIcon.classList.add('animate-spin');
            }
            if (submitText) submitText.textContent = 'Actualizando...';
        } else {
            console.log('✅ [SUBMIT] Habilitando botón');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
            if (submitIcon) {
                submitIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                submitIcon.classList.remove('animate-spin');
            }
            if (submitText) submitText.textContent = 'Actualizar Perfil';
        }
    }
    
    function clearErrors() {
        console.log('🧹 [ERRORS] Limpiando errores');
        document.querySelectorAll('.error-message').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        
        document.querySelectorAll('input, textarea').forEach(el => {
            el.classList.remove('border-error', 'focus:ring-error');
        });
    }
    
    function displayErrors(errors) {
        console.log('❌ [ERRORS] Mostrando errores de validación:', errors);
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            if (input) {
                input.classList.add('border-error', 'focus:ring-error');
                const errorSpan = input.parentElement.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.textContent = errors[field][0];
                    errorSpan.classList.remove('hidden');
                }
                console.log(`  - ${field}: ${errors[field][0]}`);
            }
        });
        
        // Scroll al primer error
        const firstError = document.querySelector('.border-error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // ============================================
    // VALIDACIÓN EN TIEMPO REAL
    // ============================================
    
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (email && !emailRegex.test(email)) {
                console.log('⚠️ [VALIDATION] Email no válido:', email);
                this.classList.add('border-warning');
                const errorSpan = this.parentElement.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.textContent = 'Formato de email no válido';
                    errorSpan.classList.remove('hidden');
                    errorSpan.classList.add('text-warning');
                }
            } else {
                this.classList.remove('border-warning');
                const errorSpan = this.parentElement.querySelector('.error-message');
                if (errorSpan) {
                    errorSpan.textContent = '';
                    errorSpan.classList.add('hidden');
                    errorSpan.classList.remove('text-warning');
                }
            }
        });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9\s\(\)\-\+]/g, '');
        });
    }

    // ============================================
    // ATAJOS DE TECLADO
    // ============================================
    
    document.addEventListener('keydown', function(e) {
        const modal = document.getElementById('entrepreneur-profile-modal');
        if (!modal || modal.classList.contains('hidden')) return;
        
        // Ctrl/Cmd + S para guardar
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            console.log('⌨️ [SHORTCUT] Ctrl+S presionado - Guardando');
            if (profileForm) profileForm.dispatchEvent(new Event('submit'));
        }
    });

    // ============================================
    // INICIALIZACIÓN COMPLETA
    // ============================================
    
    console.log('✅ [INIT] Perfil de emprendedor inicializado correctamente');
    console.log('📊 [STATUS] Estado del módulo:', {
        moduloCargado: true,
        elementosEncontrados: {
            form: !!profileForm,
            avatar: !!avatarInput,
            submit: !!submitBtn
        },
        funcionesGlobales: {
            loadProfile: typeof window.loadEntrepreneurProfile === 'function',
            hasChanged: typeof window.hasEntrepreneurFormChanged === 'function'
        }
    });
});