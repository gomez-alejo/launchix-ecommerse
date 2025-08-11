<!-- resources/views/productos/index.blade.php -->
<!-- resources/views/productos/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .card-img-top {
            object-fit: cover;
            height: 180px;
        }
        /* Botón azul personalizado */
        .btn-ver-mas {
            background-color: #2563EB;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-ver-mas:hover,
        .btn-ver-mas:focus {
            background-color: #1E40AF;
            color: white;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <h1 class="mb-4 text-center">Lista de Productos</h1>

    @if($products->isEmpty())
        <div class="alert alert-warning text-center">No hay productos disponibles.</div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        {{-- Imagen del producto --}}
                        <img src="{{ asset('images/productos/' . imagenPorProducto($product->name)) }}" class="card-img-top" alt="{{ $product->name }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text flex-grow-1">{{ $product->description }}</p>
                            <p><strong>Precio:</strong> ${{ number_format($product->price, 2) }}</p>

                            <a href="{{ route('productos.show', $product->id) }}" class="btn btn-ver-mas btn-sm mt-auto">Ver más</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

{{-- Helper para seleccionar imagen basada en nombre --}}
@php
    function imagenPorProducto($nombre) {
        $mapa = [
            'Camiseta Deportiva' => 'camiseta.jpg',
            'Zapatillas Running' => 'zapatillas.jpg',
            'Botella de Agua' => 'botella.jpg',
            'Guantes de Gimnasio' => 'guantes.jpg',
            'Mochila Deportiva' => 'mochila.jpg',
        ];
        return $mapa[$nombre] ?? 'default.jpg';
    }
@endphp
{{-- Fin del helper --}}