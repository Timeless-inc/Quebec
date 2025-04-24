<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     * Adicionem aqui nomes de cookies que não devem ser criptografados, se houver.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}