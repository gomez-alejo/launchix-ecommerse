    @vite ('resources/css/navbar.css')
    <!-- Navbar compacto -->
    <nav class="navbar text-white p-3 sticky top-0 z-50">
        <div class="container mx-auto">
            <div class="navbar-container">
                    <!-- Logo -->
                <div class="text-xl font-bold">
                    <a href="" class="logo flex items-center space-x-2">
                        <i class="fas fa-shopping-cart text-2xl bounce-icon"></i>
                        <span>launchix</span>
                    </a>
                </div>


                <!-- Enlaces principales (ocultos en móvil) -->
                <div class="nav-main-links">
                    <a href="" class="nav-link hover:text-lighter transition-colors duration-300">
                        <i class="fas fa-star"></i> Más Vendidos
                    </a>
                    <a href="/html/Productos.html" class="nav-link hover:text-lighter transition-colors duration-300">
                        <i class="fas fa-box"></i> Productos
                    </a>
                    <a href="/html/Servicios.html" class="nav-link hover:text-lighter transition-colors duration-300">
                        <i class="fas fa-concierge-bell"></i> Servicios
                    </a>
                    <div class="relative" id="categoriesContainer">
                        <button id="categoriesButton" class="nav-link hover:text-lighter transition-colors duration-300">
                            <i class="fas fa-list"></i> Categorías
                        </button>
                        <div id="categoriesModal" class="modal absolute bg-white p-4 rounded-lg shadow-lg w-48 mt-2 hidden z-50">
                            <ul>
                                <li class="mb-2"><a href="#" class="text-primary hover:text-secondary transition-colors block p-2 rounded hover:bg-gray-100">Electrónicos</a></li>
                                <li class="mb-2"><a href="#" class="text-primary hover:text-secondary transition-colors block p-2 rounded hover:bg-gray-100">Ropa</a></li>
                                <li class="mb-2"><a href="#" class="text-primary hover:text-secondary transition-colors block p-2 rounded hover:bg-gray-100">Hogar</a></li>
                                <li class="mb-2"><a href="#" class="text-primary hover:text-secondary transition-colors block p-2 rounded hover:bg-gray-100">Deportes</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Barra de búsqueda compacta (oculta en móvil) -->
                <div class="search-compact">
                    <form class="flex items-center search-container">
                        <input type="text" id="searchInput" placeholder="Buscar productos..."
                            class="search-input px-3 py-2 text-gray-800 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-primary w-full text-sm">
                        <button type="submit" class="search-btn bg-primary hover:bg-secondary text-white font-bold py-2 px-3 rounded-r-lg transition-colors duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Enlaces de usuario (ocultos en móvil) -->
                <div class="nav-user-links">
                    <a href="/html/sesion.html" class="auth-btn bg-lighter hover:bg-light text-primary font-semibold py-2 px-3 rounded-lg transition-all duration-300 text-sm">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </a>
                    
                    <div class="relative" id="profileContainer">
                        <button id="profileButton" class="nav-link hover:text-lighter transition-colors duration-300">
                            <i class="fas fa-user"></i> Perfil
                        </button>
                        <div id="profileModal" class="profile-modal absolute right-0 bg-white p-4 rounded-lg shadow-lg w-48 mt-2 hidden z-50">
                            <ul>
                                <li class="mb-2">
                                    <a href="/html/perfil.html" class="profile-modal-item text-primary hover:text-secondary transition-colors block p-2 rounded">
                                        <i class="fas fa-user-circle mr-2"></i>Mi Perfil
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="profile-modal-item text-primary hover:text-secondary transition-colors block p-2 rounded">
                                        <i class="fas fa-cog mr-2"></i>Configuraciones
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="#" class="profile-modal-item text-primary hover:text-secondary transition-colors block p-2 rounded">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <a href="/html/Carrito.html" class="nav-link hover:text-lighter transition-colors duration-300">
                        <i class="fas fa-shopping-cart"></i> Carrito
                    </a>
                    
                    <a href="#" class="nav-link hover:text-lighter transition-colors duration-300">
                        <i class="fas fa-question-circle"></i> Ayuda
                    </a>
                </div>

                <!-- Botón de menú móvil -->
                <button id="mobileMenuBtn" class="lg:hidden nav-link">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Menú móvil -->
        <div id="mobileMenu" class="mobile-menu hidden lg:hidden mt-4 p-4 rounded-lg">
            <div class="flex flex-col space-y-2">
                <!-- Barra de búsqueda móvil -->
                <div class="border-b border-white/20 pb-3 mb-3">
                    <form class="flex items-center">
                        <input type="text" placeholder="Buscar productos..." class="flex-1 px-3 py-2 text-gray-800 rounded-l-lg focus:outline-none">
                        <button type="submit" class="bg-primary hover:bg-secondary text-white py-2 px-3 rounded-r-lg transition-colors duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <!-- Enlaces principales móvil -->
                <a href="#" class="mobile-menu-link text-white">
                    <i class="fas fa-star mr-2"></i> Más Vendidos
                </a>
                <a href="/html/Productos.html" class="mobile-menu-link text-white">
                    <i class="fas fa-box mr-2"></i> Productos
                </a>
                <a href="#" class="mobile-menu-link text-white">
                    <i class="fas fa-concierge-bell mr-2"></i> Servicios
                </a>
                
                <!-- Categorías móvil con submenu -->
                <div class="mobile-category-container">
                    <button id="mobileCategoriesBtn" class="mobile-menu-link text-white w-full text-left flex items-center justify-between">
                        <span><i class="fas fa-list mr-2"></i> Categorías</span>
                        <i class="fas fa-chevron-down transition-transform duration-300" id="mobileCategoriesIcon"></i>
                    </button>
                    <div id="mobileCategoriesMenu" class="hidden ml-4 mt-2 space-y-2">
                        <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                            <i class="fas fa-laptop mr-2"></i> Electrónicos
                        </a>
                        <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                            <i class="fas fa-tshirt mr-2"></i> Ropa
                        </a>
                        <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                            <i class="fas fa-home mr-2"></i> Hogar
                        </a>
                        <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                            <i class="fas fa-running mr-2"></i> Deportes
                        </a>
                    </div>
                </div>

                <!-- Separador -->
                <div class="border-t border-white/20 pt-3 mt-3">
                    <a href="#" class="mobile-menu-link text-white">
                        <i class="fas fa-sign-in-alt mr-2"></i> Iniciar Sesión
                    </a>
                    
                    <!-- Perfil móvil con submenu -->
                    <div class="mobile-profile-container">
                        <button id="mobileProfileBtn" class="mobile-menu-link text-white w-full text-left flex items-center justify-between">
                            <span><i class="fas fa-user mr-2"></i> Perfil</span>
                            <i class="fas fa-chevron-down transition-transform duration-300" id="mobileProfileIcon"></i>
                        </button>
                        <div id="mobileProfileMenu" class="hidden ml-4 mt-2 space-y-2">
                            <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                                <i class="fas fa-user-circle mr-2"></i> Mi Perfil
                            </a>
                            <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                                <i class="fas fa-cog mr-2"></i> Configuraciones
                            </a>
                            <a href="#" class="block text-white/80 hover:text-white py-2 px-4 rounded transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                    
                    <a href="#" class="mobile-menu-link text-white">
                        <i class="fas fa-shopping-cart mr-2"></i> Carrito
                    </a>
                    <a href="#" class="mobile-menu-link text-white">
                        <i class="fas fa-question-circle mr-2"></i> Ayuda
                    </a>
                </div>
            </div>
        </div>
    </nav>