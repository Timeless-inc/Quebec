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
        
        $status = $request->input('status', 'todos'); 
        
        if ($status && $status !== 'todos') {
            if ($status === 'em_aberto') {
                $query->whereIn('status', ['em_andamento', 'pendente']);
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomeCompleto', 'like', "%{$search}%")
                    ->orWhere('matricula', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('tipoRequisicao', 'like', "%{$search}%")
                    ->orWhere('key', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('date_filter') && $request->date_filter != '') {
            try {
                $dateInput = $request->date_filter;
                
                if (strpos($dateInput, '-') !== false) {
                    $date = Carbon::createFromFormat('Y-m-d', $dateInput);
                } else {
                    $date = Carbon::createFromFormat('d/m/Y', $dateInput);
                }
                
                $query->whereDate('created_at', $date->format('Y-m-d'));
            } catch (\Exception $e) {
            }
        }

        $requerimentos = $query->latest()->paginate(10);
        
        $requerimentos->appends($request->query());

        $events = Event::orderBy('start_date')->get();
        $datas = Carbon::now()->format('d/m/Y');

        return view('cradt.index', [
            'requerimentos' => $requerimentos,
            'datas' => $datas,
            'events' => $events,
            'currentStatus' => $status
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
