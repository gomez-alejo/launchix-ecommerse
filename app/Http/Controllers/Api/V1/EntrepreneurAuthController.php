<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Entrepreneur;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class EntrepreneurAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:entrepreneurs,email',
            'password'   => 'required|min:6',
        ]);

        $entrepreneur = Entrepreneur::create([
            'first_name' => $validated['first_name'],
            'last_name'  => $validated['last_name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
        ]);

        $token = $entrepreneur->createToken('entrepreneur-auth')->plainTextToken;

        return response()->json([
            'message' => 'Emprendedor registrado',
            'entrepreneur' => [
                'id' => $entrepreneur->id,
                'first_name' => $entrepreneur->first_name,
                'last_name' => $entrepreneur->last_name,
                'email' => $entrepreneur->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $entrepreneur = Entrepreneur::where('email', $validated['email'])->first();

        if (!$entrepreneur || !Hash::check($validated['password'], $entrepreneur->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas.'],
            ]);
        }

        $token = $entrepreneur->createToken('entrepreneur-auth')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'entrepreneur' => [
                'id' => $entrepreneur->id,
                'first_name' => $entrepreneur->first_name,
                'last_name' => $entrepreneur->last_name,
                'email' => $entrepreneur->email,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $authUser = $request->user();

        // Asegura que el token pertenezca a un Emprendedor
        if (!$authUser instanceof Entrepreneur) {
            return response()->json([
                'message' => 'No autorizado: el token no pertenece a un emprendedor.'
            ], 403);
        }

        return response()->json([
            'message' => 'Emprendedor autenticado',
            'data' => [
                'id' => $authUser->id,
                'first_name' => $authUser->first_name,
                'last_name' => $authUser->last_name,
                'email' => $authUser->email,
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $authUser = $request->user();

        if (!$authUser instanceof Entrepreneur) {
            return response()->json([
                'message' => 'No autorizado: el token no pertenece a un emprendedor.'
            ], 403);
        }

        $token = $authUser->currentAccessToken();
        if ($token instanceof PersonalAccessToken) {
            $token->delete();
        }
        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $authUser = $request->user();

        if (!$authUser instanceof Entrepreneur) {
            return response()->json([
                'message' => 'No autorizado: el token no pertenece a un emprendedor.'
            ], 403);
        }

        $authUser->tokens()->delete();
        return response()->json(['message' => 'Todas las sesiones han sido cerradas exitosamente']);
    }
}
