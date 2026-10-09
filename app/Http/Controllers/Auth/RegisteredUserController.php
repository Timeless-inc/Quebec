<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Events\UserRegistered;
use App\Mail\PrimeiroAcessoCodigoMail;
use App\Rules\ValidCpf;
use App\Rules\ValidRgOrCin;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

/**
 * Primeiro acesso: a CRADT abre o cadastro (nome, CPF, matrícula e e-mail institucional) e a pessoa
 * o ativa em três passos: identificação, código enviado por e-mail e, por fim, senha e demais dados.
 */
class RegisteredUserController extends Controller
{
    private const SESSAO = 'primeiro_acesso';
    private const CODIGO_MINUTOS = 15;
    private const MAX_TENTATIVAS = 5;
    private const MAX_ENVIOS = 3;

    public function create(): View
    {
        return view('auth.primeiro-acesso.identificacao');
    }

    /**
     * Passo 1: confere o pré-cadastro e envia o código para o e-mail informado pela CRADT.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'cpf' => $this->normalizeCpf($request->input('cpf')),
            'matricula' => trim((string) $request->input('matricula')),
        ]);

        $request->validate([
            'cpf' => ['required', 'string', 'size:11'],
            'matricula' => ['required', 'string', 'max:255'],
        ], [
            'cpf.required' => 'Informe o CPF.',
            'cpf.size' => 'Informe um CPF válido.',
            'matricula.required' => 'Informe a matrícula.',
        ]);

        $preCadastro = User::where('cpf', $request->input('cpf'))
            ->where('matricula', $request->input('matricula'))
            ->whereNotNull('email')
            ->whereNull('password')
            ->first();

        if (!$preCadastro) {
            return back()->withInput()->withErrors([
                'cpf' => 'Não encontramos um pré-cadastro com este CPF e matrícula. Procure a CRADT para liberar o seu acesso.',
            ]);
        }

        if ($erro = $this->enviarCodigo($preCadastro)) {
            return back()->withInput()->withErrors(['cpf' => $erro]);
        }

        $request->session()->put(self::SESSAO, ['user_id' => $preCadastro->id, 'verificado' => false]);

        return redirect()->route('primeiro-acesso.codigo');
    }

    public function showCodeForm(Request $request): View|RedirectResponse
    {
        $user = $this->usuarioDaSessao($request);

        if (!$user) {
            return redirect()->route('register');
        }

        return view('auth.primeiro-acesso.codigo', [
            'emailMascarado' => $this->mascararEmail($user->email),
            'minutos' => self::CODIGO_MINUTOS,
        ]);
    }

    /**
     * Passo 2: só segue para os dados quem digitar o código recebido por e-mail.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        $user = $this->usuarioDaSessao($request);

        if (!$user) {
            return redirect()->route('register');
        }

        $request->merge(['codigo' => preg_replace('/\D/', '', (string) $request->input('codigo'))]);
        $request->validate(['codigo' => ['required', 'digits:6']], [
            'codigo.required' => 'Informe o código recebido por e-mail.',
            'codigo.digits' => 'O código tem 6 dígitos.',
        ]);

        $chave = $this->chaveDoCodigo($user);
        $registro = Cache::get($chave);

        if (!$registro) {
            return back()->withErrors(['codigo' => 'O código expirou. Solicite um novo.']);
        }

        if ($registro['tentativas'] >= self::MAX_TENTATIVAS) {
            Cache::forget($chave);

            return back()->withErrors(['codigo' => 'Muitas tentativas incorretas. Solicite um novo código.']);
        }

        if (!Hash::check($request->input('codigo'), $registro['hash'])) {
            $registro['tentativas']++;
            Cache::put($chave, $registro, $registro['expira_em']);

            return back()->withErrors(['codigo' => 'Código incorreto. Confira o e-mail e tente novamente.']);
        }

        Cache::forget($chave);
        $request->session()->put(self::SESSAO, ['user_id' => $user->id, 'verificado' => true]);

        return redirect()->route('primeiro-acesso.dados');
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $user = $this->usuarioDaSessao($request);

        if (!$user) {
            return redirect()->route('register');
        }

        if ($erro = $this->enviarCodigo($user)) {
            return back()->withErrors(['codigo' => $erro]);
        }

        return back()->with('status', 'Enviamos um novo código para o seu e-mail.');
    }

    public function showDataForm(Request $request): View|RedirectResponse
    {
        $user = $this->usuarioDaSessao($request, verificado: true);

        if (!$user) {
            return redirect()->route('register');
        }

        return view('auth.register', [
            'user' => $user,
            'states' => $this->brazilStates(),
        ]);
    }

    /**
     * Passo 3: define a senha e completa os dados que a CRADT não informa.
     */
    public function complete(Request $request): RedirectResponse
    {
        $user = $this->usuarioDaSessao($request, verificado: true);

        if (!$user) {
            return redirect()->route('register');
        }

        $inputRg = $this->normalizeRg($request->input('rg'));
        $inputRgUf = strtoupper(trim((string) $request->input('rg_uf')));

        $request->merge([
            'username' => trim((string) $request->input('username')),
            'telefone' => preg_replace('/\D/', '', (string) $request->input('telefone')),
            'rg_uf' => $inputRgUf,
            'rg' => $inputRg,
        ]);

        $isCinDocument = $this->isCinDocument($inputRg, $user->cpf);

        $validationRules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'rg' => ['bail', 'required', 'string', 'min:7', 'max:11', 'regex:/^[0-9A-Za-z]+$/', new ValidRgOrCin($user->cpf)],
            'rg_uf' => ['nullable', 'string', 'size:2', Rule::in(array_keys($this->brazilStates()))],
            'data_nascimento' => ['required', 'date', 'before:today', 'after:1900-01-01'],
            'telefone' => ['required', 'string', 'min:10', 'max:11'],
        ];

