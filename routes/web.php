<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\EntrepreneurAuthController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;

// Página de inicio dinámica (usa el controlador, no una función anónima)
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Rutas públicas de productos
Route::get('/productos', [ProductController::class, 'index'])->name('productos');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('productos.show');
Route::get('/mas-vendidos', [ProductController::class, 'bestSellers'])->name('productos.masvendidos');
Route::get('/masvendidos', [ProductController::class, 'bestSellers'])->name('masvendidos'); // Alias para compatibilidad

// Ruta pública para la vista de servicios (SOLO ESTA)
Route::get('/servicios', [\App\Http\Controllers\ServicioController::class, 'index'])->name('servicios');

// Si tienes rutas protegidas para crear, editar, eliminar servicios, déjalas así:
Route::middleware(['auth'])->group(function () {
    Route::post('/servicios', [\App\Http\Controllers\ServicioController::class, 'store'])->name('servicios.store');
    Route::put('/servicios/{id}', [\App\Http\Controllers\ServicioController::class, 'update'])->name('servicios.update');
    Route::patch('/servicios/{id}', [\App\Http\Controllers\ServicioController::class, 'update'])->name('servicios.patch');
    Route::delete('/servicios/{id}', [\App\Http\Controllers\ServicioController::class, 'destroy'])->name('servicios.destroy');
});

// Rutas públicas de categorías
Route::get('/categorias', [CategoryController::class, 'index'])->name('categorias.index');

// Acciones protegidas (solo autenticados)
Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class)->except(['index', 'show']);
    Route::post('/comprar/{product}', [OrderController::class, 'store'])->name('order.store');
    // ...otras rutas protegidas...
    // Puedes agregar aquí las rutas protegidas de servicios si lo necesitas
});

// USER AUTH
Route::get('/login/user', [UserAuthController::class, 'showLogin'])->name('login.user');
Route::post('/login/user', [UserAuthController::class, 'login']);
Route::get('/register/user', [UserAuthController::class, 'showRegister'])->name('register.user');
Route::post('/register/user', [UserAuthController::class, 'register']);
Route::post('/logout/user', [UserAuthController::class, 'logout'])->name('logout.user');

// ENTREPRENEUR AUTH
Route::get('/login/entrepreneur', [EntrepreneurAuthController::class, 'showLogin'])->name('login.entrepreneur');
Route::post('/login/entrepreneur', [EntrepreneurAuthController::class, 'login']);
Route::get('/register/entrepreneur', [EntrepreneurAuthController::class, 'showRegister'])->name('register.entrepreneur');
Route::post('/register/entrepreneur', [EntrepreneurAuthController::class, 'register']);
Route::post('/logout/entrepreneur', [EntrepreneurAuthController::class, 'logout'])->name('logout.entrepreneur');




