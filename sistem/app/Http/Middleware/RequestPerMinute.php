<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class RequestPerMinute
{

    public function handle(Request $request, Closure $next)
    {
        // Identificador del "usuario" para el rate limit.
        $userId = $request->header('X-User-ID', $request->ip());

        $key = 'rpm:'.sha1($userId);
        $maxAttempts = 10; // máximo 10 peticiones
        $decaySeconds = 60; // por minuto

        // Si se ha alcanzado el límite, devolver 429 con Retry-After
        if (!RateLimiter::remaining($key, $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            Log::warning("Rate limit exceeded for {$userId}. Retry after {$retryAfter}s");

            return Response::json([
                'error' => 'Límite de peticiones excedido',
                'mensaje' => "Máximo {$maxAttempts} peticiones por minuto"
            ], 429)->header('Retry-After', $retryAfter);
        }

        // Registrar el intento
        RateLimiter::hit($key, $decaySeconds);

        return $next($request);
    }
}
