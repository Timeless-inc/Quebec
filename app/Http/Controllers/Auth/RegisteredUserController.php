<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\UserRegistered;
use App\Rules\ValidCpf;
use App\Rules\ValidRgOrCin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $inputCpf = $this->normalizeCpf($request->input('cpf'));
        $inputRg = $this->normalizeRg($request->input('rg'));
        $inputMatricula = trim((string) $request->input('matricula'));
        $hasSecondMatricula = $request->boolean('has_second_matricula');
        $inputSecondMatricula = $hasSecondMatricula ? trim((string) $request->input('second_matricula')) : null;

        $request->merge([
            'username' => trim((string) $request->input('username')),
            'name' => trim((string) $request->input('name')),
            'email' => strtolower(trim((string) $request->input('email'))),
            'matricula' => $inputMatricula,
            'second_matricula' => $inputSecondMatricula,
            'has_second_matricula' => $hasSecondMatricula ? 1 : 0,
            'rg' => $inputRg,
            'cpf' => $inputCpf,
        ]);

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
            'matricula' => ['required', 'string', 'max:255'],
            'second_matricula' => ['nullable', 'required_if:has_second_matricula,1', 'string', 'max:255'],
            'rg' => ['required', 'string', 'max:14', new ValidRgOrCin($inputCpf), Rule::unique('users')->ignore($cradtPending?->id)],
            'cpf' => ['required', 'string', 'size:11', new ValidCpf(), Rule::unique('users', 'cpf')->ignore($cradtPending?->id)],
            'has_second_matricula' => ['sometimes', 'boolean'],
        ];

        $errorMessages = [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'email.unique' => 'Este e-mail já está em uso.',
            'rg.unique' => 'Este RG já está cadastrado em nosso sistema.',
            'cpf.unique' => 'Este CPF já está cadastrado em nosso sistema.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'second_matricula.required_if' => 'Informe a segunda matrícula ou desmarque a opção de segunda matrícula.',
        ];

        $validator = Validator::make($request->all(), $validationRules, $errorMessages);

        $validator->after(function ($validator) use ($cradtPending, $inputMatricula, $inputSecondMatricula) {
            if ($cradtPending) {
                return;
            }

            $matriculaExists = User::query()
                ->where(function ($query) use ($inputMatricula) {
                    $query->where('matricula', $inputMatricula)
                        ->orWhere('second_matricula', $inputMatricula);
                })
                ->exists();

            if ($matriculaExists) {
                $validator->errors()->add('matricula', 'Esta matrícula já está cadastrada em nosso sistema (como matrícula principal ou secundária).');
            }

            if (empty($inputSecondMatricula)) {
                return;
            }

            $secondMatriculaExists = User::query()
                ->where(function ($query) use ($inputSecondMatricula) {
                    $query->where('matricula', $inputSecondMatricula)
                        ->orWhere('second_matricula', $inputSecondMatricula);
                })
                ->exists();

            if ($secondMatriculaExists) {
                $validator->errors()->add('second_matricula', 'Esta matrícula secundária já está cadastrada em nosso sistema.');
            }

            if ($inputMatricula === $inputSecondMatricula) {
                $validator->errors()->add('second_matricula', 'A matrícula secundária deve ser diferente da matrícula principal.');
            }
        });

        $validated = $validator->validate();

        if ($cradtPending) {
            $user = $cradtPending;
            $user->update([
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'rg' => $validated['rg'],
                'cpf' => $inputCpf,
                'second_matricula' => $hasSecondMatricula ? $validated['second_matricula'] : null,
            ]);
        } else {
            $user = User::create([
                'username' => $validated['username'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'matricula' => $validated['matricula'],
                'second_matricula' => $hasSecondMatricula ? $validated['second_matricula'] : null,
                'rg' => $validated['rg'],
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

    private function normalizeCpf(?string $cpf): string
    {
        return preg_replace('/\D/', '', (string) $cpf);
    }

    private function normalizeRg(?string $rg): string
    {
        return strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $rg));
    }
}
