<div id="publicar-producto" class="section-content">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6" id="form-title">Publicar Producto</h1>
       <form id="producto-form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <input type="hidden" id="product-id" name="product_id" value="">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Producto *</label>
                    <input type="text" name="name" id="product-name" value="" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Precio *</label>
                    <input type="number" id="product-price" name="price" value="" step="0.01" min="0" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoría *</label>
                    <select id="product-category" name="category" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Seleccionar categoría</option>
                        <option value="ropa">Ropa</option>
                        <option value="calzado">Calzado</option>
                        <option value="accesorios">Accesorios</option>
                        <option value="hogar">Hogar</option>
                        <option value="electronica">Electrónica</option>
                        <option value="deportes">Deportes</option>
                        <option value="belleza">Belleza y Cuidado Personal</option>
                        <option value="juguetes">Juguetes</option>
                        <option value="libros">Libros</option>
                        <option value="otros">Otros</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad en Stock *</label>
                    <input type="number" id="product-stock" name="stock" value="" min="0" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Descripción *</label>
                <textarea id="product-description" name="description" class="form-input w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Describe tu producto..." required></textarea>
            </div>
            
            <div class="flex justify-end space-x-4">
                <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors" onclick="cancelProductForm()">
                    Cancelar
                </button>
                <button type="submit" class="btn-primary px-6 py-2 text-white rounded-lg hover:bg-blue-600 transition-colors" id="submit-button">
                    Publicar Producto
                </button>
            </div>
        </form>
    </div>
</div>