                <!-- Configuración -->
                <div id="configuracion" class="section-content">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Configuración</h1>
                        
                        <div class="space-y-8">
                            <!-- Cambiar Contraseña -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Cambiar Contraseña</h2>
                                <form id="password-form" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña Actual *</label>
                                        <input type="password" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nueva Contraseña *</label>
                                            <input type="password" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                                            <input type="password" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg">
                                            Cambiar Contraseña
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Notificaciones -->
                            <div class="border-b border-gray-200 pb-6">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Notificaciones</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Notificaciones por Email</h3>
                                            <p class="text-sm text-gray-500">Recibe notificaciones sobre pedidos y mensajes</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Notificaciones Push</h3>
                                            <p class="text-sm text-gray-500">Recibe notificaciones en tiempo real</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Privacidad -->
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Privacidad y Seguridad</h2>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-medium text-gray-700">Perfil Público</h3>
                                            <p class="text-sm text-gray-500">Permitir que otros usuarios vean tu perfil</p>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="checkbox" class="sr-only peer" checked>
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                                        </label>
                                    </div>
                                    
                                    <div class="pt-4">
                                        <button class="text-red-600 hover:text-red-800 font-medium">
                                            Desactivar Cuenta
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>