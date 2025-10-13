<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// API V1 Routes
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Authentication Routes (Public)
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register')->name('auth.register');
        Route::post('/login', 'login')->name('auth.login');
    });

    // Public Product Routes (no authentication required)
    Route::controller(ProductController::class)->group(function () {
        Route::get('/products', 'index')->name('products.public.index');
        Route::get('/products/popular', 'popular')->name('products.public.popular');
        Route::get('/products/featured', 'featured')->name('products.public.featured');
        Route::get('/products/{product}', 'show')->name('products.public.show');
        Route::get('/products/{product}/related', 'related')->name('products.public.related');
    });

    // Protected Routes
    Route::middleware('auth:sanctum')->group(function () {

        // Auth Routes (Protected)
        Route::controller(AuthController::class)->group(function () {
            Route::get('/me', 'me')->name('auth.me');
            Route::post('/logout', 'logout')->name('auth.logout');
            Route::post('/logout-all', 'revokeAll')->name('auth.logout-all');
        });

        // Products API Routes (Authenticated only)
        Route::post('products', [ProductController::class, 'store'])
            ->name('products.store');
        Route::put('products/{product}', [ProductController::class, 'update'])
            ->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->name('products.destroy');
        Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])
            ->name('products.toggle-status');
        Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])
            ->name('products.toggle-featured');

        // Services API Routes
        Route::apiResource('servicios', ServicioController::class);
        Route::patch('servicios/{servicio}/toggle-status', [ServicioController::class, 'toggleStatus'])
            ->name('servicios.toggle-status');

        // Future API endpoints will go here:
        // Route::apiResource('categories', CategoryController::class);
        // Route::apiResource('entrepreneurs', EntrepreneurController::class);
        // Route::apiResource('orders', OrderController::class);

    });
});

/*
|--------------------------------------------------------------------------
| Legacy API Routes (if needed for compatibility)
|--------------------------------------------------------------------------
*/

// Uncomment if you need to maintain backward compatibility
// Route::middleware('auth:sanctum')->group(function () {
//     Route::apiResource('servicios', ServicioController::class);
//     Route::patch('servicios/{servicio}/toggle-status', [ServicioController::class, 'toggleStatus']);
// });
