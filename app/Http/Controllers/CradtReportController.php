<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CradtReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Acesso negado.');
        }
        Gate::authorize('isCradt', $user);

        $requerimentos = ApplicationRequest::select('situacao', DB::raw('count(*) as total'))
            ->groupBy('situacao')
            ->orderByDesc('total')
            ->get();

        $requerimentosTipo = ApplicationRequest::select('tipoRequisicao', DB::raw('count(*) as total'))
            ->groupBy('tipoRequisicao')
            ->orderByDesc('total')
            ->get();

        $requerimentosStatus = ApplicationRequest::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderByDesc('total')
            ->get();
            
        $requerimentosTurnos = ApplicationRequest::select('turno', DB::raw('count(*) as total'))
            ->groupBy('turno')
            ->orderByDesc('total')
            ->get();

        $requerimentosCursos = ApplicationRequest::select('curso', DB::raw('count(*) as total'))
            ->groupBy('curso')
            ->orderByDesc('total')
            ->get();

        return view('cradt-report.index', compact('requerimentos', 'requerimentosTipo', 'requerimentosStatus', 'requerimentosTurnos', 'requerimentosCursos'));
    }
}
