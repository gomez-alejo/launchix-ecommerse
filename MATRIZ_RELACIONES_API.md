# 📊 MATRIZ COMPLETA DE RELACIONES - E-COMMERCE API

## ✅ RELACIONES IMPLEMENTADAS Y VERIFICADAS

### 👤 USER COMO CENTRO DEL SISTEMA

```
User (usuarios/compradores/emprendedores)
├─ hasOne(Cart) ✅
├─ hasMany(Order) ✅
├─ hasMany(Addresses) ✅
├─ hasMany(reviews) ✅
├─ hasMany(Product) ✅ [si es emprendedor]
├─ hasMany(Servicio) ✅
├─ hasMany(Favorite) ✅ [NUEVO]
├─ hasMany(payments) ✅
└─ belongsToMany(Role) ✅
```

### 🏪 ENTREPRENEUR (VENDEDORES)

```
Entrepreneur
├─ hasMany(Product) ✅
└─ hasMany(Servicio) ✅
```

### 📦 PRODUCT (PRODUCTOS)

```
Product
├─ belongsTo(Entrepreneur) ✅
├─ belongsTo(User) ✅
├─ belongsTo(Category) ✅
├─ belongsToMany(Category) ✅ [many-to-many]
├─ hasMany(reviews) ✅
├─ hasMany(Product_image) ✅
├─ hasMany(Cart_Item) ✅
├─ hasMany(Order_Item) ✅
└─ morphMany(Favorite) ✅ [NUEVO]
```

### 🛠️ SERVICIO (SERVICIOS)

```
Servicio
├─ belongsTo(Entrepreneur) ✅
├─ belongsTo(User) ✅
├─ hasMany(reviews) ✅ [NUEVO]
└─ morphMany(Favorite) ✅ [NUEVO]
```

### 🛒 CART & CART_ITEM

```
Cart
├─ belongsTo(User) ✅
└─ hasMany(Cart_Item) ✅

Cart_Item
├─ belongsTo(Cart) ✅
└─ belongsTo(Product) ✅
```

### 📋 ORDER & ORDER_ITEM

```
Order
├─ belongsTo(User) ✅
├─ hasMany(Order_Item) ✅
├─ hasMany(payments) ✅
├─ hasMany(shipments) ✅
└─ belongsTo(Addresses) ✅ [dirección de envío]

Order_Item
├─ belongsTo(Order) ✅
└─ belongsTo(Product) ✅
```

### ⭐ FAVORITES (NUEVO SISTEMA)

```
Favorite (Polimórfico)
├─ belongsTo(User) ✅
└─ morphTo(favoritable) ✅ [Product|Servicio]
```

### 💳 PAYMENTS & SHIPMENTS

```
payments
├─ belongsTo(Order) ✅
└─ belongsTo(User) ✅

shipments
├─ belongsTo(Order) ✅
└─ belongsTo(Addresses) ✅
```

### 🏷️ CATEGORÍAS Y CLASIFICACIÓN

```
Category
├─ hasMany(Product) ✅
├─ belongsToMany(Product) ✅ [many-to-many]
└─ hasMany(Servicio) ✅

Product_category (Tabla Pivote)
├─ belongsTo(Product) ✅
└─ belongsTo(Category) ✅
```

### ⭐ REVIEWS

```
reviews
├─ belongsTo(Product) ✅
├─ belongsTo(Servicio) ✅ [NUEVO]
└─ belongsTo(User) ✅
```

## 🎯 SCOPES DISPONIBLES PARA LA API

### Product Scopes
```php
Product::active()                    // Productos activos
Product::featured()                  // Productos destacados  
Product::inStock()                   // Con stock disponible
Product::onSale()                    // En promoción
Product::topRated(4.0)              // Mejor calificados
Product::recent(30)                  // Últimos 30 días
Product::priceRange(100, 500)       // Rango de precio
Product::search('zapatos')           // Búsqueda por nombre/descripción
Product::popular()                   // Ordenados por vistas
```

### Servicio Scopes
```php
Servicio::active()                   // Servicios activos
Servicio::byCategory('limpieza')     // Por categoría
Servicio::byCity('Bogotá')          // Por ciudad
Servicio::priceRange(50, 200)       // Rango de precio
Servicio::search('plomería')        // Búsqueda
Servicio::recent(30)                 // Recientes
```

### User Scopes
```php
User::active()                       // Usuarios activos
User::byCity('Medellín')            // Por ciudad
User::byDepartment('Antioquia')     // Por departamento
User::search('juan')                // Búsqueda por nombre/email
User::verified()                     // Email verificado
```

### Favorite Scopes
```php
Favorite::products()                 // Solo favoritos de productos
Favorite::services()                 // Solo favoritos de servicios
Favorite::byUser(123)               // De un usuario específico
```

## 🚀 CONSULTAS OPTIMIZADAS LISTAS PARA API

### Productos con Relaciones
```php
// Producto completo con todas sus relaciones
Product::with([
    'entrepreneur',
    'user', 
    'category',
    'images',
    'reviews.user',
    'favorites'
])->active()->paginate(20);

// Productos destacados con imágenes
Product::with('images')
    ->featured()
    ->inStock()
    ->orderBy('views', 'desc')
    ->take(10);
```

### Servicios con Emprendedor
```php
// Servicios activos con información del creador
Servicio::with(['entrepreneur', 'user'])
    ->active()
    ->byCategory('limpieza')
    ->paginate(20);
```

### Favoritos de Usuario
```php
// Todos los favoritos de un usuario
User::find(1)
    ->favorites()
    ->with('favoritable')
    ->get();

// Solo productos favoritos
User::find(1)
    ->favorites()
    ->products()
    ->with('favoritable.images')
    ->get();
```

## 🎯 ENDPOINTS DE API SUGERIDOS

### Productos
```
GET /api/products                    // Listar con filtros
GET /api/products/featured           // Destacados
GET /api/products/on-sale            // En oferta
GET /api/products/{id}               // Detalle
POST /api/products                   // Crear [Auth]
PUT /api/products/{id}               // Actualizar [Auth]
DELETE /api/products/{id}            // Eliminar [Auth]
```

### Servicios
```
GET /api/services                    // Listar con filtros
GET /api/services/by-category/{cat}  // Por categoría
GET /api/services/{id}               // Detalle
POST /api/services                   // Crear [Auth]
PUT /api/services/{id}               // Actualizar [Auth]
DELETE /api/services/{id}            // Eliminar [Auth]
```

### Favoritos
```
GET /api/favorites                   // Mis favoritos [Auth]
POST /api/favorites                  // Agregar favorito [Auth]
DELETE /api/favorites/{id}           // Quitar favorito [Auth]
```

## ✅ ESTADO: COMPLETAMENTE PREPARADO

**🎉 La estructura está 100% lista para implementar la API REST con:**
- ✅ Relaciones bidireccionales completas
- ✅ Scopes avanzados para filtrado
- ✅ Optimizaciones de performance
- ✅ Integridad referencial garantizada
- ✅ Funcionalidad web preservada
- ✅ Sistema de favoritos implementado
- ✅ Soft deletes donde es necesario
- ✅ Accessors para cálculos automáticos
