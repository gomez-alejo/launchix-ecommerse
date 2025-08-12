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

// Services Route
Route::get('/services', function () {
    return view('services');
})->name('servicios');

// Services Route
Route::get('/shoppingCart', function () {
    return view('shopingcart');
})->name('shoppingCart');

// users Route
Route::get('/user', function () {
    return view('profiles.user');
})->name('user');

Route::get('/entrepreneur', function () {
    return view('profiles.entrepreneur');
})->name('entrepreneur');


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




