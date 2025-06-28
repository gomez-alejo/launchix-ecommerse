<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Incluye Tailwind CSS desde un CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Incluye Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
    }
    .navbar {
        background-color: transparent;
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 100;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
    }


    #categoriesContainer {
        position: relative;
    }

    #modal {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1050;
        padding: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        background-color: rgba(0, 0, 0, 0.7); /* Fondo semi-transparente solo para el modal */
    }

    #modal ul li a {
        color: white; /* Color del texto en el modal */
    }

    #modal ul li a:hover {
        color: #d1d5db; /* Color del texto al pasar el mouse */
    }
    .carousel {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }

    .carousel-inner {
        display: flex;
        height: 100%;
        transition: transform 0.5s ease-in-out; /* Añade una transición suave */
    }

    .carousel-item {
        min-width: 100%;
        position: relative;
    }

    .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 20px;
        border-radius: 10px;
    }

    .carousel-control {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        border: none;
        padding: 10px;
        cursor: pointer;
        z-index: 10;
    }

    .carousel-control.prev {
        left: 10px;
    }

    .carousel-control.next {
        right: 10px;
    }
</style>
</head>
<body>  
    @include('includes.navbar')

    @yield('content')

<script>
    // JavaScript para el carrusel
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const totalItems = items.length;

    function updateCarousel() {
        const offset = -currentIndex * 100;
        document.querySelector('.carousel-inner').style.transform = `translateX(${offset}%)`;
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % totalItems;
        updateCarousel();
    }

    function prevSlide() {
        currentIndex = (currentIndex - 1 + totalItems) % totalItems;
        updateCarousel();
    }

    // Auto-advance the carousel every 3 seconds
    setInterval(nextSlide, 3000);
</script>
</body>
</html>
