                <!-- Publicar Producto -->
                <div id="publicar-producto" class="section-content">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Publicar Producto</h1>
                        
                        <form id="producto-form" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                                    <input type="text" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                                    <input type="number" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                                    <select class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                        <option value="">Seleccionar categoría</option>
                                        <option value="ropa">Ropa</option>
                                        <option value="calzado">Calzado</option>
                                        <option value="accesorios">Accesorios</option>
                                        <option value="hogar">Hogar</option>
                                        <option value="electronica">Electrónica</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad en Stock *</label>
                                    <input type="number" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" required>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                                <textarea class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none" rows="4" required></textarea>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Imágenes del Producto</label>
                                <div class="drag-area" id="product-dropzone">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <p class="text-gray-600 mb-2">Arrastra las imágenes aquí o haz clic para seleccionar</p>
                                    <input type="file" id="product-images" class="hidden" multiple accept="image/*">
                                    <button type="button" class="text-primary hover:text-secondary" onclick="document.getElementById('product-images').click()">
                                        Seleccionar Archivos
                                    </button>
                                </div>
                                <div id="product-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Peso (kg)</label>
                                    <input type="number" step="0.01" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Dimensiones (cm)</label>
                                    <input type="text" placeholder="L x A x H" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                    <input type="text" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none">
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-4">
                                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg">
                                    Publicar Producto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>