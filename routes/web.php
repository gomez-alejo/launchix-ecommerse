<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\EntrepreneurAuthController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\ProductController;

// Web Routes

// Home Route
Route::get('/home', function () {
    return view('home');
})->name('home'); // 


// Bestsellers Route
Route::get('/bestsellers', function () {
    return view('bestsellers');
})->name('masvendidos'); 

// Products Route
Route::get('/products', function () {
    return view('products');
})->name('productos');

// Services Route
Route::get('/services', function () {
    return view('services');
})->name('servicios');


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



















// Reemplaza las rutas actuales por estas:
Route::middleware(['auth:entrepreneur'])->group(function () {
    Route::get('/entrepreneur', [EntrepreneurController::class, 'index'])->name('entrepreneur');
    
    // Rutas de productos (SIN prefix para mantener compatibilidad)
    Route::get('/products', [ProductController::class, 'index'])->name('productos');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});



