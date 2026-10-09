<?php

namespace App\Http\Middleware;

use App\Models\ApplicationRequest;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRequerimentoAccess
{
    /**
     * Alunos só acessam os próprios requerimentos; os demais cargos seguem as regras da rota.
     *
     * @param  string  $parametro  Nome do parâmetro da rota que traz o id do requerimento
     */
    public function handle(Request $request, Closure $next, string $parametro = 'id'): Response
    {
        $user = $request->user();

        if ($user->role === 'Aluno') {
            $requerimento = ApplicationRequest::findOrFail($request->route($parametro));

            $mesmoEmail = $user->email && $requerimento->email === $user->email;
            $mesmoCpf = $user->cpf && $this->digitos($requerimento->cpf) === $this->digitos($user->cpf);

            abort_unless($mesmoEmail || $mesmoCpf, 403, 'Você só pode acessar os seus próprios requerimentos.');
        }

        return $next($request);
    }

    private function digitos(?string $cpf): string
    {
        return preg_replace('/\D/', '', (string) $cpf);
    }
}
