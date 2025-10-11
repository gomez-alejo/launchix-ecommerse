<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Entrepreneur;

class EntrepreneurAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.entrepreneurs.login');
    }

    public function showRegister()
    {
        return view('auth.entrepreneurs.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'business_name' => 'required|string|max:255',
            'email'         => 'required|email|unique:entrepreneurs,email',
            'password'      => 'required|confirmed|min:8',
            'phone'         => 'nullable|string|max:20',
        ], [
            'business_name.required' => 'El nombre del negocio es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.unique' => 'Este correo ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        $entrepreneur = Entrepreneur::create([
            'business_name' => $request->business_name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'active'        => true,
            'verified'      => false,
        ]);

        Auth::guard('entrepreneur')->login($entrepreneur);
        
        return redirect('entrepreneur');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Ingresa un correo válido',
            'password.required' => 'La contraseña es obligatoria',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('entrepreneur')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            return redirect('entrepreneur');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout(Request $request)
    {
        Auth::guard('entrepreneur')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
            ->with('success', 'Sesión cerrada correctamente.');
    }
}