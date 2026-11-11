<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Deniega el acceso (403) a cualquier usuario que no sea administrador.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->is_admin) {
            abort(403, 'Acceso denegado: solo la dirección puede entrar en esta oficina.');
        }

        return $next($request);
    }
}
