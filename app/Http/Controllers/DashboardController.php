<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if (!$user) {
        abort(403, 'Acesso negado.');
    }

    $requerimentos = ApplicationRequest::where('email', $user->email)
        ->latest()
        ->get();

    $datas = Carbon::now()->format('d/m/Y');
    $nome = $user->name;
    $matricula = $user->matricula;
    $email = $user->email;
    $cpf = $user->cpf;
    
    $currentStatus = 'em_andamento';
    $anexos = ['requerimento_TSI202420892.png', 'hbshdbfhbaajcmsncanjbs.png', 'bshdbfhbaajcmsnjcanbs.img'];
    $observacoes = 'Observações sobre a falta';

    return view('dashboard.index', compact(
        'requerimentos',
        'nome',
        'matricula',
        'email',
        'cpf',
        'datas',
        'currentStatus',
        'anexos',
        'observacoes'
    ));
}




}
