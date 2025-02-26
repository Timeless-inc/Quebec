<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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
            $query->where(function ($q) use ($search) {
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

        $events = Event::orderBy('start_date')->get();

        $datas = Carbon::now()->format('d/m/Y');

        return view('cradt.index', [
            'requerimentos' => $requerimentos,
            'datas' => $datas,
            'events' => $events
        ]);
    }

        public function showRegistrationForm()
    {
        return view('cradt.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'cpf' => 'required|string|max:14|unique:users',
            'matricula' => 'required|string|max:255|unique:users',
        ]);
    
         $user = User::create([
        'cpf' => $validated['cpf'],
        'matricula' => $validated['matricula'],
        'role' => 'Cradt',
        'username' => null,
        'name' => null,
        'email' => null
    ]);
        return redirect()->route('cradt')->with('success', 'Pré-cadastro CRADT realizado com sucesso!');
    }
    
}
