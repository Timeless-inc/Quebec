<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatriculaDisponivel;

class UserManagementController extends Controller
{
    public function index()
    {
        $query   = User::query();
        $roles   = Role::orderBy('label')->get();
        request()->flash();
        $request = request();

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('cpf', 'like', "%{$search}%")
                  ->orWhere('matricula', 'like', "%{$search}%")
                  ->orWhereHas('matriculasAdicionais', fn ($matriculas) => $matriculas->where('matricula', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->with('matriculasAdicionais')->orderBy('name')->paginate(15);

        return view('users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        if (request()->routeIs('diretor-geral.users.*') && !auth()->user()->isDiretorGeral()) {
            abort(403, 'Apenas o Diretor Geral pode gerenciar usuários.');
        }
        $roles = Role::orderBy('label')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $dynamicRoleLabels = Role::pluck('label')->toArray();
        $allowedRoles      = array_merge(['Aluno', 'Cradt', 'Diretor Geral'], $dynamicRoleLabels);

        $validatedData = $request->validate([
            'name'             => 'required|string|max:255',
            'username'         => 'required|string|max:255|unique:users,username,' . $user->id,
            'email'            => 'required|email|max:255|unique:users,email,' . $user->id,
            'cpf'              => 'required|string|max:14|unique:users,cpf,' . $user->id,
            'matricula'        => ['required', 'string', 'max:255', new MatriculaDisponivel($user->id)],
            'matriculas_adicionais'   => ['nullable', 'array'],
            'matriculas_adicionais.*' => ['nullable', 'string', 'max:255', 'distinct', 'different:matricula', new MatriculaDisponivel($user->id)],
            'rg'               => 'required|string|max:12|unique:users,rg,' . $user->id,
            'role'             => ['required', 'string', Rule::in($allowedRoles)],
        ], [
            'matriculas_adicionais.*.distinct'  => 'As matrículas adicionais não podem se repetir.',
            'matriculas_adicionais.*.different' => 'A matrícula adicional deve ser diferente da matrícula principal.',
        ]);

        $user->update(collect($validatedData)->except('matriculas_adicionais')->all());
        $user->sincronizarMatriculasAdicionais($validatedData['matriculas_adicionais'] ?? []);

        $indexRoute = Auth::user()->isDiretorGeral() ? 'diretor-geral.users.index' : 'users.index';
        return redirect()->route($indexRoute)->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        $indexRoute = Auth::user()->isDiretorGeral() ? 'diretor-geral.users.index' : 'users.index';

        if ($user->id === Auth::id()) {
            return redirect()->route($indexRoute)->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();
        return redirect()->route($indexRoute)->with('success', 'Usuário excluído com sucesso!');
    }
}