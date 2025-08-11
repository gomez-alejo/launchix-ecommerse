<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});


// Listar productos
Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');

// Mostrar detalle de un producto
Route::get('/productos/{id}', [ProductController::class, 'show'])->name('productos.show');

// Mostrar formulario de edición de un producto
Route::get('/productos/{id}/edit', [ProductController::class, 'edit'])->name('productos.edit');

