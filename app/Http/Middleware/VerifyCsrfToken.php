<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * Adicione aqui URIs (ex: rotas de API de webhook) que não precisam de token CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'stripe/*',
        // 'api/*', // Exemplo para excluir todas as rotas de API
    ];
}