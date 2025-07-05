<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

        // TODO: existen conflictos entre el inicio de sesion en web y en aplicacion, modificar para que si ya existe una sesion retorne unicamente la informacion y no cree una nueva, los tokens se eliminan despues de 24 horas
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
        $user = User::where('username', $request->username)->first();

        // Verificar si ya existe un token para el dispositivo
        $existingToken = $user->tokens()
            ->where('name', $request->device_name)
            ->first();

        if ($existingToken) {
            // Si el token existe, retornarlo en lugar de crear uno nuevo
            return response()->json([
                'success' => true,
                'token' => $existingToken->plainTextToken,
                'user' => $user,
                'token_type' => 'Bearer',
                'message' => 'Sesión ya activa para este dispositivo'
            ]);
        }
        // Si no existe un token, crear uno nuevo
        $token = $user->createToken($request->device_name, ['*'],  expiresAt: Carbon::now()->addHours(2))->plainTextToken;

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
