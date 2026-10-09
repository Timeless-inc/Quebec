<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private array $tabelas = [
        'requerimentos' => [
            'status' => ['em_andamento', 'indeferido', 'pendente', 'encaminhado', 'devolvido'],
            'padrao' => 'em_andamento',
        ],
        'request_forwardings' => [
            'status' => ['encaminhado', 'indeferido', 'pendente', 'devolvido', 'reencaminhado'],
            'padrao' => 'encaminhado',
        ],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->renomear('finalizado', 'deferido');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->renomear('deferido', 'finalizado');
    }

    /**
     * Troca o valor do ENUM sem perder dados: aceita os dois nomes, converte as linhas e remove o antigo.
     */
    private function renomear(string $de, string $para): void
    {
        foreach ($this->tabelas as $tabela => $coluna) {
            $this->alterarEnum($tabela, [...$coluna['status'], $de, $para], $coluna['padrao']);

            DB::table($tabela)->where('status', $de)->update(['status' => $para]);

            $this->alterarEnum($tabela, [...$coluna['status'], $para], $coluna['padrao']);
        }
    }

    private function alterarEnum(string $tabela, array $valores, string $padrao): void
    {
        $lista = implode(', ', array_map(fn ($valor) => "'{$valor}'", $valores));

        DB::statement("ALTER TABLE `{$tabela}` MODIFY `status` ENUM({$lista}) NOT NULL DEFAULT '{$padrao}'");
    }
};
