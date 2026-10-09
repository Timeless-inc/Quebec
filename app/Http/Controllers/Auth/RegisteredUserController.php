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
        return view('auth.register', [
            'states' => $this->brazilStates(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $inputCpf = $this->normalizeCpf($request->input('cpf'));
        $inputRg = $this->normalizeRg($request->input('rg'));
        $inputRgUf = strtoupper(trim((string) $request->input('rg_uf')));
        $inputMatricula = trim((string) $request->input('matricula'));

        $request->merge([
            'username' => trim((string) $request->input('username')),
            'email' => strtolower(trim((string) $request->input('email'))),
            'matricula' => $inputMatricula,
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
            'rg_uf' => $inputRgUf,
            'rg' => $inputRg,
            'cpf' => $inputCpf,
        ]);

        $isCinDocument = $this->isCinDocument($inputRg, $inputCpf);

        // A CRADT abre o cadastro (CPF, nome e matrícula); aqui a pessoa só completa o primeiro acesso
        $preCadastro = User::where('cpf', $inputCpf)
            ->where('matricula', $inputMatricula)
            ->whereNull('email')
            ->first();

        $validationRules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($preCadastro?->id)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($preCadastro?->id)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'matricula' => ['required', 'string', 'max:255'],
            'rg' => ['bail', 'required', 'string', 'min:7', 'max:11', 'regex:/^[0-9A-Za-z]+$/', new ValidRgOrCin($inputCpf)],
            'rg_uf' => ['nullable', 'string', 'size:2', Rule::in(array_keys($this->brazilStates()))],
            'cpf' => ['required', 'string', 'size:11', new ValidCpf()],
            'data_nascimento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'telefone' => ['required', 'string', 'min:10', 'max:11'],
        ];

        $errorMessages = [
            'username.unique' => 'Este nome de usuário já está em uso.',
            'email.unique' => 'Este e-mail já está em uso.',
            'password.confirmed' => 'A confirmação de senha não corresponde.',
            'password.min' => 'A senha deve ter pelo menos :min caracteres.',
            'password.required' => 'A senha é obrigatória.',
            'rg.min' => 'O RG deve ter entre 7 e 11 caracteres.',
            'rg.max' => 'O RG deve ter entre 7 e 11 caracteres.',
            'rg.regex' => 'O RG deve conter apenas letras e números.',
            'rg_uf.size' => 'A UF deve conter 2 letras.',
            'rg_uf.in' => 'Selecione uma UF válida.',
            'data_nascimento.required' => 'Informe a data de nascimento.',
            'data_nascimento.before' => 'Informe uma data de nascimento válida.',
            'data_nascimento.after' => 'Informe uma data de nascimento válida.',
            'telefone.required' => 'Informe o telefone.',
            'telefone.min' => 'Informe o telefone com DDD.',
            'telefone.max' => 'Informe o telefone com DDD.',
        ];

        $validator = Validator::make($request->all(), $validationRules, $errorMessages);

        $validator->after(function ($validator) use ($preCadastro, $inputRg, $inputRgUf, $isCinDocument) {
            if (!$preCadastro) {
                $validator->errors()->add('cpf', 'Não encontramos um pré-cadastro com este CPF e matrícula. Procure a CRADT para liberar o seu acesso.');
                return;
            }

            if (!$isCinDocument && $inputRgUf === '') {
                $validator->errors()->add('rg_uf', 'Selecione a UF do RG legado.');
            }

            if (!$isCinDocument && $inputRgUf !== '') {
                $rgExists = User::query()
                    ->where('rg', $inputRg)
                    ->where('rg_uf', $inputRgUf)
                    ->where('id', '!=', $preCadastro->id)
                    ->exists();

                if ($rgExists) {
                    $validator->errors()->add('rg', 'Este RG já está cadastrado em nosso sistema para a UF selecionada.');
                }
            }
        });

        $validated = $validator->validate();

        $user = $preCadastro;
        $user->update([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'rg' => $validated['rg'],
            'rg_uf' => $isCinDocument ? null : $validated['rg_uf'],
            'data_nascimento' => $validated['data_nascimento'],
            'telefone' => $validated['telefone'],
        ]);

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

    private function isCinDocument(string $normalizedRg, string $normalizedCpf): bool
    {
        return $normalizedRg !== ''
            && $normalizedRg === $normalizedCpf
            && ValidCpf::isValid($normalizedRg);
    }

    private function brazilStates(): array
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins',
        ];
    }
}
