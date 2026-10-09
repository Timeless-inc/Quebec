<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MatriculaDisponivel implements ValidationRule
{
    public function __construct(private ?int $ignorarUserId = null)
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (User::matriculaEmUso((string) $value, $this->ignorarUserId)) {
            $fail("A matrícula {$value} já está cadastrada em nosso sistema.");
        }
    }
}
