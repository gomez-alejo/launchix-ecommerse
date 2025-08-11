<!-- resources/views/productos/show.blade.php -->
<!-- resources/views/productos/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lista de Productos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

    <style>
        body {
            background-color: #f9fafb;
        }
        h1 {
            color: #2563EB;
            font-weight: bold;
        }
        .card {
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .card-img-top {
            object-fit: cover;
            height: 200px;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            transition: transform 0.3s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .btn-ver-mas {
            background-color: #2563EB;
            color: white;
            border: none;
            font-weight: 500;
            transition: background-color 0.3s ease;
        }
        .btn-ver-mas:hover {
            background-color: #1E40AF;
        }
        .precio {
            font-size: 1.1rem;
            color: #16a34a;
            font-weight: bold;
        }
        .card-footer {
            background-color: transparent;
            border-top: none;
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
                        <img src="{{ asset('images/productos/' . imagenPorProducto($product->name)) }}"
                             class="card-img-top"
                             alt="{{ $product->name }}">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text flex-grow-1">{{ Str::limit($product->description, 100, '...') }}</p>
                            <p class="precio"> ${{ number_format($product->price, 2) }}</p>
                        </div>

                        <div class="card-footer text-center">
                            <a href="{{ route('productos.show', $product->id) }}" class="btn btn-ver-mas btn-sm">
                                <i class="fas fa-eye"></i> Ver más
                            </a>
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