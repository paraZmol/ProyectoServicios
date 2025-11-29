<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // verificar autentificacion
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        $userRole = $user->role;

        $requiredRoles = explode('|', $roles);

        if (! in_array($userRole, $requiredRoles)) {
            abort(403, 'Acceso denegado. No tienes permiso para ver esta sección.');
        }

        return $next($request);
    }
}
