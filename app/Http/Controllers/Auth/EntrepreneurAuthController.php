<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:entrepreneurs',
            'password'   => 'required|confirmed|min:6',
        ]);

        $entrepreneur = Entrepreneur::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => bcrypt($request->password),
        ]);

        Auth::guard('entrepreneur')->login($entrepreneur);
        return redirect()->route('entrepreneur'); // ajusta según tu app
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('entrepreneur')->attempt($credentials)) {
            return redirect()->route('entrepreneur');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        Auth::guard('entrepreneur')->logout();
        return redirect()->route('home');
    }
}
