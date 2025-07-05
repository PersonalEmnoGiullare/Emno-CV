<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RefreshTokenExpiration
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user && $user->currentAccessToken()) {
            $token = $user->currentAccessToken();

            // Verificar si el token está próximo a expirar (ej: menos de 2 horas)
            $expiresAt = $token->expires_at;
            $now = Carbon::now();

            if ($expiresAt && $expiresAt->diffInMinutes($now) < 10) {
                // Renovar el token por 2 horas más
                $token->update([
                    'expires_at' => $now->addHours(2)
                ]);
            }
        }

        return $next($request);
    }
}
