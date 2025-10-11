// Configuración CSRF para Laravel
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Función para crear el overlay del modal
function createModalOverlay() {
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
    overlay.style.animation = 'fadeIn 0.3s ease-out';
    return overlay;
}

// Función para cerrar modal
function closeModal(overlay) {
    overlay.style.animation = 'fadeOut 0.3s ease-out';
    setTimeout(() => overlay.remove(), 300);
}

// Editar descripción
window.editDescription = function() {
    const overlay = createModalOverlay();
    const currentDesc = document.querySelector('.text-medium.leading-relaxed')?.textContent.trim() || '';
    
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl shadow-primary-lg max-w-2xl w-full p-6 transform" style="animation: slideUp 0.3s ease-out;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-dark">Editar Descripción</h3>
                <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-light rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="descriptionForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-medium mb-2">Descripción del negocio</label>
                    <textarea name="description" rows="6" maxlength="1000"
                        class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                        placeholder="Cuéntanos sobre tu negocio...">${currentDesc}</textarea>
                    <p class="text-xs text-light mt-1">Máximo 1000 caracteres</p>
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="this.closest('.fixed').remove()" 
                        class="px-6 py-2.5 border-2 border-gray text-medium rounded-xl hover:bg-light transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-primary hover:bg-secondary text-dark rounded-xl transition-colors font-medium shadow-primary">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    document.getElementById('descriptionForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Guardando...';
        
        try {
            const response = await fetch('/entrepreneur/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    description: this.description.value
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal(overlay);
                // Actualizar en la página
                const descElement = document.querySelector('.text-medium.leading-relaxed');
                if (descElement) {
                    descElement.textContent = this.description.value || 'Este emprendedor aún no ha agregado una descripción.';
                }
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message || 'Error al actualizar', 'error');
                btn.disabled = false;
                btn.textContent = 'Guardar cambios';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al conectar con el servidor', 'error');
            btn.disabled = false;
            btn.textContent = 'Guardar cambios';
        }
    });
};

// Editar información de contacto
window.editContact = function() {
    const overlay = createModalOverlay();
    
    // Obtener valores actuales
    const businessName = document.querySelector('h1.text-3xl')?.textContent.trim() || '';
    const email = document.querySelector('a[href^="mailto:"]')?.textContent.trim() || '';
    const phone = document.querySelector('a[href^="tel:"]')?.textContent.trim() || '';
    
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl shadow-primary-lg max-w-2xl w-full p-6 transform" style="animation: slideUp 0.3s ease-out;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-dark">Editar Información de Contacto</h3>
                <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-light rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="contactForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-medium mb-2">Nombre del Negocio *</label>
                    <input type="text" name="business_name" required value="${businessName}"
                        class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                        placeholder="Nombre de tu negocio">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-medium mb-2">Email *</label>
                    <input type="email" name="email" required value="${email}"
                        class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                        placeholder="correo@ejemplo.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-medium mb-2">Teléfono</label>
                    <input type="tel" name="phone" value="${phone}"
                        class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                        placeholder="+57 300 123 4567">
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="this.closest('.fixed').remove()" 
                        class="px-6 py-2.5 border-2 border-gray text-medium rounded-xl hover:bg-light transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-primary hover:bg-secondary text-dark rounded-xl transition-colors font-medium shadow-primary">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    document.getElementById('contactForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Guardando...';
        
        try {
            const response = await fetch('/entrepreneur/profile/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    business_name: this.business_name.value,
                    email: this.email.value,
                    phone: this.phone.value
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal(overlay);
                setTimeout(() => location.reload(), 1000);
            } else {
                if (data.errors) {
                    const errorMsg = Object.values(data.errors).flat().join('<br>');
                    showNotification(errorMsg, 'error');
                } else {
                    showNotification(data.message || 'Error al actualizar', 'error');
                }
                btn.disabled = false;
                btn.textContent = 'Guardar cambios';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al conectar con el servidor', 'error');
            btn.disabled = false;
            btn.textContent = 'Guardar cambios';
        }
    });
};

