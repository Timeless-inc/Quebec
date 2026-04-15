<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Migrar usuários com cargos antigos para Diretor Geral
        DB::table('users')
            ->whereIn('role', ['Professor', 'Coordenador'])
            ->update(['role' => 'Diretor Geral']);
    }

    public function down(): void
    {
        // Não é possível reverter automaticamente sem saber qual era o cargo original
        // Deixamos a reversão manual por segurança
    }
};
