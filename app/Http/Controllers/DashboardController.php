<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Exibe o conteúdo do dashboard.
     */
    public function index()
    {
        // Obtém o usuário autenticado
        $user = Auth::user();

        // Verifica se o usuário está autenticado
        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        // Define as variáveis para o dashboard
        $datas = Carbon::now()->format('d/m/Y');  // Formatação da data
        $nome = $user->name;
        $matricula = $user->matricula;
        $email = $user->email;
        $cpf = $user->cpf;
        $andamento = 'Pendência';  // Exemplo de andamento
        $anexos = ['anexo1.pdf', 'anexo2.pdf'];  // Exemplo de anexos
        $observacoes = 'Observações sobre a falta';  // Exemplo de observações

        // Retorna a view do dashboard com as variáveis
        return view('dashboard.index', compact(
            'nome', 'matricula', 'email', 'cpf', 'datas', 'andamento', 'anexos', 'observacoes'
        ));
    }
}
