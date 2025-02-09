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

        $query = ApplicationRequest::query();
        $request = request();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomeCompleto', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('tipoRequisicao', 'like', "%{$search}%")
                  ->orWhereDate('created_at', 'like', $search)
                  ->orWhere('key', 'like', "%{$search}%");
            });
        }

        $requerimentos = $query->latest()->paginate(10);
        $datas = Carbon::now()->format('d/m/Y');

        return view('cradt.index', [
            'requerimentos' => $requerimentos,
            'datas' => $datas
        ]);
    }
}
