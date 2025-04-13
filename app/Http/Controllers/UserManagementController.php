<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Acesso negado.');
        }

        Gate::authorize('isCradt', $user);

        $query = User::query();
        request()->flash(); 
        $request = request();
        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
        
        return view('users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('users')->ignore($user->id)],
            'matricula' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'rg' => ['required', 'string', 'max:12', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'string', 'in:Aluno,Cradt'],
        ]);

        // Se uma nova senha foi fornecida
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'confirmed', 'min:8'],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }

    public function destroy(User $user)
    {
        // Proteção contra exclusão da própria conta
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Você não pode excluir sua própria conta!');
        }

        $user->delete();
        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso!');
    }
}