        $errorMessages = [
            'username.unique' => 'Este nome de usuário já está em uso.',
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

        $validator->after(function ($validator) use ($user, $inputRg, $inputRgUf, $isCinDocument) {
            if (!$isCinDocument && $inputRgUf === '') {
                $validator->errors()->add('rg_uf', 'Selecione a UF do RG legado.');
            }

            if (!$isCinDocument && $inputRgUf !== '') {
                $rgExists = User::query()
                    ->where('rg', $inputRg)
                    ->where('rg_uf', $inputRgUf)
                    ->where('id', '!=', $user->id)
                    ->exists();

                if ($rgExists) {
                    $validator->errors()->add('rg', 'Este RG já está cadastrado em nosso sistema para a UF selecionada.');
                }
            }
        });

        $validated = $validator->validate();

        $user->update([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'rg' => $validated['rg'],
            'rg_uf' => $isCinDocument ? null : $validated['rg_uf'],
            'data_nascimento' => $validated['data_nascimento'],
            'telefone' => $validated['telefone'],
        ]);

        // O código digitado comprova o acesso ao e-mail informado pela CRADT
        $user->forceFill(['email_verified_at' => now()])->save();

        $request->session()->forget(self::SESSAO);

        event(new Registered($user));
        event(new UserRegistered($user));
        Auth::login($user);

        if ($user->role === 'Cradt') {
            return redirect(route('cradt.index', absolute: false));
        }

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Gera um novo código, guarda apenas o hash e o envia para o e-mail do pré-cadastro.
     * Devolve a mensagem de erro, ou null quando o envio deu certo.
     */
    private function enviarCodigo(User $user): ?string
    {
        // O limite é por cadastro, e não por IP, porque um campus inteiro pode sair pelo mesmo endereço
        $limite = 'primeiro-acesso-envio:' . $user->id;

        if (RateLimiter::tooManyAttempts($limite, self::MAX_ENVIOS)) {
            return 'Você já pediu vários códigos. Aguarde alguns minutos antes de tentar de novo.';
        }

        RateLimiter::hit($limite, self::CODIGO_MINUTOS * 60);

        $codigo = (string) random_int(100000, 999999);
        $expiraEm = now()->addMinutes(self::CODIGO_MINUTOS);

        Cache::put($this->chaveDoCodigo($user), [
            'hash' => Hash::make($codigo),
            'tentativas' => 0,
            'expira_em' => $expiraEm,
        ], $expiraEm);

        try {
            Mail::to($user->email)->send(new PrimeiroAcessoCodigoMail($user, $codigo, self::CODIGO_MINUTOS));
        } catch (\Throwable $e) {
            Log::error('Falha ao enviar o código de primeiro acesso', ['user_id' => $user->id, 'erro' => $e->getMessage()]);
            Cache::forget($this->chaveDoCodigo($user));

            return 'Não foi possível enviar o código para o seu e-mail agora. Tente novamente em instantes.';
        }

        return null;
    }

    /**
     * Usuário pendente guardado na sessão do primeiro acesso, se ainda não ativou a conta.
     */
    private function usuarioDaSessao(Request $request, bool $verificado = false): ?User
    {
        $sessao = $request->session()->get(self::SESSAO);

        if (!$sessao || ($verificado && empty($sessao['verificado']))) {
            return null;
        }

        return User::where('id', $sessao['user_id'])->whereNull('password')->first();
    }

    private function chaveDoCodigo(User $user): string
    {
        return 'primeiro_acesso_codigo_' . $user->id;
    }

    private function mascararEmail(string $email): string
    {
        [$usuario, $dominio] = explode('@', $email, 2);

        return mb_substr($usuario, 0, 2) . str_repeat('*', max(mb_strlen($usuario) - 2, 3)) . '@' . $dominio;
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
