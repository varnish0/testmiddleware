<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAge
{
    /**
     * Edad mínima para entrar en el casino.
     */
    public const int MINIMUM_AGE = 18;

    /**
     * Redirige a los menores de edad a su panel de usuario.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (($request->user()?->age ?? 0) < self::MINIMUM_AGE) {
            return redirect()->route('panel')
                ->with('error', 'Acceso denegado: Debes ser mayor de edad');
        }

        return $next($request);
    }
}
