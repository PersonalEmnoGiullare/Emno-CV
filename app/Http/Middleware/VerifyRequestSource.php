<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware para verificar el origen de las peticiones HTTP.
 * Permite solicitudes desde dominios web específicos y aplicaciones móviles con firma criptográfica.
 */
class VerifyRequestSource
{
    /**
     * Maneja la solicitud HTTP y verifica su origen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Obtener encabezados relevantes
        $origin = $request->header('Origin');
        $deviceType = $request->header('X-Device-Type'); // 'mobile', 'desktop', 'embedded'
        $signature = $request->header('X-App-Signature');

        // TODO: Actualizar la lista blanca de dominios web permitidos al pasar a producción
        // Lista blanca de dominios web permitidos
        $allowedWebOrigins = [
            'localhost',
            'localhost:8000',
            'http://localhost:8000',
            'http://127.0.0.1:8000',
            '127.0.0.1:8000'
        ];

        // Validación para peticiones web
        if ($origin && in_array($origin, $allowedWebOrigins)) {
            // Verificación adicional para web (CSRF)
            if (!$request->expectsJson() && !$request->isMethod('GET')) {
                abort_unless(
                    $request->session()->token() === $request->header('X-CSRF-TOKEN'),
                    419,
                    'Token CSRF inválido'
                );
            }
            return $next($request);
        }

        // Validación de firma para todos los dispositivos no-web
        if ($deviceType && $signature) {
            $validSignature = match ($deviceType) {
                'mobile' => hash_hmac('sha256', 'MOBILE_APP_SALT', env('MOBILE_SECRET_KEY')),
                'desktop' => hash_hmac('sha256', 'DESKTOP_APP_SALT', env('DESKTOP_SECRET_KEY')),
                'embedded' => hash_hmac('sha256', 'EMBEDDED_APP_SALT', env('EMBEDDED_SECRET_KEY')),
                default => null
            };

            abort_unless(
                $validSignature && hash_equals($validSignature, $signature),
                401,
                'Firma inválida para este tipo de dispositivo'
            );

            return $next($request);
        }

        // Denegar acceso si no cumple con ninguna validación
        abort(403, 'Origen no autorizado');
    }
}
