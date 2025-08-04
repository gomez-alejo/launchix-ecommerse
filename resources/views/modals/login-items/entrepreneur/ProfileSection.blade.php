                <!-- Mi Perfil -->
                <div id="perfil" class="section-content">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Mi Perfil</h1>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <!-- Profile Picture -->
                            <div class="lg:col-span-1">
                                <div class="text-center">
                                    <div class="w-32 h-32 bg-light rounded-full mx-auto mb-4 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                                        </svg>
                                    </div>
                                    <button class="btn-secondary text-white px-4 py-2 rounded-lg text-sm">
                                        Cambiar Foto
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Profile Form -->
                            <div class="lg:col-span-2">
                                <form id="perfil-form" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre *</label>
                                            <input type="text" value="Juan" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Apellido *</label>
                                            <input type="text" value="Pérez" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                        <input type="email" value="juan.perez@email.com" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                            <input type="tel" value="(+57) 300-123-4567" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                                            <input type="text" value="Popayán" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                                        <input type="text" value="Calle 5 #12-34, Barrio Centro" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción Personal</label>
                                        <textarea class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" rows="4" placeholder="Cuéntanos sobre ti y tu negocio..."></textarea>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg">
                                            Actualizar Perfil
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>