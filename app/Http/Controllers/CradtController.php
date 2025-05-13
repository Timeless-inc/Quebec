<?php

namespace App\Http\Controllers;

use App\Models\ApplicationRequest;
use App\Models\Event;
use App\Models\ProfileChangeRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CradtController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $status = request()->input('status', 'todos');

        $query = ApplicationRequest::query();

        if ($status && $status !== 'todos') {
            if ($status === 'em_aberto') {
                $query->whereIn('status', ['em_andamento', 'pendente']);
            } else {
                $query->where('status', $status);
            }
        }

        if (request()->has('search')) {
            $search = request()->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nomeCompleto', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%");
            });
        }

        $requerimentos = $query->latest()->paginate(10);
        $requerimentos->appends(['status' => $status]);

        $events = Event::orderBy('start_date')->get();

        // Busque todas as solicitações pendentes ou em revisão
        $profileRequests = ProfileChangeRequest::with('user')
            ->whereIn('status', ['pending', 'needs_review'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        
        // Agrupe por request_group_id
        $profileChangeGroups = $profileRequests->groupBy('request_group_id');
        

        return view('cradt.index', [
            'requerimentos' => $requerimentos,
            'events' => $events,
            'currentStatus' => $status,
            'profileRequests' => $profileRequests,
            'profileChangeGroups' => $profileChangeGroups,
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
