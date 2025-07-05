<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Lista blanca de orígenes permitidos
        $allowedOrigins = [
            'https://emno.net',
            'https://www.emno.net',
            'https://api.emno.net',
            'capacitor://localhost',
            'http://localhost',
            'http://localhost:8000',
            'http://127.0.0.1:8000/',
            '127.0.0.1:8000/'
        ];

        $origin = $request->header('Origin');

        $response = $next($request);

        if (in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', 'POST, GET, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-CSRF-TOKEN, X-App-Signature');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}
