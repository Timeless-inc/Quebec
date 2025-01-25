<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class CradtController extends Controller
{

    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Verifica se o usuário está autenticado antes de autorizar
        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        // Aplica a Policy para verificar se é Cradt
        Gate::authorize('isCradt', $user);

        // Retorna a view caso passe na autorização
        return view('cradt.index');
    }
}