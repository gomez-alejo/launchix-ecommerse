                <!-- Mis Productos -->
                <div id="productos" class="section-content">
                    <div class="bg-white rounded-lg shadow-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-800">Mis Productos</h1>
                            <button class="btn-primary text-white px-4 py-2 rounded-lg" onclick="showSection('publicar-producto')">
                                Agregar Producto
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Product Card -->
                            <div class="product-card bg-white border rounded-lg overflow-hidden">
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                    </svg>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2">Camiseta Básica</h3>
                                    <p class="text-gray-600 text-sm mb-2">Camiseta de algodón 100% disponible en varios colores</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-primary font-bold">$25.000</span>
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm">Editar</button>
                                            <button class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Repeat for more products -->
                            <div class="product-card bg-white border rounded-lg overflow-hidden">
                                <div class="h-48 bg-gray-200 flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                                    </svg>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2">Zapatos Deportivos</h3>
                                    <p class="text-gray-600 text-sm mb-2">Zapatos cómodos para ejercicio y uso diario</p>
                                    <div class="flex justify-between items-center">
                                        <span class="text-primary font-bold">$85.000</span>
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-800 text-sm">Editar</button>
                                            <button class="text-red-600 hover:text-red-800 text-sm">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>