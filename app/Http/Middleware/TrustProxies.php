<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Defina aqui os IPs dos seus proxies confiáveis ou use '*' para confiar em todos (menos seguro).
     * Pode ser configurado via .env (TRUSTED_PROXIES).
     *
     * @var array<int, string>|string|null
     */
    protected $proxies; // Deixe null para usar a configuração do .env ou defina explicitamente

    /**
     * The headers that should be used to detect proxies.
     * Define quais cabeçalhos HTTP indicam que a requisição passou por um proxy.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB; // Usar Request::HEADER_X_FORWARDED_AWS_ELB se estiver na AWS
        // ou Request::HEADER_X_FORWARDED_ALL para todos os cabeçalhos padrão
}