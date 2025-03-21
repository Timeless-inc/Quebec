<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $anosDisponiveis = DB::table('requerimentos')
            ->select(DB::raw('YEAR(created_at) as ano'))
            ->distinct()
            ->orderBy('ano')
            ->pluck('ano')
            ->toArray();

        if(empty($anosDisponiveis)) {
            $anosDisponiveis = [date('Y')];
        }

        return view('cradt-report.index', compact('requerimentos', 'requerimentosTipo', 'requerimentosStatus', 'requerimentosTurnos', 'requerimentosCursos', 'anosDisponiveis'));
    }



    public function getFilteredData(Request $request)
{
    $mes = $request->input('mes');
    $ano = $request->input('ano');
    $filtro = $request->input('filtro');

    $query = ApplicationRequest::query();

    if ($mes === 'all') {
        $startDate = Carbon::createFromDate($ano, 1, 1)->startOfYear();
        $endDate = Carbon::createFromDate($ano, 12, 31)->endOfYear();
    } else {

        $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();
    }

    $query = $query->whereBetween('created_at', [$startDate, $endDate]);

    switch ($filtro) {
        case 'tipo':
            $data = $query->select('tipoRequisicao as label', DB::raw('count(*) as total'))
                ->groupBy('tipoRequisicao')
                ->get();
            break;
        case 'status':
            $data = $query->select('status as label', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
            break;
        case 'turno':
            $data = $query->select('turno as label', DB::raw('count(*) as total'))
                ->groupBy('turno')
                ->get();
            break;
        case 'curso':
            $data = $query->select('curso as label', DB::raw('count(*) as total'))
                ->groupBy('curso')
                ->get();
            break;
        default:
            $data = collect();
    }

    return response()->json($data);
}
}
