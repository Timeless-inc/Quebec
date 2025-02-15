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

        // Busca os 5 requerimentos mais enviados
        $requerimentos = ApplicationRequest::select('situacao', DB::raw('count(*) as total'))
        ->groupBy('situacao')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

        return view('cradt-report.index', compact('requerimentos'));
    }}
