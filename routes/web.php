<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\EntrepreneurAuthController;
use App\Http\Controllers\EntrepreneurController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EntrepreneurProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserAddressController;

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







// API de productos públicas
Route::get('/api/productos', [ProductController::class, 'apiIndex'])->name('productos.api');
Route::get('/api/productos/search', [ProductController::class, 'search'])->name('productos.search');

// Grupo de rutas API con prefijo
Route::prefix('api')->group(function () {
    Route::get('/productos', [ProductController::class, 'publicIndex']);
    Route::get('/productos/{id}', [ProductController::class, 'publicShow']);
    Route::post('/productos/search', [ProductController::class, 'search']);
});



/**
 * =====================================================
 * RUTAS DEL PERFIL DE USUARIO
 * =====================================================
 */

Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    
    // Vista del perfil
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    
    // Datos del usuario
    Route::get('/data', [ProfileController::class, 'getUserData'])->name('data');
    
    // Actualizar perfil (información personal)
    Route::post('/update', [ProfileController::class, 'update'])->name('update');
    
    // Cambiar contraseña
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('changePassword');
});

/**
 * =====================================================
 * RUTAS DE GESTIÓN DE DIRECCIONES
 * =====================================================
 */

Route::middleware('auth')->prefix('addresses')->name('addresses.')->group(function () {
    
    // Listar todas las direcciones del usuario
    Route::get('/', [UserAddressController::class, 'index'])->name('index');
    
    // Obtener dirección principal
    Route::get('/main', [UserAddressController::class, 'getMain'])->name('main');
    
    // Crear nueva dirección
    Route::post('/', [UserAddressController::class, 'store'])->name('store');
    
    // Ver dirección específica
    Route::get('/{id}', [UserAddressController::class, 'show'])->name('show');
    
    // Actualizar dirección específica
    Route::put('/{id}', [UserAddressController::class, 'update'])->name('update');
    
    // Eliminar dirección
    Route::delete('/{id}', [UserAddressController::class, 'destroy'])->name('destroy');
    
    // Establecer dirección como principal
    Route::post('/{id}/set-main', [UserAddressController::class, 'setMain'])->name('setMain');
});

Route::middleware('auth')->prefix('addresses')->name('addresses.')->group(function () {
    
    // Listar todas las direcciones del usuario
    Route::get('/', [UserAddressController::class, 'index'])->name('index');
    
    // Obtener dirección principal
    Route::get('/main', [UserAddressController::class, 'getMain'])->name('main');
    
    // Crear nueva dirección
    Route::post('/', [UserAddressController::class, 'store'])->name('store');
    
    // Ver dirección específica
    Route::get('/{id}', [UserAddressController::class, 'show'])->name('show');
    
    // Actualizar dirección específica
    Route::put('/{id}', [UserAddressController::class, 'update'])->name('update');
    
    // Eliminar dirección
    Route::delete('/{id}', [UserAddressController::class, 'destroy'])->name('destroy');
    
    // Establecer dirección como principal
    Route::post('/{id}/set-main', [UserAddressController::class, 'setMain'])->name('setMain');
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

use App\Http\Controllers\Entrepreneur\EntrepreneurAddressController;

/*
|--------------------------------------------------------------------------
| Rutas del Emprendedor
|--------------------------------------------------------------------------
|
| Aquí se definen las rutas para el panel del emprendedor.
| Todas las rutas están protegidas con el middleware 'auth:entrepreneur'
|
*/

Route::middleware(['auth:entrepreneur'])->prefix('entrepreneur')->name('entrepreneur.')->group(function () {
    
    // ============================================
    // RUTAS DEL PERFIL DEL NEGOCIO
    // ============================================
    
    // Obtener datos completos del perfil (negocio + dirección)
    Route::get('/profile/data', [EntrepreneurController::class, 'getData'])
        ->name('profile.data');
    
    // Actualizar información del negocio
    Route::post('/profile/update', [EntrepreneurController::class, 'update'])
        ->name('profile.update');
    
    // Gestión del logo del negocio
    Route::post('/profile/logo', [EntrepreneurController::class, 'uploadLogo'])
        ->name('profile.logo.upload');
    
    Route::delete('/profile/logo', [EntrepreneurController::class, 'deleteLogo'])
        ->name('profile.logo.delete');
    
    // ============================================
    // RUTAS DE DIRECCIONES
    // ============================================
    
    // Obtener dirección principal
    Route::get('/addresses/main', [EntrepreneurAddressController::class, 'getMain'])
        ->name('addresses.main');
    
    // Listar todas las direcciones
    Route::get('/addresses', [EntrepreneurAddressController::class, 'index'])
        ->name('addresses.index');
    
    // Crear o actualizar dirección principal
    Route::post('/addresses', [EntrepreneurAddressController::class, 'store'])
        ->name('addresses.store');
    
    // Actualizar dirección específica
    Route::put('/addresses/{id}', [EntrepreneurAddressController::class, 'update'])
        ->name('addresses.update');
    
    // Eliminar dirección
    Route::delete('/addresses/{id}', [EntrepreneurAddressController::class, 'destroy'])
        ->name('addresses.destroy');
    
    // Establecer dirección como principal
    Route::post('/addresses/{id}/set-main', [EntrepreneurAddressController::class, 'setAsMain'])
        ->name('addresses.set-main');
});