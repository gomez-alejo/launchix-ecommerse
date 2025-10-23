<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Frontend externo (Laravel) consumiendo este backend

Este proyecto, en la rama `Guillermo-Gomez`, está preparado para funcionar como backend y ser consumido por un frontend independiente (otro proyecto Laravel o SPA).

### 1) Variables de entorno (backend)

Configura el archivo `.env` de este backend con los orígenes permitidos y URLs:

```
APP_URL=http://localhost:8001
FRONTEND_URL=http://localhost:8000
CORS_ALLOWED_ORIGINS=http://localhost:8000,http://127.0.0.1:8000

# Si alguna vez usas cookies (Sanctum SPA), configura también:
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1,localhost:8000,127.0.0.1:8000
SESSION_DOMAIN=localhost
```

En local puedes levantar este backend en `php artisan serve --host=127.0.0.1 --port=8001` para mantener separado del frontend.

### 2) CORS en backend

El archivo `config/cors.php` permite configurar orígenes vía `CORS_ALLOWED_ORIGINS` y `FRONTEND_URL`. Si el frontend corre en un puerto distinto (por ejemplo 5173 para Vite), agrégalo a `CORS_ALLOWED_ORIGINS`.

### 3) Autenticación recomendada

Este backend usa Laravel Sanctum con tokens personales (Bearer). Flujo básico:

1. El frontend hace `POST /api/v1/login` con email y password.
2. La respuesta incluye `access_token` y `token_type` = `Bearer`.
3. El frontend almacena el token (por sesión) y lo envía en el header `Authorization: Bearer {token}` en cada request protegido.

Endpoints clave:

- `POST /api/v1/register`
- `POST /api/v1/login`
- `GET /api/v1/me` (requiere Bearer)
- `POST /api/v1/logout` (requiere Bearer)

Productos (públicos):

- `GET /api/v1/products`
- `GET /api/v1/products/{id}`
- `GET /api/v1/products/featured`
- `GET /api/v1/products/popular`

Productos (protegidos, requieren Bearer):

- `POST /api/v1/products`
- `PUT /api/v1/products/{id}`
- `DELETE /api/v1/products/{id}`

### 4) Ejemplos desde frontend Laravel

En el frontend, define las variables en `.env`:

```
API_BASE_URL=http://127.0.0.1:8001
```

Ejemplo usando el cliente HTTP de Laravel (facade `Http`):

```php
use Illuminate\Support\Facades\Http;

// Login
$response = Http::post(env('API_BASE_URL').'/api/v1/login', [
	'email' => $request->email,
	'password' => $request->password,
]);

if ($response->successful()) {
	$token = $response['access_token'];
	// Guardar en sesión
	session(['api_token' => $token]);
}

// Request autenticado
$token = session('api_token');
$products = Http::withToken($token)
	->get(env('API_BASE_URL').'/api/v1/products')
	->json();
```

Si utilizas Axios/Fetch en el frontend, solo agrega el header `Authorization: Bearer <token>`.

### 5) Puesta en marcha local

1. Backend
   - `composer install`
   - Configura `.env` (DB, APP_URL, CORS_ALLOWED_ORIGINS)
   - `php artisan migrate --seed` (si aplica)
   - `php artisan serve --host=127.0.0.1 --port=8001`

2. Frontend (otro Laravel)
   - Configura `.env` con `API_BASE_URL=http://127.0.0.1:8001`
   - Usa el cliente HTTP para consumir endpoints

Problemas comunes:

- 401 sin token: asegúrate de enviar `Authorization: Bearer ...` en rutas protegidas.
- CORS bloqueado: agrega el origen del frontend a `CORS_ALLOWED_ORIGINS` y limpia cache (`php artisan config:clear`).
- Cookie/Sanctum SPA: si decides usar cookies en lugar de Bearer, habilita `supports_credentials=true` en `config/cors.php`, configura `SANCTUM_STATEFUL_DOMAINS` y usa `sanctum/csrf-cookie` en el frontend.


## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
