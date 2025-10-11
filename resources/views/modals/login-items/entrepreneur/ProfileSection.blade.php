<!-- Sección de Perfil del Emprendedor -->
<div id="perfil" class="section-content">
    <!-- Alertas -->
    <div id="alert-container" class="mb-6"></div>

    <!-- Header con Logo y Datos del Negocio -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="gradient-primary h-32"></div>
        <div class="px-6 pb-6 -mt-16">
            <div class="flex flex-col md:flex-row items-center md:items-end gap-6">
                <!-- Logo del Negocio -->
                <div class="relative">
                    <div class="w-32 h-32 rounded-lg border-4 border-white shadow-lg overflow-hidden bg-white group">
                        <img id="logo-preview" 
                             src="" 
                             alt="Logo del negocio" 
                             class="w-full h-full object-cover">
                        
                        <!-- Overlay hover -->
                        <label for="logo-input" class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </label>
                    </div>
                    <input type="file" id="logo-input" class="hidden" accept="image/jpeg,image/png,image/jpg">
                    
                    <!-- Botón eliminar logo -->
                    <button id="delete-logo-btn" 
                            type="button"
                            class="absolute -top-1 -right-1 bg-error text-white rounded-full p-2 shadow-lg hover:bg-red-600 transition-colors hidden"
                            title="Eliminar logo">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Info del Negocio -->
                <div class="flex-1 text-center md:text-left">
                    <h1 id="business-name" class="text-3xl font-bold text-dark mb-1">Cargando...</h1>
                    <p id="business-email" class="text-medium mb-3">correo@ejemplo.com</p>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                        <span id="rating-display" class="inline-flex items-center text-sm">
                            <svg class="w-5 h-5 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <span class="font-semibold text-dark">0.0</span>
                        </span>
                        <span id="registered-date" class="inline-flex items-center text-sm text-light">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Miembro desde -</span>
                        </span>
                        <span id="verified-badge" class="hidden inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Verificado
                        </span>
                        <span id="active-badge" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-light text-green">
                            <svg class="w-2 h-2 mr-1" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3"></circle>
                            </svg>
                            Activo
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pestañas de Navegación -->
    <div class="bg-white rounded-lg shadow-lg mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button id="tab-business" class="tab-button active flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Información del Negocio
                </button>
                <button id="tab-personal" class="tab-button flex-1 py-4 px-6 text-center border-b-2 font-semibold text-sm transition-colors">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Dirección de Envío
                </button>
            </nav>
        </div>
    </div>

    <!-- Contenido de las Pestañas -->
    <div id="tab-content">
        
        <!-- PESTAÑA: INFORMACIÓN DEL NEGOCIO -->
        <div id="content-business" class="tab-content active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Sidebar - Vista Previa -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-dark mb-4 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Vista Previa
                        </h3>
                        
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Nombre del Negocio</p>
                                <p id="preview-business-name" class="text-sm text-dark font-medium">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Email</p>
                                <p id="preview-email" class="text-sm text-dark break-all">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Teléfono</p>
                                <p id="preview-phone" class="text-sm text-dark">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Descripción</p>
                                <p id="preview-description" class="text-sm text-medium leading-relaxed">Sin descripción</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Calificación</p>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                    <span id="preview-rating" class="text-sm font-semibold text-dark">0.0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario del Negocio -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-dark flex items-center">
                                <svg class="w-6 h-6 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar Información del Negocio
                            </h2>
                            <span class="text-xs text-gray bg-gray-100 px-3 py-1 rounded-full">* Campos obligatorios</span>
                        </div>

                        <form id="business-form" class="space-y-6">
                            @csrf
                            
                            <!-- Nombre del Negocio -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Nombre del Negocio <span class="text-error">*</span>
                                </label>
                                <input type="text" 
                                       name="business_name" 
                                       id="business_name" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                                       placeholder="Ej: Café del Centro"
                                       required>
                                <span class="error-message text-xs text-error mt-1 hidden block"></span>
                            </div>

                            <!-- Email del Negocio -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Correo Electrónico <span class="text-error">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       id="business_email_input" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                                       required>
                                <span class="error-message text-xs text-error mt-1 hidden block"></span>
                                <p class="text-xs text-gray-500 mt-2">Este correo será usado para notificaciones y contacto de clientes</p>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Teléfono de Contacto
                                </label>
                                <input type="tel" 
                                       name="phone" 
                                       id="business_phone" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="+57 300 123 4567">
                                <span class="error-message text-xs text-error mt-1 hidden block"></span>
                            </div>
                            
                            <!-- Descripción del Negocio -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Descripción del Negocio
                                    <span class="text-xs text-gray-500 font-normal ml-1">(máximo 1000 caracteres)</span>
                                </label>
                                <textarea name="description" 
                                          id="business_description" 
                                          class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none" 
                                          rows="5" 
                                          maxlength="1000"
                                          placeholder="Describe tu negocio, productos o servicios que ofreces..."></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="error-message text-xs text-error hidden block"></span>
                                    <span id="char-counter-business" class="text-xs text-gray-500 font-medium">0 / 1000</span>
                                </div>
                            </div>
                            
                            <!-- Botones -->
                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        id="cancel-business-btn"
                                        class="btn-outline-primary px-6 py-3 rounded-lg font-medium hover:bg-accent transition-all">
                                    Cancelar Cambios
                                </button>
                                <button type="submit" 
                                        id="submit-business-btn"
                                        class="btn-primary px-6 py-3 text-white rounded-lg font-medium flex items-center justify-center shadow-primary hover:shadow-primary-lg transition-all">
                                    <svg id="submit-business-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span id="submit-business-text">Actualizar Negocio</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- PESTAÑA: DIRECCIÓN DE ENVÍO -->
        <div id="content-personal" class="tab-content hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Sidebar - Dirección Actual -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                        <h3 class="text-lg font-bold text-dark mb-4 flex items-center">
                            <svg class="w-5 h-5 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Dirección Actual
                        </h3>
                        
                        <div id="current-address-display" class="space-y-4">
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Dirección</p>
                                <p id="preview-address" class="text-sm text-dark">No especificada</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Ciudad</p>
                                <p id="preview-city" class="text-sm text-dark">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Departamento</p>
                                <p id="preview-department" class="text-sm text-dark">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Código Postal</p>
                                <p id="preview-postal-code" class="text-sm text-dark">-</p>
                            </div>
                            
                            <div>
                                <p class="text-xs text-gray uppercase font-semibold mb-2">Referencias</p>
                                <p id="preview-reference" class="text-sm text-medium leading-relaxed">-</p>
                            </div>
                        </div>

                        <div id="no-address-display" class="hidden">
                            <div class="bg-gray-50 rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">No has registrado ninguna dirección de envío</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario de Dirección -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-dark flex items-center">
                                <svg class="w-6 h-6 text-primary mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Dirección de Envío
                            </h2>
                            <span class="text-xs text-gray bg-gray-100 px-3 py-1 rounded-full">* Campos obligatorios</span>
                        </div>

                        <form id="address-form" class="space-y-6">
                            @csrf
                            <input type="hidden" name="user_id" id="user_id">
                            <input type="hidden" name="address_id" id="address_id">
                            
                            <!-- Dirección Completa -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Dirección Completa <span class="text-error">*</span>
                                </label>
                                <input type="text" 
                                       name="address" 
                                       id="address" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" 
                                       placeholder="Ej: Calle 5 #12-34"
                                       required>
                                <span class="error-message text-xs text-error mt-1 hidden block"></span>
                            </div>

                            <!-- Ciudad y Departamento -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Ciudad <span class="text-error">*</span>
                                    </label>
                                    <input type="text" 
                                           name="city" 
                                           id="city" 
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                           placeholder="Ej: Popayán"
                                           required>
                                    <span class="error-message text-xs text-error mt-1 hidden block"></span>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Departamento <span class="text-error">*</span>
                                    </label>
                                    <input type="text" 
                                           name="department" 
                                           id="department" 
                                           class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                           placeholder="Ej: Cauca"
                                           required>
                                    <span class="error-message text-xs text-error mt-1 hidden block"></span>
                                </div>
                            </div>

                            <!-- Código Postal -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Código Postal
                                </label>
                                <input type="text" 
                                       name="postal_code" 
                                       id="postal_code" 
                                       class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                                       placeholder="Ej: 190001"
                                       maxlength="20">
                                <span class="error-message text-xs text-error mt-1 hidden block"></span>
                            </div>
                            
                            <!-- Referencias Adicionales -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Referencias Adicionales
                                    <span class="text-xs text-gray-500 font-normal ml-1">(opcional)</span>
                                </label>
                                <textarea name="reference" 
                                          id="reference" 
                                          class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none" 
                                          rows="3" 
                                          maxlength="500"
                                          placeholder="Ej: Casa de dos pisos, portón verde, al lado de la panadería"></textarea>
                                <div class="flex justify-between items-center mt-2">
                                    <span class="error-message text-xs text-error hidden block"></span>
                                    <span id="char-counter-reference" class="text-xs text-gray-500 font-medium">0 / 500</span>
                                </div>
                            </div>

                            <!-- Dirección Principal -->
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_main" 
                                       id="is_main" 
                                       class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                                       checked>
                                <label for="is_main" class="ml-2 text-sm text-gray-700">
                                    Establecer como dirección principal
                                </label>
                            </div>
                            
                            <!-- Botones -->
                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                                <button type="button" 
                                        id="cancel-address-btn"
                                        class="btn-outline-primary px-6 py-3 rounded-lg font-medium hover:bg-accent transition-all">
                                    Cancelar Cambios
                                </button>
                                <button type="submit" 
                                        id="submit-address-btn"
                                        class="btn-primary px-6 py-3 text-white rounded-lg font-medium flex items-center justify-center shadow-primary hover:shadow-primary-lg transition-all">
                                    <svg id="submit-address-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span id="submit-address-text">Guardar Dirección</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Auto-cargar datos cuando se muestra la sección
