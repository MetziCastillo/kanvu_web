<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Comprobar que los datos sean válidos.
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        // 2. Intentar iniciar sesión.
        $authenticated = Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'enable' => true,
        ], $request->boolean('remember'));

        // 3. Si los datos no coinciden, regresar al formulario.
        if (! $authenticated) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales son incorrectas o la cuenta está deshabilitada.',
            ]);
        }

        // 4. Renovar la sesión por seguridad.
        $request->session()->regenerate();

        // 5. Entrar al dashboard.
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
