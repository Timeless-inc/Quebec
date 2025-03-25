<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        $userId = Auth::id();
        $userIsCradt = $user->role === 'cradt';

        $events = Event::where('is_active', true)
            ->whereDate('end_date', '>=', now())
            ->where(function ($query) use ($userId, $userIsCradt) {
                $query->where('is_exception', false);

                if (!$userIsCradt) {
                    $query->orWhere(function ($q) use ($userId) {
                        $q->where('is_exception', true)
                            ->where('exception_user_id', $userId);
                    });
                } else {
                    $query->orWhere('is_exception', true);
                }
            })
            ->orderBy('start_date')
            ->get();

        $requerimentos = ApplicationRequest::where('email', $user->email)->paginate(10);
 
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
            'observacoes',
            'events'
        ));
    }
}
