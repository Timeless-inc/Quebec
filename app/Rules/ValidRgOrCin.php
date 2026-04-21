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

        if ($this->normalizedCpf !== '' && $identity === $this->normalizedCpf) {
            if (!ValidCpf::isValid($identity)) {
                $fail('A CIN informada é inválida.');
            }

            return;
        }

        if (strlen($identity) < 7 || strlen($identity) > 11) {
            $fail('O RG deve ter entre 7 e 11 caracteres.');
            return;
        }

        if (!preg_match('/^[0-9A-Z]+$/', $identity)) {
            $fail('O RG deve conter apenas letras e números.');
        }
    }
}