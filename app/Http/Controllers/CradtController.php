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
use Illuminate\Validation\Rule;
use App\Rules\MatriculaDisponivel;
use App\Rules\ValidCpf;

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

    /**
     * A CRADT abre o cadastro com os dados essenciais; a pessoa completa o restante no primeiro acesso.
     */
    public function register(Request $request)
    {
        $request->merge([
            'name' => trim((string) $request->input('name')),
            'email' => strtolower(trim((string) $request->input('email'))),
            'cpf' => preg_replace('/\D/', '', (string) $request->input('cpf')),
            'matricula' => trim((string) $request->input('matricula')),
        ]);

        $validated = $request->validate([
            'role' => ['required', Rule::in(['Aluno', 'Cradt'])],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'cpf' => ['required', 'string', 'size:11', new ValidCpf(), 'unique:users,cpf'],
            'matricula' => ['required', 'string', 'max:255', new MatriculaDisponivel()],
            'matriculas_adicionais' => ['nullable', 'array'],
            'matriculas_adicionais.*' => ['nullable', 'string', 'max:255', 'distinct', 'different:matricula', new MatriculaDisponivel()],
        ], [
            'cpf.unique' => 'Este CPF já está cadastrado.',
            'email.unique' => 'Este e-mail já está cadastrado.',
            'cpf.size' => 'Informe um CPF válido.',
            'matriculas_adicionais.*.distinct' => 'As matrículas adicionais não podem se repetir.',
            'matriculas_adicionais.*.different' => 'A matrícula adicional deve ser diferente da matrícula principal.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'cpf' => $validated['cpf'],
            'matricula' => $validated['matricula'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'username' => null,
        ]);

        $user->sincronizarMatriculasAdicionais($validated['matriculas_adicionais'] ?? []);

        return redirect()->route('cradt.register')
            ->with('success', "Pré-cadastro de {$user->name} realizado. No primeiro acesso, um código será enviado para {$user->email}.");
    }
}
