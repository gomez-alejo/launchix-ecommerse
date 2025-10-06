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
use App\Http\Controllers\EntrepreneurProfileController;


/**
 * ===================== RUTAS PÚBLICAS PRINCIPALES =====================
 */

// Página de inicio dinámica
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Bestsellers
Route::get('/bestsellers', function () {
    return view('bestsellers');
})->name('masvendidos'); 

// Productos - Rutas públicas
Route::get('/products', function () {
    return view('products');
})->name('productos');
Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/productos/{product}', [ProductController::class, 'show'])->name('productos.show');

// Servicios - Rutas públicas
Route::get('/services', function () {
    return view('services');
})->name('servicios');
Route::get('/servicios', [ServicioController::class, 'index'])->name('servicios.index');

// Carrito de compras
Route::get('/shoppingCart', function () {
    return view('shoppingcart');
})->name('shoppingCart');

/**
 * ===================== PERFILES DE USUARIO =====================
 */

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
 * ===================== RUTAS PRIVADAS EMPRENDEDOR =====================
 */

// Dashboard para el emprendedor (con modales y JS)
Route::get('/entrepreneur/services', function () {
    return view('modals.login-items.entrepreneur.ServicesSection');
})->middleware('auth:entrepreneur')->name('entrepreneur.services');

// Servicios - Operaciones CRUD para emprendedores
Route::post('/servicios', [ServicioController::class, 'store'])->middleware('auth:entrepreneur')->name('servicios.store');
Route::get('/mis-servicios', [ServicioController::class, 'misServicios'])->middleware('auth:entrepreneur');
Route::get('/servicios/{id}', [ServicioController::class, 'show'])->middleware('auth:entrepreneur');
Route::post('/servicios/{id}', [ServicioController::class, 'update'])->middleware('auth:entrepreneur');
Route::delete('/servicios/{id}', [ServicioController::class, 'destroy'])->middleware('auth:entrepreneur');

// Productos - Operaciones CRUD para emprendedores
Route::post('/productos', [ProductController::class, 'store'])->middleware('auth:entrepreneur')->name('productos.store');
Route::put('/productos/{id}', [ProductController::class, 'update'])->middleware('auth:entrepreneur')->name('productos.update');
Route::patch('/productos/{id}', [ProductController::class, 'update'])->middleware('auth:entrepreneur')->name('productos.patch');
Route::delete('/productos/{id}', [ProductController::class, 'destroy'])->middleware('auth:entrepreneur')->name('productos.destroy');

/**
 * ===================== PERFIL DE EMPRENDEDOR =====================
 */

Route::middleware(['auth:entrepreneur'])->group(function () {
    Route::get('/entrepreneur/profile', [EntrepreneurProfileController::class, 'show'])->name('entrepreneur.profile');
    Route::get('/entrepreneur/profile/data', [EntrepreneurProfileController::class, 'getEntrepreneurData']);
    Route::post('/entrepreneur/profile/update', [EntrepreneurProfileController::class, 'updateEntrepreneurProfile']);
    Route::post('/entrepreneur/profile/avatar', [EntrepreneurProfileController::class, 'updateEntrepreneurAvatar']);
    Route::delete('/entrepreneur/profile/avatar', [EntrepreneurProfileController::class, 'deleteEntrepreneurAvatar']);
     // Ruta para cambiar contraseña
    Route::post('/entrepreneur/password', [EntrepreneurProfileController::class, 'updatePassword']);
});

/**
 * ===================== API ROUTES =====================
 */

// API de productos públicas
Route::get('/api/productos', [ProductController::class, 'apiIndex'])->name('productos.api');
Route::get('/api/productos/search', [ProductController::class, 'search'])->name('productos.search');

// Grupo de rutas API con prefijo
Route::prefix('api')->group(function () {
    Route::get('/productos', [ProductController::class, 'publicIndex']);
    Route::get('/productos/{id}', [ProductController::class, 'publicShow']);
    Route::post('/productos/search', [ProductController::class, 'search']);
});

use App\Http\Controllers\ProfileController;

// Rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
    // Vista del perfil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    
    // API endpoints para el perfil
    Route::get('/api/profile/data', [ProfileController::class, 'getUserData'])->name('profile.data');
    Route::post('/api/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/api/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
});


//rutas del perfil de usuario
Route::get('/userProfile', function () {
    return view('modals.login-items.user.ProfileSection');
})->name('profile');

Route::get('/userOrders', function () {
    return view('modals.login-items.user.OrderSection');
})->name('orders');

Route::get('/userOrdersDetail', function () {
    return view('modals.login-items.user.OrderDetail');
})->name('ordersDetail');

Route::get('/userReviews', function () {
    return view('modals.login-items.user.ReviewsSection');
})->name('reviews');

Route::get('/userFollowed', function () {
    return view('modals.login-items.user.FollowedStore');
})->name('followed');

Route::get('/userHistory', function () {
    return view('modals.login-items.user.BrowsingHistory');
})->name('history');

Route::get('/userSettings', function () {
    return view('modals.login-items.user.SettingsSection');
})->name('settings');

// Rutas para la gestión de pedidos
Route::middleware('auth')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
});