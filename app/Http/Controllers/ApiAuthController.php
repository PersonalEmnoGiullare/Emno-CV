<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {

        // Validar todos los campos en una sola operación
        $validatedData = $request->validate([
            'username' => 'required|string|exists:users,username',
            'password' => 'required|string',
            'device_name' => 'required|string|max:255'
        ], [
            // Mensajes personalizados
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.exists' => 'El nombre de usuario no existe en nuestros registros.',
            'password.required' => 'La contraseña es obligatoria.',
            'device_name.required' => 'El nombre del dispositivo es obligatorio.',
            'device_name.max' => 'El nombre del dispositivo no puede exceder 255 caracteres.'
        ]);

        // Extraer solo las credenciales para autenticación
        $credentials = $request->only(['username', 'password']);


        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials)) {
            // Si las credenciales son incorrectas, devolver un error 401
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas',
                'error_code' => 'INVALID_CREDENTIALS'
            ], 401);
        }

        // Si la autenticación es exitosa, generar un token de acceso
        // * Eliminar tokens anteriores
        $request->user()->tokens()->delete();
        $user = User::where('username', $request->username)->first();
        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente'
        ]);
    }
}
