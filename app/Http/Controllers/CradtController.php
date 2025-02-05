<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CradtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        Gate::authorize('isCradt', $user);

        $requerimentos = ApplicationRequest::latest()->paginate(10); // Mantém a paginação ativa
        $datas = Carbon::now()->format('d/m/Y');

        return view('cradt.index', [
            'requerimentos' => $requerimentos,
            'datas' => $datas
        ]);
    }
}
