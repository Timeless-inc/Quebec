<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Importando a classe Carbon para manipulação de datas

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

        // Define a variável $data com a data atual, formatada
        $datas = Carbon::now()->format('d/m/Y');  // Aqui estamos formatando para 'd/m/Y'

        // Outras variáveis para passar à view
        $nome = $user->name;
        $matricula = $user->matricula;
        $email = $user->email;
        $cpf = $user->cpf;
        $andamento = 'Pendência';  // Exemplo de status de andamento
        $anexos = ['anexo1.pdf', 'anexo2.pdf'];  // Exemplo de anexos
        $observacoes = 'Observações sobre a falta';  // Exemplo de observações

        // Retorna a view passando as variáveis para o componente
        return view('cradt.index', compact(
            'nome', 'matricula', 'email', 'cpf', 'datas', 'andamento', 'anexos', 'observacoes'
        ));
    }
}
