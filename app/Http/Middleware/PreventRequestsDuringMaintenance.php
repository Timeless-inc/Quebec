<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     * Adicionar URIs que devem funcionar mesmo em modo de manutenção.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}