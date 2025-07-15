<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {

        // Verificar si el usuario a iniciado sesion
        if (!Auth::check()) {
            // Si la peticion es de tipo json, retornar un error 401
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autenticado',
                    'error_code' => 'UNAUTHENTICATED'
                ], 401);
            }
            // Si no ha iniciado sesion, redirigir al login
            return redirect()->route('login');
        }

        // obterner el usuario
        $user = Auth::user();

        // convertir en ulista los roles recibidos
        $roles_permitidos = explode('-', $roles);

        // ferificar si el rol se encuentra o no dentro de la lista de permitidos
        if (!in_array($user->rol, $roles_permitidos)) {

            // Si la peticion es de tipo json, retornar un error 403
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No autorizado',
                    'error_code' => 'UNAUTHORIZED'
                ], 403);
            }

            // cerrar sesion
            // Auth::logout();
            // invalidar la sesion generada
            $request->session()->invalidate();
            // reestablecer los tokens generados para la sesion
            $request->session()->regenerateToken();
            // mandar pagina de error 403
            abort(403, 'No tienes permiso para acceder a esta pagina.');
        }

        return $next($request);
    }
}
