<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Registrar nuevo usuario
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'city' => $request->city,
            'department' => $request->department,
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente',
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    /**
     * Iniciar sesión
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        // Revocar tokens anteriores si se desea (opcional)
        if ($request->revoke_other_tokens) {
            $user->tokens()->delete();
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => new UserResource($user),
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * Obtener usuario autenticado
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        // Manejo de include dinámico: ?include=favorites,cart,roles,entrepreneur
        $allowedIncludes = [
            'favorites.favoritable',
            'cart',
            'roles',
            'entrepreneur',
            'products',
            'services',
        ];

        $requestedIncludes = collect(explode(',', (string) $request->query('include')))
            ->map(fn($i) => trim($i))
            ->filter()
            ->map(function($i) {
                // Normalizar nombres simples a relaciones reales
                return match($i) {
                    'favorites' => 'favorites.favoritable',
                    'cart' => 'cart',
                    'roles' => 'roles',
                    'entrepreneur' => 'entrepreneur',
                    'products' => 'products',
                    'services' => 'servicios', // en el modelo es servicios()
                    default => $i,
                };
            })
            ->filter(fn($i) => in_array($i, $allowedIncludes))
            ->values();

        if ($requestedIncludes->isNotEmpty()) {
            // Intentar cargar relaciones válidas, ignorando errores silenciosamente
            try {
                $user->load($requestedIncludes->all());
            } catch (\Throwable $e) {
                // Registrar pero no romper la respuesta
                Log::warning('Error cargando includes en /me', [
                    'requested' => $requestedIncludes,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'message' => 'Usuario autenticado',
            'data' => new UserResource($user),
            'included' => $requestedIncludes,
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request): JsonResponse
    {
        // Revocar token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    /**
     * Revocar todos los tokens del usuario
     */
    public function revokeAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Todos los tokens han sido revocados'
        ]);
    }
}
