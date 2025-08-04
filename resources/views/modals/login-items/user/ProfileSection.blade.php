                <!-- Profile Section -->
                <div id="profile-section" class="section active">
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">Última actualización: 15 Jul 2025</span>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                        </div>

                        <!-- Notification Area -->
                        <div id="notification" class="notification hidden">
                            <p id="notification-text"></p>
                        </div>

                        <!-- Profile Form -->
                        <form id="profileForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Personal Information -->
                                <div class="space-y-6">
                                    <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                                        Información Personal
                                    </h2>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre Completo
                                        </label>
                                        <input type="text" id="fullName" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="Juan Pérez" placeholder="Ingresa tu nombre completo">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Nombre de Usuario
                                        </label>
                                        <input type="text" id="username" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="juanperez123" placeholder="Ingresa tu nombre de usuario">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Correo Electrónico
                                        </label>
                                        <input type="email" id="email" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="juan.perez@email.com" placeholder="Ingresa tu correo electrónico">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Teléfono
                                        </label>
                                        <input type="tel" id="phone" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="+57 300 123 4567" placeholder="Ingresa tu número de teléfono">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Fecha de Nacimiento
                                        </label>
                                        <input type="date" id="birthDate" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="1990-05-15">
                                    </div>
                                </div>

                                <!-- Address Information -->
                                <div class="space-y-6">
                                    <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2">
                                        Dirección
                                    </h2>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Dirección Principal
                                        </label>
                                        <input type="text" id="address" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                               value="Calle 123 #45-67" placeholder="Ingresa tu dirección">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Ciudad
                                            </label>
                                            <input type="text" id="city" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                                   value="Popayán" placeholder="Ciudad">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Código Postal
                                            </label>
                                            <input type="text" id="zipCode" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                                   value="190001" placeholder="Código postal">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Departamento
                                        </label>
                                        <select id="department" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none">
                                            <option value="cauca" selected>Cauca</option>
                                            <option value="bogota">Bogotá D.C.</option>
                                            <option value="antioquia">Antioquia</option>
                                            <option value="valle">Valle del Cauca</option>
                                        </select>
                                    </div>

                                    <!-- Change Password Section -->
                                    <div class="mt-8">
                                        <h2 class="text-xl font-semibold text-gray-800 border-b border-gray-200 pb-2 mb-4">
                                            Cambiar Contraseña
                                        </h2>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Contraseña Actual
                                                </label>
                                                <input type="password" id="currentPassword" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                                       placeholder="Ingresa tu contraseña actual">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Nueva Contraseña
                                                </label>
                                                <input type="password" id="newPassword" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                                       placeholder="Ingresa tu nueva contraseña">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Confirmar Nueva Contraseña
                                                </label>
                                                <input type="password" id="confirmPassword" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none" 
                                                       placeholder="Confirma tu nueva contraseña">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                                <button type="button" class="btn-secondary px-6 py-3 rounded-lg font-medium">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-primary text-white px-6 py-3 rounded-lg font-medium">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>