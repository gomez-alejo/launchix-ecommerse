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

/**
 * ===================== RUTAS PRIVADAS Y AJAX DE SERVICIOS EMPRENDEDOR =====================
 */

// Dashboard para el emprendedor (con modales y JS)
Route::get('/entrepreneur/services', function () {
    return view('modals.login-items.entrepreneur.ServicesSection');
})->middleware('auth:entrepreneur')->name('entrepreneur.services');

// AJAX: Listar mis servicios
Route::get('/mis-servicios', [ServicioController::class, 'misServicios'])->middleware('auth:entrepreneur');

// AJAX: Ver detalles de un servicio
Route::get('/servicios/{id}', [ServicioController::class, 'show'])->middleware('auth:entrepreneur');

// AJAX: Editar servicio (POST con _method=PUT)
Route::post('/servicios/{id}', [ServicioController::class, 'update'])->middleware('auth:entrepreneur');

// AJAX: Eliminar servicio
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->middleware('auth:entrepreneur');

/**
 * ===================== RUTAS PÚBLICAS =====================
 */

// Página de inicio dinámica
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Productos
Route::get('/productos', [ProductController::class, 'index'])->name('productos');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('productos.show');
Route::get('/masvendidos', [ProductController::class, 'bestSellers'])->name('masvendidos');

// Servicios (catálogo público)
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios');
// Publicar servicio (solo emprendedores autenticados)
Route::post('/servicios', [ServicioController::class, 'store'])->middleware('auth:entrepreneur')->name('servicios.store');

// Vista estática de servicios (si realmente la usas)
Route::get('/services', function () {
    return view('services');
})->name('services');

// Carrito de compras
Route::get('/shoppingCart', function () {
    return view('shoppingcart');
})->name('shoppingCart');

// Perfil usuario
Route::get('/user', function () {
    return view('profiles.user');
})->name('user');

// Perfil emprendedor
Route::get('/entrepreneur', function () {
    return view('profiles.entrepreneur');
})->name('entrepreneur');

/**
 * ===================== AUTENTICACIÓN =====================
 */

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

/**
 * ===================== API/REST (comentadas, mover a api.php si las usas) =====================
 */
// Route::get('/api/productos', [ProductController::class, 'apiIndex'])->name('productos.api');
// Route::get('/api/productos/search', [ProductController::class, 'search'])->name('productos.search');
// Route::prefix('api')->group(function () {
//     Route::get('/productos', [ProductController::class, 'publicIndex']);
//     Route::get('/productos/{id}', [ProductController::class, 'publicShow']);
//     Route::post('/productos/search', [ProductController::class, 'search']);
// });
// Route::post('/productos', [ProductController::class, 'store'])->name('productos.store');
// Route::put('/productos/{id}', [ProductController::class, 'update'])->name('productos.update');
// Route::patch('/productos/{id}', [ProductController::class, 'update'])->name('productos.patch');
// Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->name('productos.destroy');