// Editar logo
window.editLogo = function() {
    const overlay = createModalOverlay();
    
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl shadow-primary-lg max-w-lg w-full p-6 transform" style="animation: slideUp 0.3s ease-out;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-dark">Cambiar Logo</h3>
                <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-light rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="logoForm" class="space-y-4">
                <div class="border-2 border-dashed border-gray rounded-xl p-8 text-center hover:border-primary transition-colors cursor-pointer" id="dropZone">
                    <input type="file" id="logoInput" name="logo" accept="image/jpeg,image/png,image/jpg" class="hidden">
                    <label for="logoInput" class="cursor-pointer">
                        <svg class="w-16 h-16 mx-auto text-gray mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-medium mb-1">Haz clic para seleccionar una imagen</p>
                        <p class="text-xs text-light">PNG, JPG hasta 2MB</p>
                    </label>
                </div>
                
                <div id="imagePreview" class="hidden">
                    <p class="text-sm font-medium text-medium mb-2">Vista previa:</p>
                    <img src="" alt="Preview" class="max-h-48 mx-auto rounded-xl">
                </div>
                
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="this.closest('.fixed').remove()" 
                        class="px-6 py-2.5 border-2 border-gray text-medium rounded-xl hover:bg-light transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" id="uploadBtn" disabled
                        class="px-6 py-2.5 bg-primary hover:bg-secondary text-dark rounded-xl transition-colors font-medium shadow-primary disabled:opacity-50 disabled:cursor-not-allowed">
                        Subir Logo
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    const logoInput = document.getElementById('logoInput');
    const imagePreview = document.getElementById('imagePreview');
    const uploadBtn = document.getElementById('uploadBtn');
    
    logoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validar tamaño
            if (file.size > 2048000) {
                showNotification('La imagen no puede superar los 2MB', 'error');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePreview.classList.remove('hidden');
                uploadBtn.disabled = false;
            };
            reader.readAsDataURL(file);
        }
    });
    
    document.getElementById('logoForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Subiendo...';
        
        const formData = new FormData();
        formData.append('logo', logoInput.files[0]);
        
        try {
            const response = await fetch('/entrepreneur/profile/logo', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal(overlay);
                setTimeout(() => location.reload(), 1000);
            } else {
                if (data.errors) {
                    const errorMsg = Object.values(data.errors).flat().join('<br>');
                    showNotification(errorMsg, 'error');
                } else {
                    showNotification(data.message || 'Error al subir logo', 'error');
                }
                btn.disabled = false;
                btn.textContent = 'Subir Logo';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al conectar con el servidor', 'error');
            btn.disabled = false;
            btn.textContent = 'Subir Logo';
        }
    });
};

// Agregar dirección
window.addAddress = function() {
    const overlay = createModalOverlay();
    
    overlay.innerHTML = `
        <div class="bg-white rounded-2xl shadow-primary-lg max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto transform" style="animation: slideUp 0.3s ease-out;">
            <div class="flex items-center justify-between mb-4 sticky top-0 bg-white pb-2 border-b border-gray z-10">
                <h3 class="text-2xl font-bold text-dark">Agregar Nueva Dirección</h3>
                <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-light rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="addAddressForm" class="space-y-4 mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-medium mb-2">Dirección *</label>
                        <input type="text" name="address" required
                            class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                            placeholder="Calle 123 #45-67">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-medium mb-2">Ciudad *</label>
                        <input type="text" name="city" required
                            class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                            placeholder="Popayán">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-medium mb-2">Departamento *</label>
                        <input type="text" name="department" required
                            class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                            placeholder="Cauca">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-medium mb-2">Código Postal</label>
                        <input type="text" name="postal_code"
                            class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                            placeholder="190001">
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_main" class="w-5 h-5 text-primary border-gray rounded focus:ring-primary">
                            <span class="text-sm font-medium text-medium">Dirección principal</span>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-medium mb-2">Referencia</label>
                    <textarea name="reference" rows="3" maxlength="500"
                        class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                        placeholder="Ej: Frente al parque principal, edificio azul"></textarea>
                </div>
                
                <div class="border-t border-gray pt-4">
                    <h4 class="font-medium text-dark mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Coordenadas GPS (Opcional)
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-medium mb-2">Latitud</label>
                            <input type="text" name="latitude" step="any"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="2.444100">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-medium mb-2">Longitud</label>
                            <input type="text" name="longitude" step="any"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="-76.605900">
                        </div>
                    </div>
                    
                    <button type="button" onclick="getLocation(this.form)" 
                        class="mt-3 px-4 py-2 bg-accent hover:bg-primary text-dark rounded-lg transition-colors text-sm font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Usar mi ubicación actual
                    </button>
                </div>
                
                <div class="flex gap-3 justify-end pt-4 border-t border-gray">
                    <button type="button" onclick="this.closest('.fixed').remove()" 
                        class="px-6 py-2.5 border-2 border-gray text-medium rounded-xl hover:bg-light transition-colors font-medium">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-primary hover:bg-secondary text-dark rounded-xl transition-colors font-medium shadow-primary">
                        Agregar Dirección
                    </button>
                </div>
            </form>
        </div>
    `;
    
    document.body.appendChild(overlay);
    
    document.getElementById('addAddressForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.textContent = 'Guardando...';
        
        const formData = {
            address: this.address.value,
            city: this.city.value,
            department: this.department.value,
            postal_code: this.postal_code.value || null,
            latitude: this.latitude.value || null,
            longitude: this.longitude.value || null,
            reference: this.reference.value || null,
            is_main: this.is_main.checked
        };
        
        try {
            const response = await fetch('/entrepreneur/addresses', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (data.success) {
                showNotification(data.message, 'success');
                closeModal(overlay);
                setTimeout(() => location.reload(), 1000);
            } else {
                if (data.errors) {
                    const errorMsg = Object.values(data.errors).flat().join('<br>');
                    showNotification(errorMsg, 'error');
                } else {
                    showNotification(data.message || 'Error al agregar dirección', 'error');
                }
                btn.disabled = false;
                btn.textContent = 'Agregar Dirección';
            }
        } catch (error) {
            console.error('Error:', error);
            showNotification('Error al conectar con el servidor', 'error');
            btn.disabled = false;
            btn.textContent = 'Agregar Dirección';
        }
    });
};

