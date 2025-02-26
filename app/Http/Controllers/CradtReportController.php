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

        return view('cradt-report.index', compact('requerimentos', 'requerimentosTipo', 'requerimentosStatus', 'requerimentosTurnos', 'requerimentosCursos'));
    }



    public function getFilteredData(Request $request)
    {
        $mes = $request->input('mes');
        $ano = $request->input('ano');
        $filtro = $request->input('filtro');

        $startDate = Carbon::createFromDate($ano, $mes, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($ano, $mes, 1)->endOfMonth();

        $query = ApplicationRequest::whereBetween('created_at', [$startDate, $endDate]);

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
