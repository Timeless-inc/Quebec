<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidRgOrCin implements ValidationRule
{
    public function __construct(private readonly string $normalizedCpf)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $identity = strtoupper(preg_replace('/[^0-9A-Za-z]/', '', (string) $value));

        if ($identity === '') {
            $fail('Informe um RG ou CIN válida.');
            return;
        }

        if (ctype_digit($identity) && strlen($identity) === 11) {
            if (!ValidCpf::isValid($identity)) {
                $fail('A CIN informada é inválida.');
                return;
            }

            if ($this->normalizedCpf !== '' && $identity !== $this->normalizedCpf) {
                $fail('Quando a identidade for CIN, o número deve ser igual ao CPF informado.');
            }

            return;
        }

        if (!preg_match('/^\d{6,13}[0-9X]$/', $identity)) {
            $fail('Informe um RG válido ou uma CIN válida.');
        }
    }
}