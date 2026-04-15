<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
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

        // 1. Super-acesso para Cradt e Diretor Geral
        if (in_array($user->role, ['Cradt', 'Diretor Geral'])) {
            return $next($request);
        }

        if (in_array('Diretor Geral', $roles)) {
            $isDynamic = Role::where('label', $user->role)->exists();
            if ($isDynamic) {
                return $next($request);
            }
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        abort(403, 'Acesso negado. Você não tem permissão para acessar esta página.');
    }
}