// Editar dirección
window.editAddress = async function(addressId) {
    // Cargar datos de la dirección
    try {
        const response = await fetch('/entrepreneur/addresses');
        const result = await response.json();
        
        if (!result.success) {
            showNotification('Error al cargar direcciones', 'error');
            return;
        }
        
        const address = result.data.find(a => a.id === addressId);
        if (!address) {
            showNotification('Dirección no encontrada', 'error');
            return;
        }
        
        const overlay = createModalOverlay();
        
        overlay.innerHTML = `
            <div class="bg-white rounded-2xl shadow-primary-lg max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto transform" style="animation: slideUp 0.3s ease-out;">
                <div class="flex items-center justify-between mb-4 sticky top-0 bg-white pb-2 border-b border-gray z-10">
                    <h3 class="text-2xl font-bold text-dark">Editar Dirección</h3>
                    <button onclick="this.closest('.fixed').remove()" class="p-2 hover:bg-light rounded-lg transition-colors">
                        <svg class="w-6 h-6 text-medium" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <form id="editAddressForm" class="space-y-4 mt-4">
                    <input type="hidden" name="address_id" value="${addressId}">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-medium mb-2">Dirección *</label>
                            <input type="text" name="address" required value="${address.address}"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="Calle 123 #45-67">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-medium mb-2">Ciudad *</label>
                            <input type="text" name="city" required value="${address.city}"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="Popayán">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-medium mb-2">Departamento *</label>
                            <input type="text" name="department" required value="${address.department}"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="Cauca">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-medium mb-2">Código Postal</label>
                            <input type="text" name="postal_code" value="${address.postal_code || ''}"
                                class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                placeholder="190001">
                        </div>
                        
                        <div class="flex items-center">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_main" ${address.is_main ? 'checked' : ''} class="w-5 h-5 text-primary border-gray rounded focus:ring-primary">
                                <span class="text-sm font-medium text-medium">Dirección principal</span>
                            </label>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-medium mb-2">Referencia</label>
                        <textarea name="reference" rows="3" maxlength="500"
                            class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                            placeholder="Ej: Frente al parque principal, edificio azul">${address.reference || ''}</textarea>
                    </div>
                    
                    <div class="border-t border-gray pt-4">
                        <h4 class="font-medium text-dark mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Coordenadas GPS (Opcional)
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-medium mb-2">Latitud</label>
                                <input type="text" name="latitude" step="any" value="${address.latitude || ''}"
                                    class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                    placeholder="2.444100">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-medium mb-2">Longitud</label>
                                <input type="text" name="longitude" step="any" value="${address.longitude || ''}"
                                    class="w-full px-4 py-3 border-2 border-gray rounded-xl focus:border-primary focus:outline-none transition-colors"
                                    placeholder="-76.605900">
                            </div>
                        </div>
                        
                        <button type="button" onclick="getLocation(this.form)" 
                            class="mt-3 px-4 py-2 bg-accent hover:bg-primary text-dark rounded-lg transition-colors text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Usar mi ubicación actual
                        </button>
                    </div>
                    
                    <div class="flex gap-3 justify-end pt-4 border-t border-gray">
                        <button type="button" onclick="deleteAddress(${addressId})" 
                            class="px-6 py-2.5 bg-red-500 hover:bg-red-600 text-white rounded-xl transition-colors font-medium mr-auto">
                            Eliminar
                        </button>
                        <button type="button" onclick="this.closest('.fixed').remove()" 
                            class="px-6 py-2.5 border-2 border-gray text-medium rounded-xl hover:bg-light transition-colors font-medium">
                            Cancelar
                        </button>
                        <button type="submit" 
                            class="px-6 py-2.5 bg-primary hover:bg-secondary text-dark rounded-xl transition-colors font-medium shadow-primary">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        `;
        
        document.body.appendChild(overlay);
        
        document.getElementById('editAddressForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Guardando...';
            
            const formData = {
                address: this.address.value,
                city: this.city.value,
                department: this.department.value,
                postal_code: this.postal_code.value || null,
                latitude: this.latitude.value || null,
                longitude: this.longitude.value || null,
                reference: this.reference.value || null,
                is_main: this.is_main.checked
            };
            
            try {
                const response = await fetch(`/entrepreneur/addresses/${addressId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification(data.message, 'success');
                    closeModal(overlay);
                    setTimeout(() => location.reload(), 1000);
                } else {
                    if (data.errors) {
                        const errorMsg = Object.values(data.errors).flat().join('<br>');
                        showNotification(errorMsg, 'error');
                    } else {
                        showNotification(data.message || 'Error al actualizar dirección', 'error');
                    }
                    btn.disabled = false;
                    btn.textContent = 'Guardar Cambios';
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error al conectar con el servidor', 'error');
                btn.disabled = false;
                btn.textContent = 'Guardar Cambios';
            }
        });
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al cargar la dirección', 'error');
    }
};

// Eliminar dirección
window.deleteAddress = async function(addressId) {
    if (!confirm('¿Estás seguro de que deseas eliminar esta dirección?')) {
        return;
    }
    
    try {
        const response = await fetch(`/entrepreneur/addresses/${addressId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            document.querySelector('.fixed')?.remove();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Error al eliminar dirección', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Error al conectar con el servidor', 'error');
    }
};

// Obtener ubicación actual
window.getLocation = function(form) {
    if (navigator.geolocation) {
        showNotification('Obteniendo ubicación...', 'info');
        
        navigator.geolocation.getCurrentPosition(
            function(position) {
                form.querySelector('[name="latitude"]').value = position.coords.latitude.toFixed(8);
                form.querySelector('[name="longitude"]').value = position.coords.longitude.toFixed(8);
                showNotification('Ubicación obtenida correctamente', 'success');
            },
            function(error) {
                let errorMsg = 'No se pudo obtener la ubicación.';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMsg = 'Debes permitir el acceso a tu ubicación.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMsg = 'Información de ubicación no disponible.';
                        break;
                    case error.TIMEOUT:
                        errorMsg = 'Tiempo de espera agotado.';
                        break;
                }
                showNotification(errorMsg, 'error');
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    } else {
        showNotification('Tu navegador no soporta geolocalización', 'error');
    }
};

// Mostrar notificaciones
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    let bgColor = 'bg-green';
    let icon = `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
    </svg>`;
    
    if (type === 'error') {
        bgColor = 'bg-red-500';
        icon = `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>`;
    } else if (type === 'info') {
        bgColor = 'bg-blue-500';
        icon = `<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>`;
    }
    
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-xl shadow-lg z-[60] flex items-center gap-3 max-w-md`;
    notification.style.animation = 'slideInRight 0.3s ease-out';
    notification.innerHTML = `
        ${icon}
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="hover:bg-white/20 rounded p-1 transition-colors">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Agregar estilos de animación
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    
    @keyframes slideUp {
        from { transform: translateY(20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
`;
document.head.appendChild(style);