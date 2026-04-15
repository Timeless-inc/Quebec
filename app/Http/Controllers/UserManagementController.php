<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

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
                  ->orWhere('matricula', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(15);

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
            'matricula'        => 'required|string|max:255|unique:users,matricula,' . $user->id,
            'second_matricula' => 'nullable|string|max:255|unique:users,second_matricula,' . $user->id,
            'rg'               => 'required|string|max:12|unique:users,rg,' . $user->id,
            'role'             => ['required', 'string', Rule::in($allowedRoles)],
        ]);

        $user->update($validatedData);

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