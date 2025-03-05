<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\UserRegistered; // Adicione esta linha
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
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matricula' => ['required', 'string', 'max:255'],
            'rg' => ['required', 'string', 'unique:users', 'max:255'],
            'cpf' => ['required', 'string', 'max:255'],
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

        //Evento de registro de usuário para envio de e-mail - passível de ser modificado
        event(new UserRegistered($user));
        
        Auth::login($user);

        if ($user->role === 'Cradt') {
            return redirect(route('cradt.index', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
