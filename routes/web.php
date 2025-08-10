<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Página principal
Route::get('/', function () {
    return view('welcome');
});

// Vista de productos destacados
Route::get('/productos', function () {
    return view('productos'); 
});

// Otras rutas
Route::get('/launchix-ecommerce', function () {
    return view('welcome');
});

Route::get('/launchix-ecommerse', function () {
    return view('welcome');
});