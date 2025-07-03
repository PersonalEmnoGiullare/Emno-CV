<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Funcion para mostarar la vista del controlador
    public function showLoginForm()
    {
        return view("login");
    }

    // fucnion para la logica del inicio de sesion
    public function login(Request $request)
    {
        // validacion de los datos recividos
        // validando los datos
        $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string',
        ]);

        // recuperra el credenciales
        $credenciales = [
            'username' => $request->get('username'),
            'password' => $request->get("password")
        ];

        // REvisar credenciales
        if (Auth::attempt($credenciales)) {

            // generar una sesion.
            $request->session()->regenerate();

            // Genera token Sanctum para API
            $token = $request->user()->createToken('web_token')->plainTextToken;

            // Guarda el token en la sesión para usarlo en Blade
            session(['api_token' => $token]);

            // redirigimos al usuario a la ruta que queria acceder
            return redirect()->intended('/');
        }

        // si las credenciales no corresponden, debemos de recargar el login con mensajes de error
        return back()->withErrors([
            "username" => "El nombre de usuario o contraseña no son validos."
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        // cerrar sesion
        Auth::logout();
        // invalidar la sesion generada
        $request->session()->invalidate();
        // reestablecer los tokens generados para la sesion
        $request->session()->regenerateToken();
        // redirigir hacia el loginQ
        return redirect('/login');
    }
}
