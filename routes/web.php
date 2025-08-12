<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\EntrepreneurAuthController;

use App\Http\Controllers\ServicioController;
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



// Rutas para servicios
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');
Route::get('/servicios/{id}', [ServicioController::class, 'show'])->name('servicios.show');
Route::post('/servicios', [ServicioController::class, 'store'])->name('servicios.store');
Route::put('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.update');
Route::patch('/servicios/{id}', [ServicioController::class, 'update'])->name('servicios.patch');
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->name('servicios.destroy');


