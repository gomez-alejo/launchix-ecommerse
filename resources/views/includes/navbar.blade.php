
<nav class="navbar text-white p-4">
    <div class="flex items-center space-x-6">
        <!-- Logo -->
        <div class="text-xl font-bold">
            <a href="#"><i class="fas fa-shopping-bag"></i> Logo</a>
        </div>
        <!-- Enlaces -->
        <a href="#" class="hover:text-gray-300"><i class="fas fa-star"></i> Artículos más vendidos</a>
        <a href="{{ route('products') }}" class="hover:text-gray-300"><i class="fas fa-box"></i> Productos</a>
        <a href="{{ route('services') }}" class="hover:text-gray-300"><i class="fas fa-concierge-bell"></i> Servicios</a>
        <div class="relative" id="categoriesContainer">
            <button id="categoriesButton" class="hover:text-gray-300"><i class="fas fa-list"></i> Categorías</button>
            <!-- Modal -->
            <div id="modal" class="absolute bg-white p-4 rounded-lg shadow-lg w-48 hidden">
                <ul>
                    <li class="mb-2"><a href="#" class="text-blue-500 hover:text-blue-700">Electrónicos</a></li>
                    <li class="mb-2"><a href="#" class="text-blue-500 hover:text-blue-700">Ropa</a></li>
                    <li class="mb-2"><a href="#" class="text-blue-500 hover:text-blue-700">Hogar</a></li>
                    <li class="mb-2"><a href="#" class="text-blue-500 hover:text-blue-700">Deportes</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Barra de búsqueda -->
    <div class="mt-2 md:mt-0 md:mx-4 w-full md:w-auto">
        <form action="#" method="GET" class="flex items-center">
            <input type="text" placeholder="Buscar..." class="px-3 py-1 text-sm rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full md:w-64">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 text-sm rounded-r-lg">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Enlaces fijos a la derecha -->
    <div class="flex items-center space-x-4">
        <a href="#" class="hover:text-gray-300"><i class="fas fa-user"></i> Cuenta</a>
        <a href="#" class="hover:text-gray-300"><i class="fas fa-shopping-cart"></i> Carrito</a>
        <a href="#" class="hover:text-gray-300"><i class="fas fa-question-circle"></i> Ayuda</a>
    </div>
</nav>
    <script>
    const categoriesContainer = document.getElementById('categoriesContainer');
    const modal = document.getElementById('modal');

    categoriesContainer.addEventListener('mouseenter', () => {
        modal.classList.remove('hidden');
    });

    categoriesContainer.addEventListener('mouseleave', () => {
        modal.classList.add('hidden');
    });
</script>
