@extends('layouts.app')

@section('title', 'Bienvenido')

@vite(['resources/css/profile.css', 'resources/css/profileWelcome.css'])

@section('content')

<!-- Main Content -->
<div class="container mx-auto px-4 py-8">
    
    <div class="flex gap-8">
        <!-- Sidebar -->
        @include('modals.login-items.user.sideBar')

        <!-- Main Content Area -->
        <main class="flex-1">
            
            <!-- Welcome Hero Section -->
            <div class="welcome-hero bg-gradient-to-br from-lighter via-accent to-light rounded-2xl p-12 mb-8 relative overflow-hidden">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-primary opacity-10 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-secondary opacity-10 rounded-full -ml-24 -mb-24"></div>
                
                <div class="relative z-10 max-w-3xl">
                    <!-- Welcome Icon -->
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-primary rounded-2xl shadow-primary mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                    
                    <!-- Welcome Text -->
                    <h1 class="text-4xl md:text-5xl font-bold text-dark mb-4">
                        ¡Bienvenido a <span class="text-primary">Launchix </span>!
                    </h1>
                    <p class="text-xl text-medium mb-6 leading-relaxed">
                        Gracias por unirte a nuestra comunidad. Estamos emocionados de tenerte aquí y de acompañarte en cada compra.
                    </p>
                    <p class="text-lg text-light">
                        Tu cuenta ha sido creada exitosamente. Ahora puedes disfrutar de todos los beneficios que tenemos para ti.
                    </p>
                </div>
            </div>

            <!-- Benefits Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Benefit 1: Fast Shipping -->
                <div class="benefit-card bg-white rounded-xl p-6 shadow-soft hover:shadow-primary transition-all duration-300 border border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-accent rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Envíos Rápidos</h3>
                            <p class="text-sm text-light leading-relaxed">
                                Recibe tus productos en tiempo récord con nuestro sistema de logística optimizado.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 2: Secure Payment -->
                <div class="benefit-card bg-white rounded-xl p-6 shadow-soft hover:shadow-primary transition-all duration-300 border border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-green-light rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Pago Seguro</h3>
                            <p class="text-sm text-light leading-relaxed">
                                Protegemos tu información con los más altos estándares de seguridad.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Benefit 3: 24/7 Support -->
                <div class="benefit-card bg-white rounded-xl p-6 shadow-soft hover:shadow-primary transition-all duration-300 border border-gray-100">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-14 h-14 bg-orange-light rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-dark mb-2">Soporte 24/7</h3>
                            <p class="text-sm text-light leading-relaxed">
                                Nuestro equipo está disponible para ayudarte en cualquier momento que lo necesites.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Getting Started Section -->
            <div class="bg-white rounded-xl p-8 shadow-soft mb-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-gradient-primary rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-dark">Primeros Pasos</h2>
                </div>

                <div class="space-y-4">
                    <!-- Step 1 -->
                    <div class="flex items-start gap-4 p-4 bg-lighter rounded-lg hover:bg-accent transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-dark font-bold">
                            1
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-dark mb-1">Completa tu Perfil</h3>
                            <p class="text-sm text-light">
                                Agrega tu información de contacto y dirección de envío para agilizar tus compras.
                            </p>
                        </div>
                        <a href="" class="flex-shrink-0 px-4 py-2 bg-white border-2 border-primary text-primary rounded-lg font-medium hover:bg-primary hover:text-dark transition-all duration-200">
                            Ir al Perfil
                        </a>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-start gap-4 p-4 bg-lighter rounded-lg hover:bg-accent transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-dark font-bold">
                            2
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-dark mb-1">Explora Nuestro Catálogo</h3>
                            <p class="text-sm text-light">
                                Descubre miles de productos de calidad con los mejores precios del mercado.
                            </p>
                        </div>
                        <a href="" class="flex-shrink-0 px-4 py-2 bg-white border-2 border-primary text-primary rounded-lg font-medium hover:bg-primary hover:text-dark transition-all duration-200">
                            Ver Productos
                        </a>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-start gap-4 p-4 bg-lighter rounded-lg hover:bg-accent transition-colors duration-200">
                        <div class="flex-shrink-0 w-8 h-8 bg-primary rounded-full flex items-center justify-center text-dark font-bold">
                            3
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-dark mb-1">Realiza tu Primera Compra</h3>
                            <p class="text-sm text-light">
                                Agrega productos a tu carrito y disfruta de una experiencia de compra sencilla y segura.
                            </p>
                        </div>
                        <a href="" class="flex-shrink-0 px-4 py-2 bg-white border-2 border-primary text-primary rounded-lg font-medium hover:bg-primary hover:text-dark transition-all duration-200">
                            Ir al Carrito
                        </a>
                    </div>
                </div>
            </div>

            <!-- Promotions Banner -->
            <div class="bg-gradient-warm rounded-xl p-8 text-white shadow-primary-lg relative overflow-hidden">
                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 right-0 w-32 h-32 border-4 border-white rounded-full"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 border-4 border-white rounded-full"></div>
                </div>
                
                <div class="relative z-10 flex items-center justify-between flex-wrap gap-6">
                    <div class="flex-1 min-w-[300px]">
                        <div class="inline-block bg-white bg-opacity-20 px-3 py-1 rounded-full text-sm font-semibold mb-3">
                            🎉 Oferta de Bienvenida
                        </div>
                        <h3 class="text-2xl font-bold mb-2">¡Obtén 10% de descuento en tu primera compra!</h3>
                        <p class="text-white text-opacity-90">
                            Usa el código <span class="font-bold bg-white bg-opacity-20 px-2 py-1 rounded">BIENVENIDO10</span> al finalizar tu compra.
                        </p>
                    </div>
                    <button class="px-8 py-3 bg-white text-orange rounded-lg font-bold hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        Comprar Ahora
                    </button>
                </div>
            </div>

            <!-- Help Section -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- FAQ -->
                <div class="bg-white rounded-xl p-6 shadow-soft border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-dark">¿Necesitas Ayuda?</h3>
                    </div>
                    <p class="text-sm text-light mb-4">
                        Consulta nuestras preguntas frecuentes o contacta con nuestro equipo de soporte.
                    </p>
                    <a href="" class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors">
                        Ver Preguntas Frecuentes
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Contact -->
                <div class="bg-white rounded-xl p-6 shadow-soft border border-gray-100">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-bold text-dark">Contáctanos</h3>
                    </div>
                    <p class="text-sm text-light mb-4">
                        ¿Tienes alguna pregunta? Estamos aquí para ayudarte en lo que necesites.
                    </p>
                    <a href="" class="inline-flex items-center text-sm font-semibold text-primary hover:text-secondary transition-colors">
                        Enviar Mensaje
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

        </main>
    </div>
</div>

@endsection

@vite('resources/js/profile.js')