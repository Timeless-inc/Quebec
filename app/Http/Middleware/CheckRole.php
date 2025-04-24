<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) { 
            return redirect()->route('login');
        }

        $user = Auth::user(); 

        if ($user->role === 'Cradt') {
            return $next($request);
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Acesso negado. Você não tem permissão para acessar esta página.');
    }
}