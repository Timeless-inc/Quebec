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
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule; // Import Rule

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $inputCpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        $inputMatricula = $request->input('matricula');

        $cradtPending = User::where('cpf', $inputCpf) 
            ->where('matricula', $inputMatricula)
            ->where('role', 'Cradt')
            ->whereNull('email')
            ->first();

        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($cradtPending?->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($cradtPending?->id)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matricula' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($cradtPending?->id)],
            'rg' => ['required', 'string', 'max:12', Rule::unique('users')->ignore($cradtPending?->id)],
            'cpf' => ['required', 'string', 'max:14', Rule::unique('users', 'cpf')->ignore($cradtPending?->id, 'id')->where(function ($query) use ($inputCpf) {
                 $query->where('cpf', $inputCpf);
            })],
        ];

        $errorMessages = [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'email.unique' => 'Este e-mail já está em uso.',
            'matricula.unique' => 'Esta matrícula já está cadastrada.', 
            'rg.unique' => 'Este RG já está cadastrado em nosso sistema.',
            'cpf.unique' => 'Este CPF já está cadastrado em nosso sistema.',
        ];

        $request->validate($validationRules, $errorMessages);

        if ($cradtPending) {
            $user = $cradtPending;
            $user->update([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rg' => $request->rg,
                'cpf' => $inputCpf, 
            ]);
        } else {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'matricula' => $request->matricula,
                'rg' => $request->rg,
                'cpf' => $inputCpf, 
                'role' => 'Aluno'
            ]);
        }

        event(new Registered($user));
        event(new UserRegistered($user));
        Auth::login($user);

        if ($user->role === 'Cradt') {
            return redirect(route('cradt.index', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
