<?php

use Illuminate\Support\Facades\Route;

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
