<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\UserRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matricula' => ['required', 'string', 'max:255', 'unique:users'],
            'rg' => ['required', 'string', 'max:12', 'unique:users'],
            'cpf' => ['required', 'string', 'max:14', 'unique:users'],
        ], [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'email.unique' => 'Este e-mail já está em uso.',
            'matricula.unique' => 'Esta matrícula já está cadastrada.',
            'rg.unique' => 'Este RG já está cadastrado em nosso sistema.',
            'cpf.unique' => 'Este CPF já está cadastrado em nosso sistema.',
        ]);

        $cradtPending = User::where('cpf', $request->cpf)
            ->where('matricula', $request->matricula)
            ->where('role', 'Cradt')
            ->first();

        if ($cradtPending) {
            $user = $cradtPending;
            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rg' => $request->rg,
                'role' => 'Cradt'
            ]);
            $user = $cradtPending;
        } else {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'matricula' => $request->matricula,
                'rg' => $request->rg,
                'cpf' => $request->cpf,
                'role' => 'Aluno'
            ]);
        }

        event(new Registered($user));   

        //Evento de registro de usuário para envio de e-mail - passível de ser modificado
        event(new UserRegistered($user));
        
        Auth::login($user);

        if ($user->role === 'Cradt') {
            return redirect(route('cradt.index', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
