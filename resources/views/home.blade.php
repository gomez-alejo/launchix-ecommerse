@extends('layouts.app')

@section('title', 'Launchix E-commerce')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="relative w-full mb-10">
        <div id="heroCarousel" class="relative overflow-hidden rounded-lg h-72">
            @php
                $heroBanners = [
                    [
                        // Eliminado PHP directo para evitar ensuciar HTML
                        'img' => 'https://www.claroshop.com/c/algolia/assets/portada/laptops.webp',
                        'title' => 'OFERTÓN DE TECNOLOGÍA',
                        'desc' => 'Hasta 12 cuotas sin interés',
                        'price' => 'Ahora $399.900',
                    ],
                    [
                        'img' => 'https://phandroid.com/wp-content/uploads/2025/05/Budget-phones-2025-1200x600.jpg',
                        'title' => '¡Lanzamiento de Smartphones!',
                        'desc' => 'Hasta 30% de descuento solo por hoy',
                        'price' => null,
                    ],
                    [
                        'img' => 'https://img.freepik.com/vector-premium/elementos-fitness-realistas-gimnasio-mujeres-accesorios-ninas-yoga-objetos-diferentes-dispositivos-deportivos-realistas_166005-1631.jpg',
                        'title' => '¡Deportes y Fitness!',
                        'desc' => 'Todo para tu entrenamiento con envío gratis',
                        'price' => null,
                    ],
                    [
                        'img' => 'https://i.ytimg.com/vi/WJIfj9QmRAo/maxresdefault.jpg',
                        'title' => '¡Moda y Estilo!',
                        'desc' => 'Nuevas colecciones con 2x1 en ropa seleccionada',
                        'price' => null,
                    ],
                ];
            @endphp
            <div class="relative w-full h-72">
                @foreach($heroBanners as $i => $banner)
                    <div class="hero-slide absolute inset-0 transition-opacity duration-700 ease-in-out bg-white {{ $i === 0 ? '' : 'hidden' }}" data-hero-slide="{{ $i }}">
                        <div class="w-full h-72 flex items-center justify-center bg-gray-200 relative">
                            <img
                                src="{{ $banner['img'] }}"
                                class="w-full h-72 object-cover rounded-lg"
                                alt="{{ $banner['title'] }}"
                                loading="lazy"
                                onerror="this.style.display='none'; this.parentElement.innerHTML+='<span class=\'text-gray-500\'>Imagen no disponible</span>';">
                            <div class="absolute top-8 left-8 bg-white bg-opacity-90 px-8 py-4 rounded-lg shadow text-2xl font-bold text-primary">
                                {{ $banner['title'] }}
                                <span class="block text-base font-normal text-gray-700">{{ $banner['desc'] }}</span>
                            </div>
                            @if($banner['price'])
                                <div class="absolute top-8 right-8 bg-black text-white px-4 py-2 rounded-lg shadow text-lg font-bold">
                                    {{ $banner['price'] }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                {{-- Flechas --}}
                <button type="button" id="heroPrev" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-2 shadow z-20 focus:outline-none">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" id="heroNext" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-2 shadow z-20 focus:outline-none">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>
                {{-- Puntos --}}
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                    @foreach($heroBanners as $i => $banner)
                        <button type="button" class="hero-dot w-3 h-3 rounded-full {{ $i === 0 ? 'bg-primary' : 'bg-gray-300' }}" data-hero-dot="{{ $i }}"></button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Promociones y descuentos --}}
    <div class="mb-10">
        <h2 class="text-2xl font-bold mb-4 text-primary">Promociones y Descuentos</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($promos as $promo)
                <div class="rounded-lg p-6 flex flex-col items-center text-center shadow {{ $promo['bg'] }}">
                    <span class="text-4xl mb-2 {{ $promo['iconColor'] }}"><i class="{{ $promo['icon'] }}"></i></span>
                    <h3 class="font-bold text-lg mb-2">{{ $promo['title'] }}</h3>
                    <p>{{ $promo['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Productos destacados dinámicos --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-primary">Productos Destacados</h2>
            <a href="{{ route('productos') }}" class="text-primary hover:underline">Ver todos</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                    <img src="{{ $product->image }}" class="w-32 h-32 object-cover mb-2 rounded">
                    <h3 class="font-semibold mb-1">{{ $product->name }}</h3>
                    <span class="text-green-600 font-bold mb-2">${{ number_format($product->price, 2) }}</span>
                    <a href="{{ route('productos.show', $product->id) }}" class="btn-primary px-4 py-2 rounded">Ver producto</a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Servicios destacados dinámicos --}}
    <div class="mb-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-primary">Servicios Destacados</h2>
            <a href="{{ route('servicios') }}" class="text-primary hover:underline">Ver todos</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($featuredServices as $service)
                <div class="bg-white rounded-lg shadow p-4 flex flex-col items-center">
                    <img src="{{ $service->image }}" class="w-32 h-32 object-cover mb-2 rounded">
                    <h3 class="font-semibold mb-1">{{ $service->name }}</h3>
                    <span class="text-blue-600 font-bold mb-2">Desde ${{ number_format($service->price, 0) }}</span>
                    <a href="{{ route('servicios') }}" class="btn-primary px-4 py-2 rounded">Ver servicio</a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Llamado a la acción final --}}
    <div class="bg-primary text-white rounded-lg p-8 text-center mt-10">
        <h2 class="text-2xl font-bold mb-2">¡Descubre todo lo que tenemos para ti!</h2>
        <p class="mb-4">Explora productos, servicios y promociones exclusivas para ti y tu familia.</p>
        <a href="{{ route('productos') }}" class="btn-secondary px-6 py-3 rounded text-lg font-semibold">Ir a productos</a>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.hero-slide');
            const dots = document.querySelectorAll('.hero-dot');
            const prevBtn = document.getElementById('heroPrev');
            const nextBtn = document.getElementById('heroNext');
            let current = 0;
            let interval = null;

            if (!slides.length) return;

            function showSlide(idx) {
                slides.forEach((slide, i) => {
                    if (i === idx) {
                        slide.classList.remove('hidden');
                    } else {
                        slide.classList.add('hidden');
                    }
                });
                dots.forEach((dot, i) => {
                    dot.classList.toggle('bg-primary', i === idx);
                    dot.classList.toggle('bg-gray-300', i !== idx);
                });
                current = idx;
            }

            function nextSlide() {
                showSlide((current + 1) % slides.length);
            }
            function prevSlide() {
                showSlide((current - 1 + slides.length) % slides.length);
            }
            function startAuto() {
                interval = setInterval(nextSlide, 3000);
            }
            function stopAuto() {
                if (interval) clearInterval(interval);
            }

            if (nextBtn && prevBtn) {
                nextBtn.addEventListener('click', function() {
                    stopAuto(); nextSlide(); startAuto();
                });
                prevBtn.addEventListener('click', function() {
                    stopAuto(); prevSlide(); startAuto();
                });
            }
            dots.forEach((dot, i) => {
                dot.addEventListener('click', function() {
                    stopAuto(); showSlide(i); startAuto();
                });
            });

            showSlide(0);
            startAuto();
        });
    </script>
@endpush
