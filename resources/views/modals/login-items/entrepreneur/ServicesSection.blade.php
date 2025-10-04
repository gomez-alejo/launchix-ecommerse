<!-- Mis Servicios -->
<div id="servicios" class="section-content">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Mis Servicios (<span id="servicios-count">0</span>)</h1>
            <button class="btn-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors" onclick="showSection('publicar-servicio')">
                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Agregar Servicio
            </button>
        </div>

        <!-- Loading state -->
        <div id="servicios-loading" class="text-center py-12 hidden">
            <svg class="animate-spin w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-600">Cargando servicios...</p>
        </div>

        <!-- Contenedor de servicios -->
        <div id="mis-servicios-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Las cards se cargan dinámicamente por JS -->
        </div>
    </div>
</div>

<!-- Modal Ver Más -->
<div id="modal-ver" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button class="absolute top-2 right-2 text-gray-600 hover:text-primary" onclick="closeModal('modal-ver')">&times;</button>
        <h3 class="text-xl font-bold mb-2" id="ver-nombre"></h3>
        <img id="ver-img" src="" alt="" class="w-full h-48 object-cover rounded mb-3">
        <div class="mb-2 text-gray-700" id="ver-descripcion"></div>
        <div class="mb-2"><span class="font-semibold">Categoría:</span> <span id="ver-categoria"></span></div>
        <div class="mb-2"><span class="font-semibold">Precio:</span> <span id="ver-precio"></span></div>
        <div class="mb-2"><span class="font-semibold">Dirección:</span> <span id="ver-direccion"></span></div>
        <div class="mb-2"><span class="font-semibold">Teléfono:</span> <span id="ver-telefono"></span></div>
        <div class="mb-2"><span class="font-semibold">Horario:</span> <span id="ver-horario"></span></div>
        <div id="ver-galeria" class="flex gap-2 mt-2"></div>
    </div>
</div>

<!-- Modal Editar -->
<div id="modal-editar" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <button class="absolute top-2 right-2 text-gray-600 hover:text-primary" onclick="closeModal('modal-editar')">&times;</button>
        <h3 class="text-xl font-bold mb-4">Editar servicio</h3>
        <form id="form-editar-servicio" enctype="multipart/form-data">
            <input type="hidden" id="edit-id">
            <div class="mb-2">
                <label class="block font-semibold mb-1">Nombre</label>
                <input type="text" id="edit-nombre" name="nombre_servicio" class="form-input w-full rounded" required>
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Categoría</label>
                <input type="text" id="edit-categoria" name="categoria" class="form-input w-full rounded" required>
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Descripción</label>
                <textarea id="edit-descripcion" name="descripcion" class="form-textarea w-full rounded" rows="2" required></textarea>
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Dirección</label>
                <input type="text" id="edit-direccion" name="direccion" class="form-input w-full rounded">
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Teléfono</label>
                <input type="text" id="edit-telefono" name="telefono" class="form-input w-full rounded">
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Precio base</label>
                <input type="number" id="edit-precio" name="precio_base" class="form-input w-full rounded">
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Horario</label>
                <input type="text" id="edit-horario" name="horario_atencion" class="form-input w-full rounded">
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Imagen principal</label>
                <input type="file" id="edit-imagen" name="imagen_principal" class="form-input w-full">
            </div>
            <div class="mb-2">
                <label class="block font-semibold mb-1">Galería (puedes seleccionar varias)</label>
                <input type="file" id="edit-galeria" name="galeria_imagenes[]" class="form-input w-full" multiple>
            </div>
            <div id="edit-errors" class="text-red-500 text-sm mb-2"></div>
            <button type="submit" class="bg-primary text-white px-4 py-2 rounded hover:bg-primary-dark">Guardar cambios</button>
        </form>
    </div>
</div>

@vite('resources/js/MisServicios.js')
