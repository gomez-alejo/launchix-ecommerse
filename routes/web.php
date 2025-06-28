<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/products', function () {
    return view('products');
})->name('products');

Route::get('/services', function () {
    return view('services');
})->name('services');
    