document.addEventListener('DOMContentLoaded', function() {
    const perfilSection = document.getElementById('perfil');
    
    if (perfilSection) {
        // Observar cambios de visibilidad
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                const isVisible = !perfilSection.classList.contains('hidden') && 
                                perfilSection.style.display !== 'none' &&
                                perfilSection.offsetParent !== null;
                
                if (isVisible && typeof window.loadEntrepreneurProfile === 'function') {
                    console.log('🔵 [AUTO] Sección de perfil visible, cargando datos...');
                    window.loadEntrepreneurProfile();
                    observer.disconnect(); // Solo cargar una vez
                }
            });
        });
        
        observer.observe(perfilSection, { 
            attributes: true, 
            attributeFilter: ['class', 'style'],
            childList: false,
            subtree: false
        });
        
        // Si ya está visible, cargar inmediatamente
        setTimeout(() => {
            if (perfilSection.offsetParent !== null && typeof window.loadEntrepreneurProfile === 'function') {
                console.log('🔵 [AUTO] Sección ya visible, cargando datos inmediatamente...');
                window.loadEntrepreneurProfile();
            }
        }, 500);
    }

    // Manejo de pestañas
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.id.replace('tab-', 'content-');
            
            // Remover clase active de todos los botones y contenidos
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-primary', 'text-primary');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            
            tabContents.forEach(content => {
                content.classList.add('hidden');
                content.classList.remove('active');
            });
            
            // Activar pestaña seleccionada
            this.classList.add('active', 'border-primary', 'text-primary');
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            
            const selectedContent = document.getElementById(tabId);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
                selectedContent.classList.add('active');
            }
        });
    });
});
</script>

<style>
.tab-button {
    border-color: transparent;
    color: #6b7280;
}

.tab-button:hover {
    color: #374151;
    border-color: #d1d5db;
}

.tab-button.active {
    border-color: var(--primary-color, #4F46E5);
    color: var(--primary-color, #4F46E5);
}

.tab-content {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

@vite(['resources/js/entrepreneurProfile.js'])