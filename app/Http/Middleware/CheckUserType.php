<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserType;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$userTypes): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403); //Usuario no autenticado
        }

        //Se compara el tipo de usuario
        $tipoUsuarioActual = $user->tipo_usuario->value ?? null;

        if (!in_array($tipoUsuarioActual, $userTypes)) {
            abort(403, 'Acceso no autorizado');
        }


        return $next($request);
    }
}
