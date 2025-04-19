<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Se a requisição espera JSON (API), não redireciona, retorna null (gerando erro 401).
        // Caso contrário, redireciona para a rota 'login'.
        return $request->expectsJson() ? null : route('login');
    }
}